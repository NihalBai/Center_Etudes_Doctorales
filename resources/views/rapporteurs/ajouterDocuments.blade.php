<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CED</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Ensure Bootstrap JS is included -->
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
                    <li class="breadcrumb-item"><a href="{{ route('rapporteurs.index') }}">Rapporteurs</a></li>
                    <li class="breadcrumb-item active">Document</li>
                </ol>
            </nav>
            @if(session('success'))
                <div class="col-12">
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Documents</h5>
                        <form class="row g-3" id="documents-form" method="POST" action="{{ route('rapporteurs.joindreDocuments') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Madame/Monsieur :</label>
                            </div>

                            <div class="col-12">
                                <ul class="list-group" id="rapporteurList">
                                    @foreach($rapporteurs as $rapporteur)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $rapporteur->name }}
                                            <button type="button" class="btn btn-secondary btn-sm removeRapporteur" data-id="{{ $rapporteur->id }}">
                                                <i class="bi bi-x"></i>
                                            </button>
                                            <input type="hidden" name="rapporteur_ids[]" value="{{ $rapporteur->id }}">
                                        </li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addRapporteurModal">
                                    Ajouter Rapporteur
                                </button>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label">Vont valider la Thèse :</label>
                                <input type="text" class="form-control border border-secondary bg-transparent" id="titreOriginal" name="titreOriginal" value="{{ $these->titreOriginal }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Soutenue Par :</label>
                                <input type="text" class="form-control border border-secondary bg-transparent" value="{{ $these->nom }} {{ $these->prenom }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="tele" class="form-label">Avec CINE :</label>
                                <input type="text" class="form-control border border-secondary bg-transparent" value="{{ $these->CINE }}" readonly>
                            </div>

                            <!-- Ajouter les champs de formulaire pour capturer id_doctorant et id_these -->
                            <input type="hidden" name="id_doctorant" value="{{ $these->id_doctorant }}">
                            <input type="hidden" name="id_these" value="{{ $these->id }}">

                            <div class="text-center mt-3 col-12">
                                <button type="submit" class="btn btn-primary">Joindre</button>
                                <button type="reset" class="btn btn-secondary">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal" id="addRapporteurModal" tabindex="-1" aria-labelledby="addRapporteurModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRapporteurModalLabel">Ajouter Rapporteur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addRapporteurForm">
                        <div class="mb-3">
                            <label for="rapporteurSelect" class="form-label">Sélectionner Rapporteur</label>
                            <select multiple class="form-control" id="rapporteurSelect">
                                @foreach($availableRapporteurs as $availableRapporteur)
                                    <option value="{{ $availableRapporteur->id }}">{{ $availableRapporteur->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" id="addSelectedRapporteurs">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .list-group-item {
            background-color: #e7f3ff;
            color: #000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #007bff;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 10px 15px;
        }
        .select-all-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .select-all-container button {
            color: #007bff;
            background: none;
            border: none;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.removeRapporteur', function() {
                $(this).closest('.list-group-item').remove();
            });

            $('#addSelectedRapporteurs').on('click', function() {
                var selectedRapporteurs = $('#rapporteurSelect').val();

                if (selectedRapporteurs && selectedRapporteurs.length > 0) {
                    selectedRapporteurs.forEach(function(rapporteurId) {
                        var rapporteurNom = $('#rapporteurSelect option[value="' + rapporteurId + '"]').text();
                        var listItem = '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                            rapporteurNom +
                            '<button type="button" class="btn btn-secondary btn-sm removeRapporteur" data-id="' + rapporteurId + '">' +
                            '<i class="bi bi-x"></i>' +
                            '</button>' +
                            '</li>';
                        $('#rapporteurList').append(listItem);

                        // Ajouter un champ caché pour chaque rapporteur sélectionné
                        $('#documents-form').append('<input type="hidden" name="rapporteur_ids[]" value="' + rapporteurId + '">');
                    });

                    $('#addRapporteurModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            });

            // Prevent modal backdrop from blocking scrolling
            $('#addRapporteurModal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });
    </script>
</main>
@endsection
</body>
</html>
