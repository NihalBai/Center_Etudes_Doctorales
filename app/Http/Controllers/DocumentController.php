<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Doctorant;
use App\Models\Soutenance;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log;
use Exception;
use ZipArchive;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function generateAvisDeSoutenance($doctorantId)
    {
        $doctorant = Doctorant::with([
            'theses',
            'soutenances.localisation',
            'soutenances.juryMembers' => function ($query) {
                $query->with(['faculte', 'autre'])->withPivot('qualite');
            }
        ])->findOrFail($doctorantId);

        $soutenance = $doctorant->soutenances->first();

        $data = [
            'date_impression' => Carbon::now()->format('d/m/Y'),
            'title_d' => $doctorant->sex === 'male' ? 'Monsieur' : 'Madame',
            'prenom_d' => $doctorant->prenom,
            'nom_d' => $doctorant->nom,
            'titre_original' => $doctorant->theses->titreOriginal,
            'jour_l' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('dddd')),
            'jour_n' => ucfirst(Carbon::parse($soutenance->date)->isoFormat('D')),
            'mois' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('MMMM')),
            'annee' => Carbon::parse($soutenance->heure)->format('Y'),
            'heure' => Carbon::parse($soutenance->heure)->format('H:i'),
            'local' => $soutenance->localisation->nom,
            'jury_members' => []
        ];


        $juryMembers = $soutenance->juryMembers->map(function($juryMember) use ($soutenance) {
            $grade = $juryMember->getGradeBefore($soutenance->date);
            return [
                'title_m' => $juryMember->sex === 'male' ? 'Monsieur' : 'Madame',
                'prenom_m' => $juryMember->prenom,
                'nom_m' => $juryMember->nom,
                'qualiti' => $grade->nom,
                'affiliation' => $juryMember->getNameAttribute(),
                'role_m' => $juryMember->pivot->qualite
            ];
        });
        
        // Custom sorting function
        $sortedJuryMembers = $juryMembers->sort(function($a, $b) {
            $order = [
                'Président' => 1,
                'Président/rapporteur' => 1,
                'Co_directeur' => 3,
                'Directeur de thèse' => 4,
            ];
        
            $aOrder = $order[$a['role_m']] ?? 2;
            $bOrder = $order[$b['role_m']] ?? 2;
        
            return $aOrder <=> $bOrder;
        })->values(); // Ensure to reindex the array
        
        $data['jury_members'] = $sortedJuryMembers->toArray();


        $filename = $doctorant->id . ' (' . $doctorant->prenom . ' ' . $doctorant->nom . ').docx';
        $filename = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $filename); // Ensure safe filename with period preserved

        $templatePath = Storage::path('templates/AvisDeSoutenance.docx');
        $outputPath = Storage::path('documents/' . $filename);

        $this->documentService->generateAvisDeSoutenanceDocument($templatePath, $outputPath, $data);

        return response()->download($outputPath);
    }


    public function attestationDuJuryDeSoutenance($id_doctorant)
    {
        // Log::info("Starting attestation generation for doctorant ID: $id_doctorant");

        $doctorant = Doctorant::with([
            'theses',
            'soutenances.localisation',
            'soutenances.juryMembers' => function ($query) {
                $query->with(['faculte', 'autre'])->withPivot('qualite');
            }
        ])->findOrFail($id_doctorant);

        // Log::info("Doctorant data loaded: ", ['doctorant' => $doctorant]);

        $soutenance = $doctorant->soutenances->first();

        // Log::info("First soutenance loaded: ", ['soutenance' => $soutenance]);

        $zip = new ZipArchive;
        $zipFileName = 'attestation_jury_' . $doctorant->nom . '_' . $doctorant->prenom . '.zip';
        $zipFilePath = storage_path('app/documents/' . $zipFileName);

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($soutenance->juryMembers as $juryMember) {

                // Log::info("Processing jury member: ", ['juryMember' => $juryMember]);
                $role_m = $juryMember->pivot->qualite;
                // Check if the role is "Président/rapporteur" and modify accordingly
                if ($role_m === 'Président/rapporteur') {
                    $role_m = 'rapporteur et président';
                }

                $data = [
                    'n_doyen' => "BARAKAT",
                    'p_doyen' => "Ahemed",
                    'nom_m' => $juryMember->nom,
                    'prenom_m' => $juryMember->prenom,
                    'role_m' =>  $role_m,
                    'title_d' => $doctorant->sex === 'male' ? 'Mr' : 'Mme',
                    'nom_d' => $doctorant->nom,
                    'prenom_d' => $doctorant->prenom,
                    'jour_l' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('dddd')),
                    'jour_n' => ucfirst(Carbon::parse($soutenance->date)->isoFormat('D')),
                    'mois' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('MMMM')),
                    'annee' => Carbon::parse($soutenance->heure)->format('Y'),
                    'titre_original' => $doctorant->theses->titreOriginal,
                    'date_impression' => Carbon::now()->format('d/m/Y'),
                    // 'e_signature' => "Electronic Signature",
                ];

                // Log::info("Data for document generation: ", ['data' => $data]);

                $filename = 'attestation de (' . $data['nom_m'] . ' ' . $data['prenom_m'] . ').docx';
                $filename = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $filename); // Ensure safe filename with period preserved

                // Log::info("Generated filename: $filename");

                $templatePath = Storage::path('templates/AttestationDuJury.docx');
                $outputPath = Storage::path('documents/' . $filename);

                // Log::info("Template path: $templatePath, Output path: $outputPath");
                
                $this->documentService->generateAttestationDuJuryMember($templatePath, $outputPath, $data);

                // Add the generated document to the zip archive
                $zip->addFile($outputPath, $filename);
            }

            $zip->close();

            // Return the zip file for download
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            Log::error("Failed to create zip archive at: $zipFilePath");
            // Handle failure to create the zip archive
            return response()->json(['error' => 'Failed to create zip archive'], 500);
        }
    }

    public function invitationsDuJuryDeSoutenance($id_doctorant)
    {
        $doctorant = Doctorant::with([
            'soutenances.juryMembers' => function ($query) {
                $query->withPivot('qualite');
            }
        ])->findOrFail($id_doctorant);

        $soutenance = $doctorant->soutenances->first();

        foreach ($soutenance->juryMembers as $juryMember) {
            $this->invitationDuJuryDeSoutenance($doctorant->id, $juryMember->id);
        }
        return redirect()->route('soutenance.show', ['doctorantId' => $doctorant->id]);
    }
    

    public function invitationDuJuryDeSoutenance($id_doctorant,$id_member)
    {
        // $doctorant = Doctorant::with([
        //     'theses',
        //     'soutenances.localisation',
        //     'soutenances.juryMembers' => function ($query) use ($id_member) {
        //         $query->with(['faculte', 'autre'])
        //               ->withPivot('qualite')
        //               ->where('membres.id', $id_member);
        //     }
        // ])->findOrFail($id_doctorant);

        $doctorant = Doctorant::with([
            'theses',
            'soutenances.localisation',
            'soutenances.juryMembers' => function ($query) use ($id_member) {
                $query->with([
                    'faculte' => function ($query) {
                        $query->with('universite');
                    },
                    'autre'
                ])->withPivot('qualite')
                  ->where('membres.id', $id_member);
            }
        ])->findOrFail($id_doctorant);

        $soutenance = $doctorant->soutenances->first();

        $juryMember = $soutenance->juryMembers->first();
        $data = [
            'title_m'=> $juryMember->sex === 'male' ? 'Monsieur': 'Madame',
            'prn'=> $juryMember->sex === 'male' ? 'le': 'la',
            'nom_m' => $juryMember->nom,
            'prenom_m' => $juryMember->prenom,
            'faculte'=> $juryMember->faculte->nom,
            'ville'=> $juryMember->faculte->ville,
            'universite'=> $juryMember->faculte->universite->nom,
            'role_m' => $juryMember->pivot->qualite,
            'title_d' => $doctorant->sex === 'male' ? 'Monsieur' : 'Madame',
            'nom_d' => $doctorant->nom,
            'prenom_d' => $doctorant->prenom,
            'jour_l' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('dddd')),
            'jour_n' => ucfirst(Carbon::parse($soutenance->date)->isoFormat('D')),
            'mois' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('MMMM')),
            'annee' => Carbon::parse($soutenance->heure)->format('Y'),
            'heure' => Carbon::parse($soutenance->heure)->format('H:i'),
            'local' => $soutenance->localisation->nom,
            'date_impression' => Carbon::now()->format('d/m/Y'),
            // 'e_signature' => "Electronic Signature",
        ];


        $isPresident = in_array($data['role_m'], ['Président', 'Président/rapporteur']);
        $templateName = $isPresident ? 'InvitationPresident.docx' : 'InvitationMembre.docx';
    
        $filename = 'invitation de (' . $data['nom_m'] . ' ' . $data['prenom_m'] . ').docx';
        $filename = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $filename); // Ensure safe filename with period preserved
    
        $templatePath = Storage::path('templates/' . $templateName);
        $outputPath = Storage::path('documents/' . $filename);
    
        $this->documentService->generateinvitationDuJuryMember($templatePath, $outputPath,$data);
            
        return response()->download($outputPath);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    public function downloadJuryInvitations($id_doctorant)
    {
        $doctorant = Doctorant::with([
            'soutenances.juryMembers' => function ($query) {
                $query->withPivot('qualite');
            }
        ])->findOrFail($id_doctorant);

        $soutenance = $doctorant->soutenances->first();
        $zip = new ZipArchive();
        $zipFileName = 'invitations_jury_' . $doctorant->id . '.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($soutenance->juryMembers as $juryMember) {
                $outputPath = $this->generateJuryMemberInvitation($doctorant->id, $juryMember->id);
                $filename = basename($outputPath);
                $zip->addFile($outputPath, $filename);
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Unable to create zip file'], 500);
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    private function generateJuryMemberInvitation($id_doctorant, $id_member)
    {
        $doctorant = Doctorant::with([
            'theses',
            'soutenances.localisation',
            'soutenances.juryMembers' => function ($query) use ($id_member) {
                $query->with([
                    'faculte.universite', // Nested relation for faculte and universite
                    'autre'
                ])->withPivot('qualite')
                  ->where('membres.id', $id_member);
            }
        ])->findOrFail($id_doctorant);

        $soutenance = $doctorant->soutenances->first();
        $juryMember = $soutenance->juryMembers->first();
        $data = [
            'title_m'=> $juryMember->sex === 'male' ? 'Monsieur': 'Madame',
            'prn'=> $juryMember->sex === 'male' ? 'le': 'la',
            'nom_m' => $juryMember->nom,
            'prenom_m' => $juryMember->prenom,
            'faculte'=> $juryMember->faculte->nom,
            'ville'=> $juryMember->faculte->ville,
            'universite'=> $juryMember->faculte->universite->nom,
            'role_m' => $juryMember->pivot->qualite,
            'title_d' => $doctorant->sex === 'male' ? 'Monsieur' : 'Madame',
            'nom_d' => $doctorant->nom,
            'prenom_d' => $doctorant->prenom,
            'jour_l' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('dddd')),
            'jour_n' => ucfirst(Carbon::parse($soutenance->date)->isoFormat('D')),
            'mois' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('MMMM')),
            'annee' => Carbon::parse($soutenance->heure)->format('Y'),
            'heure' => Carbon::parse($soutenance->heure)->format('H:i'),
            'local' => $soutenance->localisation->nom,
            'date_impression' => Carbon::now()->format('d/m/Y'),
        ];

        $isPresident = in_array($data['role_m'], ['Président', 'Président/rapporteur']);
        $templateName = $isPresident ? 'InvitationPresident.docx' : 'InvitationMembre.docx';
        $filename = 'invitation_de_' . $data['nom_m'] . '_' . $data['prenom_m'] . '.docx';
        $filename = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $filename); // Ensure safe filename with period preserved

        $templatePath = Storage::path('templates/' . $templateName);
        $outputPath = Storage::path('documents/' . $filename);

        $this->documentService->generateinvitationDuJuryMember($templatePath, $outputPath, $data);

        return $outputPath;
    }
    
    
    
    
    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////


    public function generateAttestation($doctorantId)
    {
        $doctorant = Doctorant::with([
            'theses',
            'soutenances.localisation',
            'soutenances.juryMembers' => function ($query) {
                $query->with(['faculte', 'autre'])->withPivot('qualite');
            }
        ])->findOrFail($doctorantId);

        $soutenance = $doctorant->soutenances->first();
        $these = $doctorant->theses->first();

        if ($these->titreFinal) {
            $titre = $these->titreFinal;
        } else {
            $titre = $these->titreOriginal;
        }

        $data = [
            'prenom_d' => $doctorant->prenom,
            'nom_d' => $doctorant->nom,
            'datedeN' => Carbon::parse($doctorant->date_de_naissance)->format('d/m/Y'),
            'lieudeN' => $doctorant->lieu,
            'jour_n' => ucfirst(Carbon::parse($soutenance->date)->isoFormat('D')),
            'mois' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('MMMM')),
            'annee' => Carbon::parse($soutenance->date)->format('Y'),
            'titredethese' => $titre,
            'formation' => $these->formation,
            'discipline' => $doctorant->discipline,
            'specialite' => $soutenance->resultat->specialite,
            'mention' => $soutenance->resultat->mention,
            'date_impression' => Carbon::now()->format('d/m/Y'),
            
        ];

        $filename = $doctorant->id . ' attestation (' . $doctorant->prenom . ' ' . $doctorant->nom . ').docx';
        $filename = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $filename); // Ensure safe filename with period preserved

        $templatePath = Storage::path('templates/AttestationDuResultat.docx');
        $outputPath = Storage::path('documents/' . $filename);

        $this->documentService->generateattestationDocument($templatePath, $outputPath, $data);

        return response()->download($outputPath);
    }
    


    // public function generateRapportDuJuryDeSoutenance($doctorantId)
    // {
    //     $doctorant = Doctorant::with([
    //         'theses',
    //         'theses.demandes',
    //         'soutenances',
    //         'effectuer',
    //         'scolarites',
    //         'soutenances.juryMembers' => function ($query) {
    //             $query->withPivot('qualite');
    //         },
            
    //     ])->findOrFail($doctorantId);

    //     $soutenance = $doctorant->soutenances->first();
    //     $these = $doctorant->theses;

    //     // Fetch all scolarities for the doctorant
    //     $scolaritiesData = [];
    //     foreach ($doctorant->scolarites as $scolarity) {
    //         $scolaritiesData[] = [
    //             'sniveau' => $scolarity->niveau,
    //             'sspecialite' => $scolarity->specialite,
    //             'smois' => $scolarity->mois,
    //             'sannee' => $scolarity->annee,
    //             'smention' => $scolarity->mention,
    //             // Add any other relevant information here...
    //         ];
    //     }
       
    //     // Log the $scolaritiesData array
    //     Log::info('Scolarities Data:', ['scolaritiesData' => $scolaritiesData]);

    //     // Prepare data for jury members
    //     $juryMembersData = [];
    //     foreach ($soutenance->juryMembers as $juryMember) {
    //         $juryMembersData[] = [
    //             'title_m' => $juryMember->sex === 'male' ? 'Monsieur' : 'Madame',
    //             'prenom_m' => $juryMember->prenom,
    //             'nom_m' => $juryMember->nom,
    //             'role_m' => $juryMember->pivot->qualite
    //         ];
    //     }

    //      // Calculate the university year based on the soutenance date
    //     $soutenanceDate = Carbon::parse($soutenance->date);
    //     $soutenanceYear = $soutenanceDate->year;
    //     $soutenanceMonth = $soutenanceDate->month;

    //     if ($soutenanceMonth >= 9) { // From September to December
    //         $anneeuniv = "{$soutenanceYear}/" . ($soutenanceYear + 1);
    //     } else { // From January to July
    //         $anneeuniv = ($soutenanceYear - 1) . "/{$soutenanceYear}";
    //     }

    //     // Format the order number
    //     $shortYear = substr($soutenanceYear, -2); // Get last two digits of the year
    //     $norderFormatted = $doctorant->effectuer->numero_ordre . '/' . $shortYear;

    //     $data = [
    //         'prenom_d' => $doctorant->prenom,
    //         'nom_d' => $doctorant->nom,
    //         'titledoc' => $doctorant->sex === 'male' ? 'Monsieur' : 'Madame',
    //         'titredethese' => $these->titreOriginal,
    //         'formation_d' => $these->formation,
    //         'norder'    => $norderFormatted,
    //         'ninscri'    => $these->demandes->RNES ,
    //         'datedeN' => Carbon::parse($doctorant->date_de_naissance)->format('d/m/Y'),
    //         'lieudeN' => $doctorant->lieu,
    //         'jour_l' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('dddd')),
    //         'jour_n' => ucfirst(Carbon::parse($soutenance->date)->isoFormat('D')),
    //         'mois' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('MMMM')),
    //         'mois_n' => ucfirst(Carbon::parse($soutenance->date)->isoFormat('MM')),
    //         'annee' => Carbon::parse($soutenance->date)->format('Y'),
    //         'anneeuniv' => $anneeuniv,
    //         'scolarities' => $scolaritiesData,
    //         'discipline' => $doctorant->discipline,
    //         // 'specialite' => $soutenance->resultat->specialite,
    //         // 'mention' => $soutenance->resultat->mention,
    //         'jury_members' => $juryMembersData,
    //         // Add other relevant data here...
    //     ];

    //     // Generate the document using the appropriate template
    //     $filename = $doctorant->id . ' rapport du jury (' . $doctorant->prenom . ' ' . $doctorant->nom . ').docx';
    //     $filename = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $filename);

    //     $templatePath = Storage::path('templates/RapportDeJuryDeSoutenance.docx');
    //     $outputPath = Storage::path('documents/' . $filename);

    //     $this->documentService->generateRapportDuJuryDeSoutenanceDocument($templatePath, $outputPath, $data);

    //     return response()->download($outputPath);
    // }
    public function generateRapportDuJuryDeSoutenance($doctorantId)
{
    $doctorant = Doctorant::with([
        'theses',
        'theses.demandes',
        'soutenances',
        'effectuer',
        'scolarites',
        'soutenances.juryMembers' => function ($query) {
            $query->withPivot('qualite');
        },
    ])->findOrFail($doctorantId);

    $soutenance = $doctorant->soutenances->first();
    $these = $doctorant->theses;

    // Fetch all scolarities for the doctorant
    $scolaritiesData = [];
    foreach ($doctorant->scolarites as $scolarity) {
        $scolaritiesData[] = [
            'sniveau' => $scolarity->niveau,
            'sspecialite' => $scolarity->specialite,
            'smois' => $scolarity->mois,
            'sannee' => $scolarity->annee,
            'smention' => $scolarity->mention,
            // Add any other relevant information here...
        ];
    }

    // Log the $scolaritiesData array
    Log::info('Scolarities Data:', ['scolaritiesData' => $scolaritiesData]);

    // Prepare data for jury members
    $juryMembersData = [];
    foreach ($soutenance->juryMembers as $juryMember) {
        $juryMembersData[] = [
            'title_m' => $juryMember->sex === 'male' ? 'Monsieur' : 'Madame',
            'prenom_m' => $juryMember->prenom,
            'nom_m' => $juryMember->nom,
            'role_m' => $juryMember->pivot->qualite
        ];
    }

    // Custom sorting function
    $sortedJuryMembers = collect($juryMembersData)->sort(function($a, $b) {
        $order = [
            'Président' => 1,
            'Président/rapporteur' => 1,
            'Co_directeur' => 3,
            'Directeur de thèse' => 4,
        ];

        $aOrder = $order[$a['role_m']] ?? 2;
        $bOrder = $order[$b['role_m']] ?? 2;

        return $aOrder <=> $bOrder;
    })->values(); // Ensure to reindex the array

    // Calculate the university year based on the soutenance date
    $soutenanceDate = Carbon::parse($soutenance->date);
    $soutenanceYear = $soutenanceDate->year;
    $soutenanceMonth = $soutenanceDate->month;

    if ($soutenanceMonth >= 9) { // From September to December
        $anneeuniv = "{$soutenanceYear}/" . ($soutenanceYear + 1);
    } else { // From January to July
        $anneeuniv = ($soutenanceYear - 1) . "/{$soutenanceYear}";
    }

    // Format the order number
    $shortYear = substr($soutenanceYear, -2); // Get last two digits of the year
    $norderFormatted = $doctorant->effectuer->numero_ordre . '/' . $shortYear;

    $data = [
        'prenom_d' => $doctorant->prenom,
        'nom_d' => $doctorant->nom,
        'titledoc' => $doctorant->sex === 'male' ? 'Monsieur' : 'Madame',
        'titredethese' => $these->titreOriginal,
        'formation_d' => $these->formation,
        'norder' => $norderFormatted,
        'ninscri' => $these->demandes->RNES,
        'datedeN' => Carbon::parse($doctorant->date_de_naissance)->format('d/m/Y'),
        'lieudeN' => $doctorant->lieu,
        'jour_l' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('dddd')),
        'jour_n' => ucfirst(Carbon::parse($soutenance->date)->isoFormat('D')),
        'mois' => ucfirst(Carbon::parse($soutenance->date)->locale('fr')->isoFormat('MMMM')),
        'mois_n' => ucfirst(Carbon::parse($soutenance->date)->isoFormat('MM')),
        'annee' => Carbon::parse($soutenance->date)->format('Y'),
        'anneeuniv' => $anneeuniv,
        'scolarities' => $scolaritiesData,
        'discipline' => $doctorant->discipline,
        // 'specialite' => $soutenance->resultat->specialite,
        // 'mention' => $soutenance->resultat->mention,
        'jury_members' => $sortedJuryMembers->toArray(),
        // Add other relevant data here...
    ];

    // Generate the document using the appropriate template
    $filename = $doctorant->id . ' rapport du jury (' . $doctorant->prenom . ' ' . $doctorant->nom . ').docx';
    $filename = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $filename);

    $templatePath = Storage::path('templates/RapportDeJuryDeSoutenance.docx');
    $outputPath = Storage::path('documents/' . $filename);

    $this->documentService->generateRapportDuJuryDeSoutenanceDocument($templatePath, $outputPath, $data);

    return response()->download($outputPath);
}


    public function generateDiploma($doctorantId)
    {
        $doctorant = Doctorant::with([
            'theses',
            'doctorantArabe',
            'soutenances.localisation',
            'soutenances.juryMembers' => function ($query) {
                $query->with(['membreArabe'])->withPivot('qualite');
            }
        ])->findOrFail($doctorantId);

        $soutenance = $doctorant->soutenances->first();
        $these = $doctorant->theses->first();

        // Prepare data for jury members
        $juryMembersData = [];
        foreach ($soutenance->juryMembers as $juryMember) {
            $juryMembersData[] = [
                'prenom_ma' => $juryMember->membreArabe->prenom,
                'm_grade' => $juryMember->membreArabe->grade,
                'role' => $juryMember->membreArabe->qualite
            ];
        }

        // Calculate the university year based on the soutenance date
        $soutenanceDate = Carbon::parse($soutenance->date);
        $soutenanceYear = $soutenanceDate->year;

        $monthsArabic = [
            'January' => 'يناير',
            'February' => 'فبراير',
            'March' => 'مارس',
            'April' => 'أبريل',
            'May' => 'مايو',
            'June' => 'يونيو',
            'July' => 'يوليو',
            'August' => 'غشت',
            'September' => 'سبتمبر',
            'October' => 'أكتوبر',
            'November' => 'نوفمبر',
            'December' => 'ديسمبر',
        ];
        
        $daten = Carbon::parse($doctorant->date_de_naissance);
        $arabicMonth = $monthsArabic[$daten->format('F')]; // Get the Arabic month name
        $formattedDate = $daten->format('d ') . $arabicMonth . $daten->format(' Y'); // Combine the day, Arabic month, and year
        
        

        

        // Format the order number
        $shortYear = substr($soutenanceYear, -2); // Get last two digits of the year
        $norderFormatted = $doctorant->effectuer->numero_ordre . '/' . $shortYear;

        $demandeDate = Carbon::parse($these->demandes->date);
        $demandeYear = $demandeDate->year;
        // Format the order number
        $demandeshortYear = substr($demandeYear, -2); // Get last two digits of the year
        $numFormatted = $these->demandes->num . '/' . $demandeshortYear;


        
        // determine the title of the dissertation
        $titre = $these->titreFinal ? $these->titreFinal : $these->titreOriginal;

        // Define the mapping array for mentions
        $mentionTranslations = [
            'Passable' => 'مقبول',
            'Honorable' => 'جيد',
            'Très Honorable' => 'جيد جدًا',
            'Très Honorable avec félicitations du jury' => 'ممتاز بتهاني اللجنة',
        ];

        // Get the mention from the result and translate it to Arabic
        $mention = $soutenance->resultat->mention;
        $mentionArabic = isset($mentionTranslations[$mention]) ? $mentionTranslations[$mention] : '';

        // Prepare data for the document
        $data = [
            'nom' => $doctorant->nom,
            'prenom' => $doctorant->prenom,
            'prenoma' => $doctorant->doctorantArabe->prenom, // Assuming this is the same as $prenom
            'noma' => $doctorant->doctorantArabe->nom, // Assuming this is the same as $nom
            'cin' => $doctorant->CINE,
            'lieu' => $doctorant->lieu,
            'daten' => $formattedDate,
            'formation' => $these->demandes->formation,
            'num' => $numFormatted,
            'norder'    => $norderFormatted,
            'discipline' => $doctorant->discipline,
            'specialite' => $soutenance->resultat->specialite,
            'disciplinea' => $doctorant->doctorantArabe->discipline,
            'specialitea' => $doctorant->doctorantArabe->specialite,
            'titrethese' => $titre,
            'mention' => $soutenance->resultat->mention,
            'mentionA' => $mentionArabic, 
            'jury_members' => $juryMembersData,
        ];

        // Generate the document using the appropriate template
        $filename = $doctorant->id . ' diploma (' . $doctorant->prenom . ' ' . $doctorant->nom . ').docx';
        $filename = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $filename);

        $templatePath = Storage::path('templates/Diplome.docx');
        $outputPath = Storage::path('documents/' . $filename);

        $this->documentService->generateDiplomaDocument($templatePath, $outputPath, $data);

        return response()->download($outputPath);
    }



    public function index()
    {
        $templates = [
            'AttestationDuJury',
            'AttestationDuResultat',
            'AvisDeSoutenance',
            'Diplome',
            'InvitationMembre',
            'InvitationPresident',
            'RapportDeJuryDeSoutenance',
            'Demande_de_soutenance_de_thèse_final',
            'PV_global_commission_de thèses',
        ];

        return view('templates', compact('templates'));
    }

    

    public function printTemplate($template)
    {
        $disk = Storage::disk('templates');
    
        // Ensure template has the correct extension
        $extension = pathinfo($template, PATHINFO_EXTENSION);
        if (empty($extension)) {
            // Check if the template name is "PV_global_commission_de thèses"
            if ($template === 'PV_global_commission_de thèses') {
                $template .= '.xlsx'; // Add .xlsx extension
            } else {
                $template .= '.docx'; // Add .docx extension for other templates
            }
        }
    
        $absolutePath = storage_path('app/templates/' . $template);
    
        if (!file_exists($absolutePath)) {
            Log::error('File does not exist: ' . $absolutePath);
            return response('File does not exist', 404);
        }
    
        $file = file_get_contents($absolutePath);
    
        if ($file === false) {
            return response('Failed to read file', 500);
        }
    
        if (empty($file)) {
            return response('File is empty', 400);
        }
    
        $headers = [
            'Content-Disposition' => 'inline; filename="' . $template . '"',
        ];
    
        if ($extension === 'pdf') {
            $headers['Content-Type'] = 'application/pdf';
        } elseif ($extension === 'docx') {
            $headers['Content-Type'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        } elseif ($extension === 'xlsx') {
            $headers['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        } else {
            $headers['Content-Type'] = 'application/octet-stream';
        }
    
        return response($file, 200, $headers);
    }

    public function replaceTemplate(Request $request, $template)
    {
        // Validate the incoming request
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,xlsx', // Include xlsx MIME type
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        if (!in_array($extension, ['pdf', 'docx', 'xlsx'])) { // Include xlsx extension
            return response('Invalid file extension', 400);
        }

        $templatePath = $template . '.' . $extension;

        if (!$file->isValid() || $file->getSize() == 0) {
            return response('File is empty or invalid', 400);
        }

        $fileContents = file_get_contents($file->getRealPath());

        if (empty($fileContents)) {
            return response('File contents are empty', 400);
        }

        if (Storage::disk('templates')->exists($templatePath)) {
            Storage::disk('templates')->delete($templatePath);
        }

        Storage::disk('templates')->put($templatePath, $fileContents);

        return redirect()->route('templates')->with('success', 'Modèle remplacé avec succès.');
    }


}
        


