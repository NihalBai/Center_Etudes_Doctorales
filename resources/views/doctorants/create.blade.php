<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Doctorant</title>
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
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="{{route('doctorants.index')}}">Doctorants</a></li>
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
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Formulaire de Doctorant</h5>
                    <form class="row g-3" id="create-demande-form" method="post" action="{{ route('doctorants.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom">
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom">
                        </div>
                        <div class="col-md-6">
                            <label for="CINE" class="form-label">CINE</label>
                            <input type="text" class="form-control" id="CINE" name="CINE">
                        </div>
                        <div class="col-md-6">
                            <label for="sex" class="form-label">Sexe</label>
                            <select class="form-select" id="sex" name="sex">
                                <option value="male">Homme</option>
                                <option value="female">Femme</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="date_de_naissance" class="form-label">Date de Naissance</label>
                            <input type="date" class="form-control" id="date_de_naissance" name="date_de_naissance">
                        </div>      
                        <div class="col-md-6">
                             <label for="lieu" class="form-label">Lieu de Naissance</label>
                             <input type="text" class="form-control" id="lieu" name="lieu">
                        </div>
                        <div class="col-md-6">
                            <label for="id_encadrant" class="form-label">Encadrant</label>
                            <select class="form-select" id="id_encadrant" name="id_encadrant">
                                @foreach($encadrants as $encadrant)
                                    <option value="{{ $encadrant->id }}">{{ $encadrant->nom }} {{ $encadrant->prenom }} - {{ $encadrant->grade }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Adresse e-mail :</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tele" class="form-label">Numéro de téléphone :</label>
                            <input type="text" class="form-control" id="tele" name="tele" required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="photo_path" class="form-label">Ajouter la Photo</label>
                            <input type="file" class="form-control" id="photo_path" name="photo_path">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="discipline" class="form-label">Discipline :</label>
                            <input type="text" class="form-control" id="discipline" name="discipline" required>
                        </div>

                        <div class="col-md-6">
                            <label for="date_premiere_inscription" class="form-label">Date de Première Inscription :</label>
                            <input type="date" class="form-control" id="date_premiere_inscription" name="date_premiere_inscription" value="{{ date('Y') }}-{{ date('Y') + 1 }}" required>
                        </div>

                        <h5 class="card-title">Scolarité de Doctorant</h5>
                        <div class="col-lg-12 mb-3 text-end">
                            <button type="button" id="btnAddScolarite" class="btn btn-primary">Ajouter Parcours</button>
                        </div>
                        <div id="formScolarite" class="col-lg-12 mb-3 d-none">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Formulaire Scolarité</h5>
                                    <div class="row g-3">
                                        <div class="col-md-4 mb-3">
                                            <label for="niveau" class="form-label">Niveau</label>
                                            <select class="form-select" id="niveau" name="niveau">
                                                <option value="bac">Baccalauréat</option>
                                                <option value="deust">DEUST</option>
                                                <option value="deug">DEUG</option>
                                                <option value="master">Master</option>
                                                <option value="licence">Licence</option>
                                                <option value="autre">Autre</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3" id="autreNiveau" style="display: none;">
                                            <label for="autre_niveau" class="form-label">Autre Niveau</label>
                                            <input type="text" class="form-control" id="autre_niveau" name="autre_niveau" placeholder="Entrez le niveau">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="specialite" class="form-label">Spécialité</label>
                                            <input type="text" class="form-control" id="specialite" name="specialite" required>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="annee" class="form-label">Année</label>
                                            <input type="number" class="form-control" id="annee" name="annee" required>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="mois" class="form-label">Mois</label>
                                            <select class="form-control" id="mois" name="mois" required>
                                                @foreach($months as $month)
                                                    <option value="{{ $month }}" {{ $month == 'Juillet' ? 'selected' : '' }}>{{ $month }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="mention" class="form-label">Mention</label>
                                            <select class="form-select" id="mention" name="mention">
                                                <option value="passable">Passable</option>
                                                <option value="a_bien">Assez Bien</option>
                                                <option value="bien">Bien</option>
                                                <option value="tr_bien">Très Bien</option>
                                                <option value="excellent">Excellent</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button type="button" id="btnSaveScolarite" class="btn btn-success">Sauvegarder</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <h5 class="card-title">Parcours Scolaire/Universitaire</h5>
                            <ul id="scolariteList" class="list-group"></ul>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary" id="submitBtn">Ajouter</button>
                            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const niveauSelect = document.getElementById('niveau');
        const autreNiveauInput = document.getElementById('autreNiveau');
        const formScolarite = document.getElementById('formScolarite');

        niveauSelect.addEventListener('change', () => {
            if (niveauSelect.value === 'autre') {
                autreNiveauInput.style.display = 'block';
            } else {
                autreNiveauInput.style.display = 'none';
                autreNiveauInput.querySelector('input').value = '';
            }
        });

        const btnAddScolarite = document.getElementById('btnAddScolarite');
        const btnSaveScolarite = document.getElementById('btnSaveScolarite');
        const scolariteList = document.getElementById('scolariteList');
        const submitBtn = document.getElementById('submitBtn');

        btnAddScolarite.addEventListener('click', (e) => {
            e.preventDefault();
            formScolarite.classList.remove('d-none');
        });

        btnSaveScolarite.addEventListener('click', (e) => {
            e.preventDefault();

            let niveau = document.getElementById('niveau').value;
            const autreNiveau = document.getElementById('autre_niveau').value;
            if (niveau === 'autre' && autreNiveau.trim() !== '') {
                niveau = autreNiveau;
            }

            const specialite = document.getElementById('specialite').value;
            const annee = document.getElementById('annee').value;
            const mois = document.getElementById('mois').value;
            const mention = document.getElementById('mention').value;

            if (niveau && specialite && annee && mois && mention) {
                const scolarite = {
                    niveau: niveau,
                    specialite: specialite,
                    annee: annee,
                    mois: mois,
                    mention: mention
                };

                addScolariteToList(scolarite);
                resetForm();
            } else {
                alert('Veuillez remplir tous les champs de la scolarité.');
            }
        });

        function addScolariteToList(scolarite) {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item';

            const hiddenFields = `
                <input type="hidden" name="scolarites[${scolariteList.children.length}][niveau]" value="${scolarite.niveau}">
                <input type="hidden" name="scolarites[${scolariteList.children.length}][specialite]" value="${scolarite.specialite}">
                <input type="hidden" name="scolarites[${scolariteList.children.length}][annee]" value="${scolarite.annee}">
                <input type="hidden" name="scolarites[${scolariteList.children.length}][mois]" value="${scolarite.mois}">
                <input type="hidden" name="scolarites[${scolariteList.children.length}][mention]" value="${scolarite.mention}">
            `;

            listItem.innerHTML = `
                ${hiddenFields}
                <strong>Niveau:</strong> ${scolarite.niveau}<br>
                <strong>Spécialité:</strong> ${scolarite.specialite}<br>
                <strong>Année:</strong> ${scolarite.annee}<br>
                <strong>Mois:</strong> ${scolarite.mois}<br>
                <strong>Mention:</strong> ${scolarite.mention}<br>
                <button type="button" class="btn btn-danger btn-sm mt-2">Supprimer</button>
            `;

            const removeBtn = listItem.querySelector('button');
            removeBtn.addEventListener('click', () => {
                scolariteList.removeChild(listItem);
                updateScolariteNames();
            });

            scolariteList.appendChild(listItem);
        }

        function resetForm() {
            document.getElementById('niveau').value = '';
            document.getElementById('autre_niveau').value = '';
            document.getElementById('specialite').value = '';
            document.getElementById('annee').value = '';
            document.getElementById('mois').value = '';
            document.getElementById('mention').value = '';
            formScolarite.classList.add('d-none');
            autreNiveauInput.style.display = 'none';
        }

        function updateScolariteNames() {
            const scolariteItems = document.querySelectorAll('#scolariteList .list-group-item');
            scolariteItems.forEach((item, index) => {
                item.querySelectorAll('input').forEach(input => {
                    const name = input.getAttribute('name');
                    const updatedName = name.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', updatedName);
                });
            });
        }

        submitBtn.addEventListener('click', () => {
            document.getElementById('create-demande-form').submit();
        });
    });
</script>



<style>
    body {
        font-family: Arial, sans-serif;
    }
    .card-title {
        margin-bottom: 20px;
    }
    .form-label {
        font-weight: normal;
    }
    .form-control {
        margin-bottom: 10px;
    }
    .form-select {
        margin-bottom: 10px;
    }
    .list-group-item {
        margin-bottom: 5px;
    }
    .badge {
        font-size: 0.9rem;
    }
</style>
</body>
</html>
