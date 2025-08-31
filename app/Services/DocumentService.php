<?php
namespace App\Services;

// require 'vendor/autoload.php'; // Ensure PhpWord is autoloaded
require 'C:/Users/AYMAN FRIMANE/Documents/laravel/centre-etudes-doctorales/vendor/autoload.php';


use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Style;
use App\Models\DemandeSession;

class DocumentService
{
    // public function generateAvisDeSoutenanceDocument($templatePath, $outputPath, $data)
    // {
    //     $templateProcessor = new TemplateProcessor($templatePath);
        
    //     // Replace placeholders
    //     foreach ($data as $placeholder => $value) {
    //         if (is_array($value)) {
    //             // Handle arrays differently (e.g., cloning blocks or processing lists)
    //             if ($placeholder === 'jury_members') {
    //                 // Assuming 'jury_members' represents a block in the Word template
    //                 // You may need to adjust this based on your template structure
    //                 $templateProcessor->cloneBlock('jury_members_block', count($value), true, false, $value);
    //             }
    //         } else {
    //             // Convert non-array values to strings
    //             $stringValue = is_string($value) ? $value : strval($value);
    //             Log::info("Placeholder: $placeholder, Value: $value");
    //             $templateProcessor->setValue($placeholder, $stringValue);
    //         }
    //     }
    //     Log::info(" saving path: $outputPath");
    //     $templateProcessor->saveAs($outputPath);
    // }

    public function generateDiplomaDocument($templatePath, $outputPath, $data)
    {
        $templateProcessor = new TemplateProcessor($templatePath);
        
        /// Replace placeholders for non-array data
        foreach ($data as $placeholder => $value) {
            if (!is_array($value)) {
                $stringValue = is_string($value) ? $value : strval($value);
                $templateProcessor->setValue($placeholder, $stringValue);
            }
        }
        if (!empty($data['jury_members'])) {
            $templateProcessor->cloneBlock('jury_block', count($data['jury_members']), true, true);
            foreach ($data['jury_members'] as $index => $juryMember) {
                $indexPlusOne = $index + 1;
                $templateProcessor->setValue('prenom_ma#' . $indexPlusOne, $juryMember['prenom_ma']);
                $templateProcessor->setValue('m_grade#' . $indexPlusOne, $juryMember['m_grade']);
                $templateProcessor->setValue('role#' . $indexPlusOne, $juryMember['role']);
            }
        }

        $templateProcessor->saveAs($outputPath);
    }






    public function generateAvisDeSoutenanceDocument($templatePath, $outputPath, $data)
    {
        $templateProcessor = new TemplateProcessor($templatePath);
        
        // Replace placeholders for non-array data
        foreach ($data as $placeholder => $value) {
            if (!is_array($value)) {
                $stringValue = is_string($value) ? $value : strval($value);
                $templateProcessor->setValue($placeholder, $stringValue);
            }
        }

        // Handle jury members separately
        if (!empty($data['jury_members'])) {
            $templateProcessor->cloneBlock('jury_members_block', count($data['jury_members']), true, true);
            foreach ($data['jury_members'] as $index => $juryMember) {
                $indexPlusOne = $index + 1;
                $templateProcessor->setValue('title_m#' . $indexPlusOne, $juryMember['title_m']);
                $templateProcessor->setValue('prenom_m#' . $indexPlusOne, $juryMember['prenom_m']);
                $templateProcessor->setValue('nom_m#' . $indexPlusOne, $juryMember['nom_m']);
                $templateProcessor->setValue('qualiti#' . $indexPlusOne, $juryMember['qualiti']);
                $templateProcessor->setValue('affiliation#' . $indexPlusOne, $juryMember['affiliation']);
                $templateProcessor->setValue('role_m#' . $indexPlusOne, $juryMember['role_m']);
            }
        }

        $templateProcessor->saveAs($outputPath);
    }





    // public function generateAttestationDuJuryMember($templatePath, $outputPath,$data)
    // {
        
    //     $templateProcessor = new TemplateProcessor($templatePath);
        
    //     foreach ($data as $placeholder => $value) {
    //         $templateProcessor->setValue($placeholder, $value);
    //     }
    //      // Path to the signature image
    //     $signatureImagePath = Storage::path('signatures/signature.png');

    //     // Insert the e-signature image
    //     $templateProcessor->setImageValue('e_signature', [
    //         'path' => $signatureImagePath,
    //         'width' => 200, // Adjust width as needed
    //         'height' => 200, // Adjust height as needed
    //     ]);

    //     $templateProcessor->saveAs($outputPath);

    //     // Optionally, you can return the path to the generated document if needed
    //     // return $outputPath;
    // }

    public function generateAttestationDuJuryMember($templatePath, $outputPath, $data)
    {
        Log::info('Generating attestation for jury member:', $data);

        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            foreach ($data as $placeholder => $value) {
                $templateProcessor->setValue($placeholder, $value);
            }

            // Path to the signature image
            $signatureImagePath = Storage::path('signatures/signature.png');

            // Insert the e-signature image
            Log::info('Inserting e-signature image:', ['path' => $signatureImagePath]);
            $templateProcessor->setImageValue('e_signature', [
                'path' => $signatureImagePath,
                'width' => 200, // Adjust width as needed
                'height' => 200, // Adjust height as needed
            ]);

            Log::info('Saving generated document:', ['outputPath' => $outputPath]);
            $templateProcessor->saveAs($outputPath);
            Log::info('Document generation successful.');

            // Optionally, you can return the path to the generated document if needed
            // return $outputPath;
        } catch (\Exception $e) {
            Log::error('Failed to generate attestation for jury member:', $data);
            Log::error('Error message:', $e->getMessage());
            // Handle the exception as needed
        }
    }




    public function generateinvitationDuJuryMember($templatePath, $outputPath,$data)
    {
        
        $templateProcessor = new TemplateProcessor($templatePath);
        
        foreach ($data as $placeholder => $value) {
            $templateProcessor->setValue($placeholder, $value);
        }
        // Path to the signature image
        // $signatureImagePath = Storage::path('signatures/signature.png');

        // // Insert the e-signature image
        // $templateProcessor->setImageValue('e_signature', [
        //     'path' => $signatureImagePath,
        //     'width' => 200, // Adjust width as needed
        //     'height' => 200, // Adjust height as needed
        // ]);

        $templateProcessor->saveAs($outputPath);

        // Optionally, you can return the path to the generated document if needed
        // return $outputPath;
    }


    public function generateattestationDocument($templatePath, $outputPath, $data)
    {

        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            foreach ($data as $placeholder => $value) {
                $templateProcessor->setValue($placeholder, $value);
            }

            // Path to the signature image
            $signatureImagePath = Storage::path('signatures/signature.png');

            // Insert the e-signature image
            $templateProcessor->setImageValue('e_signature', [
                'path' => $signatureImagePath,
                'width' => 200, // Adjust width as needed
                'height' => 200, // Adjust height as needed
            ]);

            $templateProcessor->saveAs($outputPath);

            // Optionally, you can return the path to the generated document if needed
            // return $outputPath;
        } catch (\Exception $e) {
            Log::error('Error generating attestation document:', ['message' => $e->getMessage()]);
            // Handle the exception as needed
        }
    }


    // public function generateRapportDuJuryDeSoutenanceDocument($templatePath, $outputPath, $data)
    // {
    //     $templateProcessor = new TemplateProcessor($templatePath);
        
    //     // Replace placeholders for non-array data
    //     foreach ($data as $placeholder => $value) {
    //         if (!is_array($value)) {
    //             $stringValue = is_string($value) ? $value : strval($value);
    //             $templateProcessor->setValue($placeholder, $stringValue);
    //         }
    //     }

    //     // Handle jury members separately
    //     if (!empty($data['jury_members'])) {
    //         $templateProcessor->cloneBlock('jury_members_block', count($data['jury_members']), true, true);
    //         foreach ($data['jury_members'] as $index => $juryMember) {
    //             $indexPlusOne = $index + 1;
    //             $templateProcessor->setValue('title_m#' . $indexPlusOne, $juryMember['title_m']);
    //             $templateProcessor->setValue('prenom_m#' . $indexPlusOne, $juryMember['prenom_m']);
    //             $templateProcessor->setValue('nom_m#' . $indexPlusOne, $juryMember['nom_m']);
    //             $templateProcessor->setValue('role_m#' . $indexPlusOne, $juryMember['role_m']);
    //         }
    //     }

    //     // Handle scolarities separately
    //     if (!empty($data['scolarities'])) {
    //         $templateProcessor->cloneBlock('scolarite_Block', count($data['scolarities']), true, true);
    //         foreach ($data['scolarities'] as $index => $scolarity) {
    //             $indexPlusOne = $index + 1;
    //             $templateProcessor->setValue('s_niveau#' . $indexPlusOne, $scolarity['s_niveau']);
    //             $templateProcessor->setValue('s_specialite#' . $indexPlusOne, $scolarity['s_specialite']);
    //             $templateProcessor->setValue('s_mois#' . $indexPlusOne, $scolarity['s_mois']);
    //             $templateProcessor->setValue('s_annee#' . $indexPlusOne, $scolarity['s_annee']);
    //             $templateProcessor->setValue('s_mt#' . $indexPlusOne, $scolarity['s_mt']);

    //             // Add other placeholders for scolarities here if needed
    //         }
    //     }

    //     $templateProcessor->saveAs($outputPath);
    // }

    public function generateRapportDuJuryDeSoutenanceDocument($templatePath, $outputPath, $data)
    {
        $templateProcessor = new TemplateProcessor($templatePath);
        
        // Replace placeholders for non-array data
        foreach ($data as $placeholder => $value) {
            if (!is_array($value)) {
                $stringValue = is_string($value) ? $value : strval($value);
                $templateProcessor->setValue($placeholder, $stringValue);
            }
        }

        // Handle jury members separately
        for ($i = 0; $i < 3 ; $i++){
            if (!empty($data['jury_members'])) {
                $templateProcessor->cloneBlock('jury_members_block', count($data['jury_members']), true, true);
                foreach ($data['jury_members'] as $index => $juryMember) {
                    $indexPlusOne = $index + 1;
                    $templateProcessor->setValue('title_m#' . $indexPlusOne, $juryMember['title_m']);
                    $templateProcessor->setValue('prenom_m#' . $indexPlusOne, $juryMember['prenom_m']);
                    $templateProcessor->setValue('nom_m#' . $indexPlusOne, $juryMember['nom_m']);
                    $templateProcessor->setValue('role_m#' . $indexPlusOne, $juryMember['role_m']);
                }
            }
        }

        // Handle scolarities separately
        if (!empty($data['scolarities'])) {
            $templateProcessor->cloneBlock('scolarite_block', count($data['scolarities']), true, true);
            foreach ($data['scolarities'] as $index => $scolarity) {
                $indexPlusOne = $index + 1;
                $templateProcessor->setValue('sniveau#' . $indexPlusOne, $scolarity['sniveau']);
                $templateProcessor->setValue('sspecialite#' . $indexPlusOne, $scolarity['sspecialite']);
                $templateProcessor->setValue('smois#' . $indexPlusOne, $scolarity['smois']);
                $templateProcessor->setValue('sannee#' . $indexPlusOne, $scolarity['sannee']);
                $templateProcessor->setValue('smention#' . $indexPlusOne, $scolarity['smention']);
            }
        }


        // // Handle scolarities separately
        // if (!empty($data['scolarities'])) {
        //     $index = 1; // Start index for placeholders
        //     foreach ($data['scolarities'] as $scolarity) {
        //         $templateProcessor->setValue('sniveau#' . $index, $scolarity['sniveau']);
        //         $templateProcessor->setValue('sspecialite#' . $index, $scolarity['sspecialite']);
        //         $templateProcessor->setValue('smois#' . $index, $scolarity['smois']);
        //         $templateProcessor->setValue('sannee#' . $index, $scolarity['sannee']);
        //         $templateProcessor->setValue('smention#' . $index, $scolarity['smention']);
        //         $index++; // Increment index for the next scolarity
        //     }
        // }

        // Handle repeating non-array placeholders
        foreach ($data as $placeholder => $value) {
            if (!is_array($value)) {
                $templateProcessor->setValue($placeholder, $value, 1);
            }
        }

        $templateProcessor->saveAs($outputPath);
    }


    public function generateDemandeSoutenance($demande)
    {
        try {
            // Chemin vers le modèle de document
            $templatePath = storage_path('app/templates/Demande_de_soutenance_de_thèse_final.docx');

            if (!file_exists($templatePath)) {
                throw new \Exception("Le fichier modèle n'existe pas : " . $templatePath);
            }

            // Préparer les données pour remplacer dans le modèle
            $datePremiereInscription = Carbon::parse($demande->date_premiere_inscription);
            $session = Carbon::parse($demande->session)->locale('fr')->isoFormat('D MMMM YYYY');

            $data = [
                'session' => $session,
                'nom_doc' => $demande->doctorant_nom,
                'prenom_doc' => $demande->doctorant_prenom,
                'titre' => $demande->titre,
                'nom_encadrant' => $demande->encadrant_nom,
                'prenom_encadrant' => $demande->encadrant_prenom,
                'structureRecherche' => $demande->structure_recherche,
                'formationDoctorale' => $demande->formation,
                'datePremiereInscription' => $datePremiereInscription->year . '-' . $datePremiereInscription->addYear()->year,
                'nb_pub_article' => $demande->nombre_publications_article,
                'nb_pub_conference' => $demande->nombre_publications_conference,
                'nb_communications' => $demande->nombre_communications,
            ];

            // Charger le modèle Word
            $templateProcessor = new TemplateProcessor($templatePath);

            // Remplacer les valeurs dans le modèle Word
            foreach ($data as $key => $value) {
                $templateProcessor->setValue($key, $value);
            }

            // Chemin pour sauvegarder le document généré
            $outputPath = 'public/demandes/' . $demande->doctorant_nom . '-' . $demande->doctorant_prenom . '_demande.docx';
            $outputFile = storage_path('app/' . $outputPath);

            // Assurez-vous que le dossier de destination existe
            if (!file_exists(dirname($outputFile))) {
                mkdir(dirname($outputFile), 0755, true);
            }

            // Sauvegarder le document généré
            $templateProcessor->saveAs($outputFile);

            // Retourner le chemin du fichier généré
            return $outputPath;
        } catch (\Exception $e) {
            throw $e;
        }
    }
 
    public function generatePVGlobal($idSession)
    {
        // Chemin vers le modèle Excel PV global
        $templatePath = storage_path('app/templates/PV_global_commission_de thèses.xlsx');
    
        if (!file_exists($templatePath)) {
            throw new \Exception("Le fichier modèle n'existe pas : " . $templatePath);
        }
    
        // Charger la session depuis le modèle DemandeSession
        $demandeSession = DemandeSession::findOrFail($idSession);
        
        // Formater la date de la session
        $sessionDate = \Carbon\Carbon::parse($demandeSession->date)->locale('fr')->isoFormat('D MMMM YYYY');
        $sessionDateForFilename = \Carbon\Carbon::parse($demandeSession->date)->format('Y-m-d');
    
        // Charger le modèle Excel existant
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Récupérer le style de la deuxième ligne (la ligne 2) pour les colonnes B à L
        $rowToCopy = 5;
        $columnsToCopy = range('B', 'L');
        $styleArray = [];
        foreach ($columnsToCopy as $col) {
            $cellStyle = $sheet->getStyle($col . $rowToCopy);
            $styleArray[$col] = $cellStyle;
        }


    
        // Insérer le titre de la session à la ligne 2 dans les cellules C à G
        $sheet->setCellValue('C2', 'PV global de la commission des thèses – Session ' . $sessionDate);
        $sheet->mergeCells('C2:G2');
        $sheet->getStyle('C2:G2')->getAlignment()->setHorizontal('center');
    
        // Récupérer les doctorants ayant posé une demande dans la même session
        $doctorants = DB::table('theses')
            ->join('demandes', 'theses.id', '=', 'demandes.id_these')
            ->join('doctorants', 'theses.id_doctorant', '=', 'doctorants.id')
            ->join('membres', 'doctorants.id_encadrant', '=', 'membres.id')
            ->where('demandes.id_session', $idSession)
            ->select(
                'doctorants.CINE',
                'doctorants.nom as nom_doc',
                'doctorants.prenom as prenom_doc',
                'doctorants.email as email_doc',
                'doctorants.tele as telephone_doc',
                'theses.titreOriginal',
                'membres.nom as nom_encadrant',
                'membres.prenom as prenom_encadrant',
                'membres.email as email_encadrant',
                'membres.tele as telephone_encadrant',
                'theses.structure_recherche',
                'theses.formation',
                'theses.date_premiere_inscription',
                'demandes.etat'
            )
            ->get();
    
        // Ligne de départ pour l'insertion des données des doctorants
        $startRow = 5;
    
        // Insérer les données des doctorants dans le classeur Excel
        foreach ($doctorants as $index => $doctorant) {
            $row = $startRow + $index;
            $date_premiere = \Carbon\Carbon::parse($doctorant->date_premiere_inscription);
    
            // Remplir les cellules spécifiques
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $doctorant->nom_doc . ' ' . $doctorant->prenom_doc);
            $sheet->setCellValue('C' . $row, $doctorant->email_doc);
            $sheet->setCellValue('D' . $row, $doctorant->telephone_doc);
            $sheet->setCellValue('E' . $row, $doctorant->titreOriginal);
            $sheet->setCellValue('F' . $row, $doctorant->nom_encadrant . ' ' . $doctorant->prenom_encadrant);
            $sheet->setCellValue('G' . $row, $doctorant->email_encadrant);
            $sheet->setCellValue('H' . $row, $doctorant->telephone_encadrant);
            $sheet->setCellValue('I' . $row, $doctorant->structure_recherche);
            $sheet->setCellValue('J' . $row, $doctorant->formation);
            $sheet->setCellValue('K' . $row, $date_premiere->year . '-' . $date_premiere->addYear()->year);
    
            // Analyser l'état de la demande pour remplir l'avis de la commission
            $avis = '';
            if ($doctorant->etat == 'Acceptée') {
                $avis = 'Avis Favorable';
            } elseif ($doctorant->etat == 'Refusée') {
                $avis = 'Avis Défavorable';
            }
            $sheet->setCellValue('L' . $row, $avis);
        }
    
        // Générer le chemin pour sauvegarder le fichier
        $outputPath = 'public/PV_global/' . $sessionDate . '_PV_global_commission_de_thèses.xlsx';
        $outputFile = storage_path('app/' . $outputPath);
    
        // Sauvegarder le fichier Excel généré
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        try {
            $writer->save($outputFile);
        } catch (\Exception $e) {
            throw $e;
        }
    
        return $outputPath;
    }


    
    // Add more functions for different document types as needed 
}