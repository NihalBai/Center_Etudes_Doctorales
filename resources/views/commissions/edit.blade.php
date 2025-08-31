<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/public">
</head>
<body>
    

@extends($layoutFile)

@section('content')
<main id="main" class="main">

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Modifier Commission</h5>

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
                        <h5 class="card-title"><center>Modifier Commission</center></h5>

                        <form class="row g-3" method="POST" action="{{ route('commissions.update', ['commission'=>$commission]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Date de Session -->
                            <div class="col-12 row">
                                <div class="col-12">
                                    <label for="current_session" class="form-label">Session Actuelle:</label>
                                    <input type="text" class="form-control" id="current_session" value="{{ \Carbon\Carbon::parse($commission->date)->locale('fr')->isoFormat('D MMMM YYYY') }}" readOnly>
                                </div>
                
                            </div>

                            <!-- Créer une nouvelle session -->
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="new_session" name="new_session">
                                    <label class="form-check-label" for="new_session">
                                        Modifier Session
                                    </label>
                                </div>
                            </div>

                            <!-- Champ pour la nouvelle session -->
                            <div class="col-12 mt-3" id="new_session_fields" style="display: none;">
                                <div class="col-md-6">
                                    <label for="new_session_date" class="form-label">Date de la Nouvelle Session:</label>
                                    <input type="date" class="form-control" id="new_session_date" name="new_session_date">
                                </div>
                            </div>

                            <!-- Télécharger le PV global actuel -->
                            <div class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="current_pv_global_signe" class="form-label">PV Global Actuel:</label>
                                        <div>
                                        @php
        // Récupérer l'extension du fichier
        $extension = pathinfo($commission->pv_global_signe, PATHINFO_EXTENSION);
    @endphp

    <a href="{{ asset('storage/' . $commission->pv_global_signe) }}"
       class="btn btn-primary"
       download="PV_Global_Final {{ \Carbon\Carbon::parse($commission->date)->locale('fr')->isoFormat('D MMMM YYYY') }}.{{ $extension }}">
       <i class="bi bi-download"></i>Télécharger
    </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pv_global_signe" class="form-label">Nouveau PV Global:</label>
                                        <input type="file" class="form-control" id="pv_global_signe" name="pv_global_signe">
                                    </div>
                                </div>
                            </div>                           <div style="margin-bottom: 20px;"></div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                <button type="reset" class="btn btn-secondary">Annuler</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Gérer l'affichage du champ pour une nouvelle session en fonction de la case à cocher
        const createNewSessionCheckbox = document.getElementById('new_session');
        const newSessionFields = document.getElementById('new_session_fields');

        createNewSessionCheckbox.addEventListener('change', function() {
            if (this.checked) {
                newSessionFields.style.display = 'block';
            } else {
                newSessionFields.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>
