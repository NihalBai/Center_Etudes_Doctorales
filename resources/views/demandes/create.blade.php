<!-- resources/views/demandes/create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Demande</title>
    <base href="/public">
</head>
<body>
@extends($layoutFile)

@section('content')
<main id="main" class="main">

<div class="card">
            <div class="card-body" >
              <h5 class="card-title">Ajouter Demande</h5>

              <nav>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="bi bi-house-door"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('demandes.index')}}">Demandes</a></li>
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
          <!-- End Page Title -->

    <section class="section">
      <div class="row">
       
    



<!-- formulaire de demande -->
<div class="col-lg-10">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><center>Demande de Soutenance</center></h5>

            <form class="row g-3" id="create-demande-form" method="POST" action="{{ route('demandes.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="col-md-8">
                    <label for="RNES" class="form-label">RNES:</label>
                    <input type="text" class="form-control" id="RNES" name="RNES" required>
                </div>
                <div class="col-md-4">
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="col-12">
                    <label for="etat" class="form-label">État:</label>
                    <select id="etat" name="etat" class="form-control" required>
                        <option value="Refusée">Refusée</option>
                        <option value="Acceptée">Acceptée</option>
                        <option value="En attente" selected>En attente</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="id_these" class="form-label">Thèse:</label>
                    <input type="text" class="form-control" id="id_these" name="id_these" value="{{ $these->titreOriginal }}" readonly>
                </div>
                <div class="col-md-6">
                    <label for="id_doctorant" class="form-label">Doctorant:</label>
                    <input type="text" class="form-control" id="id_doctorant" name="id_doctorant" value="{{ $these->doctorant->nom }} {{ $these->doctorant->prenom }}" readonly>
                </div>

                <div class="col-12" id="existing-session">
                    <label for="demande_session_id" class="form-label">Session:</label>
                    <select id="demande_session_id" name="demande_session_id" class="form-control">
                        <option value="" selected>Choisir une session</option>
                        @if ($lastSession)
                        <option value="{{ $lastSession->id }}">{{ \Carbon\Carbon::parse($lastSession->date)->locale('fr')->isoFormat('D MMMM YYYY') }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-check-label" for="new_session">
                        <input class="form-check-input" type="checkbox" id="new_session" name="new_session"> Créer une nouvelle session
                    </label>
                </div>
                <div class="col-12" id="new-session" style="display: none;">
                    <label for="new_session_date" class="form-label">Date de la Session:</label>
                    <input type="date" class="form-control" id="new_session_date" name="new_session_date">
                </div>
                <div style="margin-bottom: 20px;"></div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Créer</button>
                    <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Écouter le changement de valeur du checkbox "Créer une nouvelle session"
        document.getElementById('new_session').addEventListener('change', function() {
            var newSessionDiv = document.getElementById('new-session');
            var existingSessionDiv = document.getElementById('existing-session');
            var sessionSelect = document.getElementById('demande_session_id');

            if (this.checked) {
                // Afficher le champ de saisie de la nouvelle session et cacher la liste déroulante
                newSessionDiv.style.display = 'block';
                existingSessionDiv.style.display = 'none';
                // Réinitialiser la sélection de la session existante
                sessionSelect.selectedIndex = 0;
            } else {
                // Afficher la liste déroulante et cacher le champ de saisie de la nouvelle session
                newSessionDiv.style.display = 'none';
                existingSessionDiv.style.display = 'block';
                // Effacer la valeur du champ de saisie de la nouvelle session
                document.getElementById('new_session_date').value = '';
            }
        });
    });
</script>


          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->


   
@endsection
</body>
</html>
