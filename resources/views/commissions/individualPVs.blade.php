<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/public">
    <title>PV individuels pour la session</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>

@extends($layoutFile)

@section('content')
<main id="main" class="main">

<div class="card">
    <div class="card-body">
        <h5 class="card-title"></h5>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('commissions.index') }}">Commissions</a></li>
                <li class="breadcrumb-item active">Modifier</li>
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
                    <h5 class="card-title"><center>PV individuels pour la session {{ \Carbon\Carbon::parse($session->date)->locale('fr')->isoFormat('D MMMM YYYY') }}</center></h5>
                    
                    <div class="container mt-5">
                        <h1 class="mb-4"></h1>

                        <div id="alert-container"></div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom du Doctorant</th>
                                    <th>Avis</th>
                                    <th>PV</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doctorants as $doctorant)
                                    @php
                                        $pv = $pvs->firstWhere('doctorant_id', $doctorant->id);
                                        $demande = $demandes->firstWhere('id_these', $doctorant->these->id);
                                    @endphp
                                    <tr data-doctorant-id="{{ $doctorant->id }}">
                                        <td>{{ $doctorant->prenom }} {{ $doctorant->nom }}</td>
                                        <td>
                                            <span class="avis-label">
                                                @if ($demande && $demande->etat == 'Acceptée')
                                                    Favorable
                                                @elseif ($demande && $demande->etat == 'Refusée')
                                                    Défavorable
                                                @elseif ($demande)
                                                    {{ $demande->etat }}
                                                @else
                                                    Aucune demande
                                                @endif
                                            </span>

                                            <select class="form-control d-none avis-select">
                                                <option value="Acceptée" {{ ($demande && $demande->etat == 'Acceptée') ? 'selected' : '' }}>Favorable</option>
                                                <option value="Refusée" {{ ($demande && $demande->etat == 'Refusée') ? 'selected' : '' }}>Défavorable</option>
                                            </select>
                                        </td>
                                        <td>
                                            @if ($pv)
                                                <a href="{{ asset('storage/' . $pv->path) }}" target="_blank" class="pv-link">Voir le PV</a>
                                                <input type="file" class="form-control d-none pv-file">
                                            @else
                                                Aucun PV disponible
                                                <input type="file" class="form-control d-none pv-file">
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary edit-global"><i class="fas fa-edit"></i> Éditer</button>
                                            @if ($pv)
                                                @php
                                                    $extension = pathinfo($pv->path, PATHINFO_EXTENSION);
                                                @endphp
                                                <a href="{{ asset('storage/' . $pv->path) }}" class="btn btn-primary" download="{{ $pv->type }}_{{ $doctorant->nom }} {{ $doctorant->prenom }}.{{ $extension }}">
                                                    <i class="bi bi-download"></i> Télécharger
                                                </a>
                                            @endif
                                            <button class="btn btn-success save-pv d-none"><i class="fas fa-save"></i> Enregistrer</button>
                                            <button class="btn btn-danger cancel-pv d-none"><i class="fas fa-times"></i> Annuler</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <script>
document.addEventListener('DOMContentLoaded', function() {
    const alertContainer = document.getElementById('alert-container');

    function showAlert(message, type) {
        alertContainer.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                        ${message}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>`;
    }

    document.querySelectorAll('.edit-global').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('tbody tr').forEach(row => {
                const avisLabel = row.querySelector('.avis-label');
                const avisSelect = row.querySelector('.avis-select');
                const pvLink = row.querySelector('.pv-link');
                const pvFile = row.querySelector('.pv-file');

                avisLabel.classList.add('d-none');
                avisSelect.classList.remove('d-none');
                if (pvLink) {
                    pvLink.classList.add('d-none');
                }
                pvFile.classList.remove('d-none');
                button.classList.add('d-none');
                row.querySelector('.save-pv').classList.remove('d-none');
                row.querySelector('.cancel-pv').classList.remove('d-none');
            });
        });
    });

    document.querySelectorAll('.cancel-pv').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('tbody tr').forEach(row => {
                const avisLabel = row.querySelector('.avis-label');
                const avisSelect = row.querySelector('.avis-select');
                const pvLink = row.querySelector('.pv-link');
                const pvFile = row.querySelector('.pv-file');

                avisLabel.classList.remove('d-none');
                avisSelect.classList.add('d-none');
                if (pvLink) {
                    pvLink.classList.remove('d-none');
                }
                pvFile.classList.add('d-none');
                document.querySelector('.edit-global').classList.remove('d-none');
                row.querySelector('.save-pv').classList.add('d-none');
                row.querySelector('.cancel-pv').classList.add('d-none');
            });
        });
    });

    document.querySelectorAll('.save-pv').forEach(button => {
        button.addEventListener('click', function() {
            const row = button.closest('tr');
            const doctorantId = row.getAttribute('data-doctorant-id');
            const avis = row.querySelector('.avis-select').value;
            const pvFile = row.querySelector('.pv-file').files[0];

            const formData = new FormData();
            formData.append('doctorant_id', doctorantId);
            formData.append('avis', avis);
            if (pvFile) {
                formData.append('pv_file', pvFile);
            }

            const sessionId = "{{ $session->id }}";

            fetch(`/commissions/${sessionId}/doctorants/${doctorantId}/updatepvindividuel`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Modifications enregistrées avec succès', 'success');
                    row.querySelector('.avis-label').textContent = avis === 'Acceptée' ? 'Favorable' : 'Défavorable';
                    row.querySelector('.avis-label').classList.remove('d-none');
                    row.querySelector('.avis-select').classList.add('d-none');
                    if (pvFile) {
                        const newLink = document.createElement('a');
                        newLink.href = URL.createObjectURL(pvFile);
                        newLink.target = '_blank';
                        newLink.textContent = "Voir le PV";
                        newLink.classList.add('pv-link');
                        row.querySelector('.pv-link').replaceWith(newLink);
                        row.querySelector('.pv-file').classList.add('d-none');
                    }
                    document.querySelector('.edit-global').classList.remove('d-none');
                    row.querySelector('.save-pv').classList.add('d-none');
                    row.querySelector('.cancel-pv').classList.add('d-none');
                } else {
                    showAlert('Erreur lors de l\'enregistrement des modifications', 'danger');
                    console.error('Erreur serveur :', data.message);
                }
            })
            .catch(error => {
                showAlert('Erreur lors de l\'enregistrement des modifications', 'danger');
                console.error('Erreur fetch :', error);
            });
        });
    });
});
</script>

</div>
</div>
</div>
</div>
</section>

@endsection

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

