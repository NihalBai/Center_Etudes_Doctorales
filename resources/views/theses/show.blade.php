<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thèse</title>
    <base href="/public">
</head>
<body>
@extends($layoutFile)

@section('content')
<main id="main" class="main">

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Bienvenue!</h5>

            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('demandes.index') }}">Demandes</a></li>
                    <li class="breadcrumb-item active">Voir Thèse</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><center><strong>Thèse</strong></center></h5>

                        <!-- Affichage des détails de la thèse -->
                        <table>
                            <tr class="even">
                                <th style="width: 50%;">Titre Original</th>
                                <td>{{ $these->titreOriginal }}</td>
                            </tr>
                            <tr class="odd">
                                <th>Titre Final</th>
                                <td>{{ $these->titreFinal ?: '-' }}</td>
                            </tr>
                            <tr class="even">
                                <th>Acceptation Directeur</th>
                                <td>{{ $these->acceptationDirecteur }}</td>
                            </tr>
                            <tr class="odd">
                                <th>Formation Doctorale</th>
                                <td>{{ $these->formation }}</td>
                            </tr>
                            <tr class="even">
                                <th>Doctorant</th>
                                <td>{{ $doctorant->nom }} {{ $doctorant->prenom }}</td>
                            </tr>
                            <tr class="odd">
                                <th>Encadrant</th>
                                <td>{{ $encadrant->nom }} {{ $encadrant->prenom }}</td>
                            </tr>
                            <tr class="even">
                                <th>Structure de Recherche</th>
                                <td>
                                    @if ($these->structure_recherche === 'autre')
                                        {{ $these->other_structure }}
                                    @else
                                        {{ $these->structure_recherche }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="odd">
                                <th>Date de Première Inscription</th>
                                <td>{{ \Carbon\Carbon::parse($these->date_premiere_inscription)->locale('fr')->isoFormat('D MMMM YYYY') }}</td>
                            </tr>
                            <tr class="even">
                                <th>Nombre de Publications (Articles)</th>
                                <td>{{ $these->nombre_publications_article }}</td>
                            </tr>
                            <tr class="odd">
                                <th>Nombre de Publications (Conférences)</th>
                                <td>{{ $these->nombre_publications_conference }}</td>
                            </tr>
                            <tr class="even">
                                <th>Nombre de Communications</th>
                                <td>{{ $these->nombre_communications }}</td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

@endsection
<style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: white;
            color: black;
        }

        tr.even th {
            background-color: white; /* Couleur pour les lignes paires */
        }

        tr.odd th {
            background-color: lightblue; /* Couleur pour les lignes impaires */
        }

        tr.even td {
            background-color: white; /* Couleur pour les cellules des lignes paires */
        }

        tr.odd td {
            background-color: lightblue; /* Couleur pour les cellules des lignes impaires */
        }
    </style>
</body>
</html>
