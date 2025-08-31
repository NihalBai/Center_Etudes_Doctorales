<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commission</title>
    <base href="/public">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #doctorants-table thead {
            display: none;
        }
        .bg-primary th {
            background-color: #cff4fc !important;
            color: white;
        }
    </style>
</head>
<body>
@extends($layoutFile)

@section('content')
<main id="main" class="main">

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Ajouter Commission</h5>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('commissions.index') }}">Commissions</a></li>
                <li class="breadcrumb-item active">Ajouter</li>
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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><center>Nouvelle Commission</center></h5>

                    <form class="row g-3" method="POST" action="{{ route('commissions.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <label for="session" class="form-label">Session de la Commission:</label>
                            <select id="session" name="session_id" class="form-control">
                                <option value="" disabled selected>Voir les sessions</option>
                                @foreach($sessions as $session)
                                    <option value=" " {{ old('session_id') == $session->id ? 'selected' : '' }} disabled >
                                        {{ \Carbon\Carbon::parse($session->date)->locale('fr')->isoFormat('D MMMM YYYY') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="new_session" name="new_session" {{ old('new_session') ? 'checked' : '' }}>
                                <label class="form-check-label" for="new_session">Créer une nouvelle session</label>
                            </div>
                        </div>
                        <div class="col-12 mt-2" id="new-session" style="display: {{ old('new_session') ? 'block' : 'none' }};">
                            <label for="new_session_date" class="form-label">Date de la Session:</label>
                            <input type="date" class="form-control @error('new_session_date') is-invalid @enderror" id="new_session_date" name="new_session_date" value="{{ old('new_session_date') }}">
                            @error('new_session_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-3">
                            <label for="pv_global_signe" class="form-label">PV Global:</label>
                            <input type="file" class="form-control @error('pv_global_signe') is-invalid @enderror" id="pv_global_signe" name="pv_global_signe" required>
                            @error('pv_global_signe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-3">
                            <label for="doctorants" class="form-label">Doctorants:</label>
                            <select id="doctorants" class="form-control">
                                <option value="" selected>Choisir un doctorant</option>
                                @foreach($doctorants as $doctorant)
                                    <option value="{{ $doctorant->id }}" data-cine="{{ $doctorant->CINE }}" data-nom="{{ $doctorant->nom }}" data-prenom="{{ $doctorant->prenom }}">
                                        {{ $doctorant->CINE }} - {{ $doctorant->nom }} {{ $doctorant->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">Doctorants sélectionnés:</label>
                            <table class="table table-bordered" id="doctorants-table">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>CINE</th>
                                        <th>Nom et Prénom</th>
                                        <th>PV Individuel</th>
                                        <th>Avis</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="selected-doctorants">
                                    @if(old('doctorant_ids'))
                                        @foreach(old('doctorant_ids') as $index => $doctorantId)
                                            @php
                                                $doctorant = $doctorants->find($doctorantId);
                                            @endphp
                                            <tr class="doctorant-item">
                                                <input type="hidden" name="doctorant_ids[]" value="{{ $doctorantId }}">
                                                <td>{{ $doctorant->CINE }}</td>
                                                <td>
                                                    <span class="badge badge-primary">{{ $doctorant->nom }} {{ $doctorant->prenom }}</span>
                                                </td>
                                                <td>
                                                    <input type="file" class="form-control @error('individual_pvs.' . $doctorantId) is-invalid @enderror" id="pv_{{ $doctorantId }}" name="individual_pvs[{{ $doctorantId }}]">
                                                    @error('individual_pvs.' . $doctorantId)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <select id="avis_{{ $doctorantId }}" name="avis[{{ $doctorantId }}]" class="form-control @error('avis.' . $doctorantId) is-invalid @enderror" required>
                                                        <option value="Acceptée" {{ old('avis.' . $doctorantId) == 'Acceptée' ? 'selected' : '' }}>Favorable</option>
                                                        <option value="Refusée" {{ old('avis.' . $doctorantId) == 'Refusée' ? 'selected' : '' }}>Défavorable</option>
                                                    </select>
                                                    @error('avis.' . $doctorantId)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-doctorant"><i class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Créer</button>
                            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle new session input
    document.getElementById('new_session').addEventListener('change', function() {
        var newSessionDiv = document.getElementById('new-session');
        var sessionSelect = document.getElementById('session');
        if (this.checked) {
            newSessionDiv.style.display = 'block';
            sessionSelect.selectedIndex = 0;
        } else {
            newSessionDiv.style.display = 'none';
        }
    });

    // Add selected doctorant to the list
    document.getElementById('doctorants').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var doctorantId = selectedOption.value;
        var cine = selectedOption.getAttribute('data-cine');
        var nom = selectedOption.getAttribute('data-nom');
        var prenom = selectedOption.getAttribute('data-prenom');

        if (doctorantId) {
            var tableHeader = document.querySelector('#doctorants-table thead');
            tableHeader.style.display = 'table-header-group';

            var row = document.createElement('tr');
            row.className = 'doctorant-item';
            row.innerHTML = `
                <input type="hidden" name="doctorant_ids[]" value="${doctorantId}">
                <td>${cine}</td>
                <td>
                    <span class="badge badge-primary">${nom} ${prenom}</span>
                </td>
                <td>
                    <input type="file" class="form-control" id="pv_${doctorantId}" name="individual_pvs[${doctorantId}]">
                </td>
                <td>
                    <select id="avis_${doctorantId}" name="avis[${doctorantId}]" class="form-control" required>
                        <option value="Acceptée">Favorable</option>
                        <option value="Refusée">Défavorable</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-doctorant"><i class="bi bi-trash"></i></button>
                </td>
            `;
            document.getElementById('selected-doctorants').appendChild(row);

            // Réinitialiser la sélection
            this.removeChild(selectedOption);

            // Ajouter l'événement de suppression
            row.querySelector('.remove-doctorant').addEventListener('click', function() {
                row.remove();

                // Réajouter l'option au dropdown doctorants
                var option = document.createElement('option');
                option.value = doctorantId;
                option.text = `${cine} - ${nom} ${prenom}`;
                option.setAttribute('data-cine', cine);
                option.setAttribute('data-nom', nom);
                option.setAttribute('data-prenom', prenom);
                document.getElementById('doctorants').appendChild(option);

                // Masquer l'en-tête du tableau si aucun doctorant n'est sélectionné
                if (document.querySelectorAll('#selected-doctorants tr').length === 0) {
                    tableHeader.style.display = 'none';
                }
            });
        }
    });
});
</script>

@endsection
</body>
</html>
