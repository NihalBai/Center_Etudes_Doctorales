<!DOCTYPE html>
<html lang="fr">
<head>
    <base href="/public">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CED</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <style>
        .download-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .button-group {
            display: flex;
            align-items: flex-end;
        }
        .button-group .btn {
            margin-left: 10px;
        }
        .fixed-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .list-group-item .info {
            flex: 1;
            color: #696969;
            font-weight: bold;
        }
        .list-group-item .actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .list-group-item .actions button {
            margin-right: 10px;
        }
        .form-inline .form-group {
            display: flex;
            align-items: flex-end;
            margin-right: 10px;
        }
        .form-inline .form-group:last-child {
            margin-right: 0;
        }
        .form-inline .form-control {
            margin-right: 10px;
        }
        .form-inline .btn {
            margin-left: auto;
        }
    </style>
</head>
<body>
@extends('layouts.layout')

@section('content')
    <main id="main" class="main">
        <div class="container">
            <div class="card mt-4">
                <div class="card-body">
                <h5 class="card-title">Bienvenue!</h5>
                    <!-- Breadcrumb -->
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('demandes.index') }}">Demandes</a></li>
                            <li class="breadcrumb-item active">Voir</li>
                        </ol>
                    </nav>

                    <!-- Messages d'alerte -->
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section principale -->
            <section class="section mt-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center">PV Individuel/ Global</h5>

                                <!-- Sélection du mode de recherche -->
                                <div class="mb-4">
                                    <label for="searchMode" class="form-label">Rechercher Par</label>
                                    <select id="searchMode" class="form-control">
                                        <option value="session">Session de commission</option>  
                                        <option value="etat">État de la demande</option>                                                        
                                    </select>
                                </div>

                                <!-- Formulaire de recherche par état de la demande -->
                                <form id="searchByEtatForm" class="mb-4" style="display: none;" method="POST" action="{{ route('commissions.searchByEtat') }}">
                                    @csrf
                                    <div class="form-row align-items-end">
                                        <div class="col-lg-3 mb-3">
                                            <label for="etat" class="form-label">État de la demande</label>
                                            <select id="etat" name="etat" class="form-select">
                                                <option value="">Toutes</option>
                                                <option value="En attente">En attente</option>
                                                <option value="Refusée">Refusée</option>
                                                <option value="Acceptée">Acceptée</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3 button-group">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Rechercher</button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Formulaire de recherche par session de commission -->
                                <form id="searchBySessionForm" class="mb-4" method="POST" action="{{ route('commissions.searchBySession') }}">
                                    @csrf
                                    <div class="form-row align-items-end">
                                        <div class="col-lg-3 mb-3">
                                            <label for="session" class="form-label">Session de commission</label>
                                            <select id="session" name="session" class="form-select">
                                                <option value="">Toutes</option>
                                                @foreach ($sessions as $session)
                                                    @php
                                                        // Convertir la date en format français
                                                        $date = \Carbon\Carbon::parse($session->date)->locale('fr_FR')->isoFormat('LL');
                                                    @endphp
                                                    <option value="{{ $session->id }}">{{ $date }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3 button-group">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Rechercher</button>
                                        </div>
                                    </div>
                                </form>
                                
                                <!-- Liste des demandes -->
                                <ul id="demandeList" class="list-group">
                                    @foreach ($demandes as $demande)
                                        <li class="list-group-item d-flex justify-content-between align-items-center" data-session="{{ $demande->id_session }}">
                                            <div class="info">{{ $demande->nom }} {{ $demande->prenom }} - {{ $demande->etat }}</div>
                                            <div class="actions">
                                                <button type="button" class="btn btn-danger btn-sm removeDemande" data-id="{{ $demande->id }}"><i class="bi bi-trash"></i> </button>
                                                <div class="form-check form-check-inline">  
                                                    <a href="{{ route('demandes.download', $demande->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-download"></i></a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <!-- Message "Aucun résultat" -->
                                <div id="noResultsMessage" style="display: none; text-align: center; color: grey;">Aucun résultat</div>
                                
                                <div style="margin-bottom: 20px;"></div>

                                <!-- Form for downloading selected demands -->
                                <form id="downloadSelectedForm" action="{{ route('commissions.downloadSelected') }}" method="POST">
                                    @csrf <!-- Ajoutez le jeton CSRF -->

                                    <!-- Ajoutez un champ caché pour stocker les IDs des demandes sélectionnées -->
                                    <input type="hidden" name="demandes" id="selectedDemandes">

                                    <!-- Bouton pour télécharger les demandes sélectionnées -->
                                    
                                </form>




                                <!-- Form for selecting session and generating PV Global -->
                                <form action="{{ route('commissions.generate_PVGloabal') }}" method="POST">
                                    @csrf
                                    <div class="form-row align-items-end">
                                        <div class="col-lg-3 mb-3">
                                            <label for="session_id">Sélectionner une session :</label>
                                            
                                            <select class="form-control" id="session_id" name="session_id" required>
                                                <option value="">Sélectionner une session</option>
                                                @foreach ($sessions as $session)
                                                    @php
                                                        // Convertir la date en format français
                                                        $date = \Carbon\Carbon::parse($session->date)->locale('fr_FR')->isoFormat('LL');
                                                    @endphp
                                                    <option value="{{ $session->id }}">{{ $date }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3 button-group">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-pdf"></i> PV_Global  </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Gestion du changement de mode de recherche
        $('#searchMode').on('change', function() {
            var selectedMode = $(this).val();

            if (selectedMode === 'session') {
                $('#searchBySessionForm').show();
                $('#searchByEtatForm').hide();
            } else if (selectedMode === 'etat') {
                $('#searchBySessionForm').hide();
                $('#searchByEtatForm').show();
            }
        });

        // Vérifiez si la liste est vide et affichez le message "Aucun résultat" si c'est le cas
        function checkIfListIsEmpty() {
            if ($('#demandeList li').length === 0) {
                $('#noResultsMessage').show();
            } else {
                $('#noResultsMessage').hide();
            }
        }

        checkIfListIsEmpty(); // Vérifiez initialement

        // Retirer une demande
        $(document).on('click', '.removeDemande', function() {
            $(this).closest('.list-group-item').remove();
            checkIfListIsEmpty(); // Vérifiez après chaque suppression
        });

        // Gestion de la sélection de toutes les cases à cocher par session
        $('#selectAllSession').on('click', function() {
            $('#demandeList input[type="checkbox"]').prop('checked', true);
        });

        // Gestion de la sélection de toutes les cases à cocher par état
        $('#selectAllEtat').on('click', function() {
            $('#demandeList input[type="checkbox"]').prop('checked', true);
        });

        // Gestion de la soumission du formulaire pour télécharger les demandes sélectionnées
        $('#downloadSelected').on('click', function() {
            let demandeIds = $('.download-checkbox:checked').map(function() {
                return this.value;
            }).get();

            if (demandeIds.length === 0) {
                alert('Veuillez sélectionner au moins une demande à télécharger.');
                return;
            }

            $.post('{{ route("commissions.downloadSelected") }}', { demandes: demandeIds }, function(response) {
                if (response.downloadUrls.length > 0) {
                    response.downloadUrls.forEach(url => {
                        window.open(url); // Open each download URL in a new tab
                    });
                } else {
                    alert('Aucun fichier à télécharger.');
                }
            });
        });
    });
</script>
</body>
</html>
