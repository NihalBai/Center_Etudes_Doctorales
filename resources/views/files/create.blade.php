<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <base href="/public">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .document-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            margin-bottom: 10px; /* Augmenté pour une meilleure séparation entre les éléments */
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
            position: relative; /* Position relative pour le positionnement absolu du bouton */
        }
        .document-name {
            color: #007bff;
            font-weight: bold;
            padding: 5px 10px;
            background-color: #d1ecf1;
            border-radius: 5px;
            flex: 1;
        }
        .file-name {
            color: #007bff;
            flex: 2;
            padding-left: 10px;
            cursor: pointer;
        }
        .btn-remove {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            position: absolute;
            top: 5px; /* Positionné au coin supérieur droit */
            right: 5px; /* Positionné au coin supérieur droit */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const addButton = document.getElementById('addDocument');
            const documentTypeSelect = document.getElementById('documentType');
            const documentList = document.getElementById('documentList');
            
            addButton.addEventListener('click', (e) => {
                e.preventDefault();
                
                const documentTypeOption = documentTypeSelect.options[documentTypeSelect.selectedIndex];
                if (documentTypeOption.disabled || documentTypeSelect.selectedIndex === 0) {
                    return; // Exit if the option is disabled or no option is selected
                }
                
                const documentType = documentTypeOption.value;
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.name = `documents[${documentType}]`;
                fileInput.required = true;
                fileInput.style.display = 'none'; // Hide the file input
                
                const listItem = document.createElement('li');
                listItem.className = 'document-item list-group-item';

                const documentName = document.createElement('span');
                documentName.className = 'document-name';
                documentName.innerText = documentTypeOption.text;

                const fileName = document.createElement('span');
                fileName.className = 'file-name';
                fileName.innerText = 'Aucun fichier sélectionné';

                const removeButton = document.createElement('button');
                removeButton.className = 'btn btn-remove btn-sm';
                removeButton.type = 'button';
                removeButton.innerText = 'Supprimer';
                removeButton.addEventListener('click', () => {
                    documentList.removeChild(listItem);
                    documentTypeOption.disabled = false;
                });

                listItem.appendChild(documentName);
                listItem.appendChild(fileInput);
                listItem.appendChild(fileName);
                listItem.appendChild(removeButton);
                documentList.appendChild(listItem);

                documentTypeOption.disabled = true;
                documentTypeSelect.selectedIndex = 0;

                fileInput.addEventListener('change', () => {
                    if (fileInput.files.length > 0) {
                        fileName.innerText = fileInput.files[0].name;
                    } else {
                        fileName.innerText = 'Aucun fichier sélectionné';
                    }
                });

                // Trigger file input click when the file name span is clicked
                fileName.addEventListener('click', () => {
                    fileInput.click();
                });
            });
        });
    </script>
</head>
<body>


@extends('layouts.layout')

@section('content')
<main id="main" class="main">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Bienvenue!</h5>

                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('doctorants.index') }}">Doctorants</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </nav>

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
        <!-- End Page Title -->

        <section class="section mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ajouter les documents</h5>
                            <form class="row g-3" id="create-demande-form" method="post" action="{{ route('doctorants.storeFile') }}" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Champ caché pour l'ID du doctorant -->
                                <input type="hidden" name="doctorant_id" value="{{ $doctorant->id }}">
                                
                                <!-- Affichage du nom et prénom du doctorant -->
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Nom et prénom du doctorant</label>
                                    <input type="text" class="form-control border border-secondary bg-transparent"  value="{{ $doctorant->nom }} {{ $doctorant->prenom }}" readonly>
                                </div>
                                 
                                <!-- Affichage du CINE sans l'envoyer à travers la requête -->
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">CINE</label>
                                    <input type="text" class="form-control border border-secondary bg-transparent" value="{{ $doctorant->CINE }}" readonly>
                                </div>

                                <div class="col-lg-12 mb-3 d-flex align-items-end">
                                    <div class="flex-grow-1">
                                    <label for="documentType" class="form-label">Type de document</label>
                                    <select class="form-select" id="documentType">
                                        <option value="" disabled selected>Choisir le type de document</option>
                                        <option value="rapport_these">Rapport de Thèse&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </option>
                                        <option value="cv">CV &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </option>
                                        <option value="dossier_scientifique">Dossier Scientifique&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                                        <option value="demande_soutenance">Demande de Soutenance</option>
                                        <option value="rapport_encadrant">Rapport de l'Encadrant&nbsp;&nbsp;&nbsp;</option>
                                    </select>
                                </div>

                                <!-- Ajouter un fichier -->
                                <div class="ml-3">
                                    <label for="addDocument" class="form-label">&nbsp;</label>
                                    <button id="addDocument" class="btn btn-primary">Ajouter Document</button>
                                </div>
                            </div>
                                
                                <!-- Liste des fichiers ajoutés -->
                                <div class="col-12 mb-3">
                                    <ul id="documentList" class="list-group"></ul>
                                </div>
                                
                                <!-- Boutons de soumission et réinitialisation -->
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary">Ajouter</button>
                                    <button type="reset" class="btn btn-secondary">Réinitialiser</button>
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
</body>
</html>
