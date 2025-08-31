<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Rapporteur</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        <form class="row g-3" id="inscription-form" method="POST" action="{{ route('rapporteurs.storeRapporteur') }}" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <label for="name" class="form-label">Nom et Prénom:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nom Prénom" required>
                </div>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Adresse e-mail :</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="col-md-6">
                <label for="tele" class="form-label">Numéro de téléphone :</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                    <input type="text" class="form-control" id="tele" name="tele" required>
                </div>
            </div>
            <div class="col-md-6">
                <label for="sex" class="form-label">Sexe :</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-gender-male" id="gender-icon"></i></span>
                    <input type="text" class="form-control" id="sex" name="sex" required>
                </div>
            </div>

            <div class="col-md-12">
                <label for="members" class="form-label">Sélectionner un membre:</label>
                <div class="input-group mb-3">
                    <select class="form-select form-control" id="members" name="members">
                        <option value="">Choisir un membre...</option>
                        @foreach($membres as $membre)
                            <option value="{{ $membre->id }}"
                                    data-nom="{{ $membre->nom }} {{ $membre->prenom }}"
                                    data-email="{{ $membre->email }}"
                                    data-tele="{{ $membre->tele }}"
                                    data-sex="{{ $membre->sex }}">
                                {{ $membre->nom }} {{ $membre->prenom }}
                            </option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="addMember">Ajouter</button>
                        <a href="{{ route('membres.create') }}" class="btn btn-outline-secondary">Nouveau</a>
                    </div>
                </div>
                <ul class="list-group" id="memberList">
                    <!-- Selected members will appear here -->
                </ul>
            </div>
            <div style="margin-bottom: 20px;"></div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Valider</button>
                <button type="reset" class="btn btn-secondary">Réinitialiser</button>
            </div>
        </form>
        </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</section>
</main>
@endsection
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var membersSelect = document.getElementById('members');
            var nameInput = document.getElementById('name');
            var emailInput = document.getElementById('email');
            var teleInput = document.getElementById('tele');
            var sexInput = document.getElementById('sex');
            var genderIcon = document.getElementById('gender-icon');

            membersSelect.addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                nameInput.value = selectedOption.getAttribute('data-nom');
                emailInput.value = selectedOption.getAttribute('data-email');
                teleInput.value = selectedOption.getAttribute('data-tele');
                sexInput.value = (selectedOption.getAttribute('data-sex') === 'male') ? 'Homme' : 'Femme';

                genderIcon.classList.remove('bi-gender-male', 'bi-gender-female');
                genderIcon.classList.add('bi-gender-' + (selectedOption.getAttribute('data-sex') === 'male' ? 'male' : 'female'));

                selectedOption.disabled = true;
            });

            var addMemberBtn = document.getElementById('addMember');
            var memberList = document.getElementById('memberList');

            addMemberBtn.addEventListener('click', function() {
                var selectedMember = membersSelect.options[membersSelect.selectedIndex];
                var memberId = selectedMember.value;
                var memberText = selectedMember.textContent.trim();

                if (memberText) {
                    var listItem = document.createElement('li');
                    listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                    listItem.textContent = memberText;

                    var hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selectedMembers[]';
                    hiddenInput.value = memberId;
                    listItem.appendChild(hiddenInput);

                    var removeBtn = document.createElement('button');
                    removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'remove-member');
                    removeBtn.textContent = 'Retirer';
                    listItem.appendChild(removeBtn);

                    memberList.appendChild(listItem);
                }
            });

            memberList.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-member')) {
                    var listItem = event.target.closest('li');
                    var memberId = listItem.querySelector('input[name="selectedMembers[]"]').value;

                    var optionToEnable = membersSelect.querySelector('option[value="' + memberId + '"]');
                    if (optionToEnable) {
                        optionToEnable.disabled = false;
                    }

                    listItem.remove();
                }
            });
        });
    </script>
</body>
</html>
