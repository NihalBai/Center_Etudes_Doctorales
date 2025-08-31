@extends($layoutFile)

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Membres</li>
                <li class="breadcrumb-item">Ajouter Membre</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Nouveau Membre</h5>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- Multi Columns Form  -->
                        <form class="row g-3" method="POST" action="{{ route('membres.store') }}">
                            @csrf
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="sex" class="form-label">Sexe</label>
                                <select class="form-control" id="sex" name="sex" required>
                                    <option value="male">Homme</option>
                                    <option value="female">Femme</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tele" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="tele" name="tele" required>
                            </div>
                            <div class="col-md-6">
                                <label for="grade" class="form-label">Grade</label>
                                <select class="form-select" id="grade" name="grade" required>
                                    <option value="">Choisir un grade</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade }}">{{ $grade }}</option>
                                    @endforeach
                                    <option value="autre">Autre</option>
                                </select>
                                <div id="otherGrade" style="display: none;">
                                    <label for="newgrade" class="form-label">Nouveau Grade</label>
                                    <input type="text" class="form-control" id="newgrade" name="newgrade">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="universite" class="form-label">Université</label>
                                <select id="universite" class="form-select" name="universite">
                                    <option value="">Choisir une université</option>
                                    @foreach($universites as $universite)
                                        <option value="{{ $universite->id }}">{{ $universite->nom }}</option>
                                    @endforeach
                                    <option value="autre">Autre</option>
                                </select>

                                <div id="otherUniversite" style="display: none;">
                                    <label for="newuniversite" class="form-label">Nouvelle Université</label>
                                    <input type="text" class="form-control" id="newuniversite" name="newuniversite">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="faculte" class="form-label">Faculté</label>
                                <select id="faculte" class="form-select" name="faculte" >
                                    <option value="">Choisir une faculté</option>
                                    @foreach($facultes as $faculte)
                                        <option value="{{ $faculte->id }}" data-universite="{{ $faculte->id_universite }}">{{ $faculte->nom }}</option>
                                    @endforeach
                                    <option value="autre">Autre</option>
                                </select>
                                <div id="otherFaculte" style="display: none;">
                                    <label for="newfaculte" class="form-label">Nouvelle Faculté</label>
                                    <input type="text" class="form-control" id="newfaculte" name="newfaculte">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="ville" class="form-label">Ville</label>
                                <select id="ville" class="form-select" name="ville" required>
                                    <option value="">Choisir une ville</option>
                                    @foreach($villes as $ville)
                                        <option value="{{ $ville }}">{{ $ville }}</option>
                                    @endforeach
                                    <option value="autre">Autre</option>
                                </select>
                                <div id="otherVille" style="display: none;">
                                    <label for="newville" class="form-label">Nouvelle Ville</label>
                                    <input type="text" class="form-control" id="newville" name="newville">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="autre" class="form-label">Autre</label>
                                <input type="text" class="form-control" id="autre" name="autre">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Ajouter</button>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="reset" class="btn btn-secondary">Annuler</button>
                            </div>
                        </form>
                        <!-- End Multi Columns Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- <script>
$(document).ready(function() {
    $('#universite').change(function() {
        var selectedUniversite = $(this).val();
        
        if (selectedUniversite === 'autre') {
            $('#otherUniversite').show();
            $('#faculte').empty().append('<option value="">Choisir une faculté</option>');
            $('#ville').empty().append('<option value="">Choisir une ville</option>');
        } else {
            $('#otherUniversite').hide();

            $('#faculte').empty().append('<option value="">Choisir une faculté</option><option value="autre">Autre</option>');
            $('#ville').empty().append('<option value="">Choisir une ville</option><option value="autre">Autre</option>');

            @foreach($facultes as $faculte)
                if ('{{ $faculte->id_universite }}' == selectedUniversite) {
                    $('#faculte').append('<option value="{{ $faculte->id }}">{{ $faculte->nom }}</option>');
                }
            @endforeach

            @foreach($facultes->groupBy('ville') as $ville => $faculteGroup)
                $('#ville').append('<option value="{{ $ville }}">{{ $ville }}</option>');
            @endforeach
        }
    });

    $('#faculte').change(function() {
        if ($(this).val() === 'autre') {
            $('#otherFaculte').show();
        } else {
            $('#otherFaculte').hide();
        }
    });

    $('#ville').change(function() {
        if ($(this).val() === 'autre') {
            $('#otherVille').show();
        } else {
            $('#otherVille').hide();
        }
    });

    $('#grade').change(function() {
        if ($(this).val() === 'autre') {
            $('#otherGrade').show();
        } else {
            $('#otherGrade').hide();
        }
    });
});
</script> -->

<!-- <script>
$(document).ready(function() {
    $('#universite').change(function() {
        var selectedUniversite = $(this).val();
        
        if (selectedUniversite === 'autre') {
            $('#otherUniversite').show();
            $('#faculte').empty().append('<option value="">Choisir une faculté</option><option value="autre">Autre</option>');
            $('#ville').empty().append('<option value="">Choisir une ville</option><option value="autre">Autre</option>');
        } else {
            $('#otherUniversite').hide();

            // Update faculte options
            updateFaculteOptions(selectedUniversite);

            // Update ville options
            updateVilleOptions(selectedUniversite);
        }
    });

    $('#faculte').change(function() {
        if ($(this).val() === 'autre') {
            $('#otherFaculte').show();
        } else {
            $('#otherFaculte').hide();
        }
    });

    $('#ville').change(function() {
        if ($(this).val() === 'autre') {
            $('#otherVille').show();
        } else {
            $('#otherVille').hide();
        }
    });

    $('#grade').change(function() {
        if ($(this).val() === 'autre') {
            $('#otherGrade').show();
        } else {
            $('#otherGrade').hide();
        }
    });

    function updateFaculteOptions(selectedUniversite) {
        $('#faculte').empty().append('<option value="">Choisir une faculté</option>');
        @foreach($facultes as $faculte)
            if ('{{ $faculte->id_universite }}' == selectedUniversite) {
                $('#faculte').append('<option value="{{ $faculte->id }}">{{ $faculte->nom }}</option>');
            }
        @endforeach
        $('#faculte').append('<option value="autre">Autre</option>');
    }

    function updateVilleOptions(selectedUniversite) {
        $('#ville').empty().append('<option value="">Choisir une ville</option>');
        @foreach($facultes->groupBy('ville') as $ville => $faculteGroup)
            $('#ville').append('<option value="{{ $ville }}">{{ $ville }}</option>');
        @endforeach
        $('#ville').append('<option value="autre">Autre</option>');
    }
});
</script> -->

<script>
$(document).ready(function() {
    $('#universite').change(function() {
        var selectedUniversite = $(this).val();
        
        if (selectedUniversite === 'autre') {
            $('#otherUniversite').show();
            $('#faculte').empty().append('<option value="">Choisir une faculté</option><option value="autre">Autre</option>');
            $('#ville').empty().append('<option value="">Choisir une ville</option><option value="autre">Autre</option>');
        } else {
            $('#otherUniversite').hide();

            // Update faculte options
            updateFaculteOptions(selectedUniversite);

            // Update ville options
            updateVilleOptions(selectedUniversite);
        }
    });

    $('#faculte').change(function() {
        if ($(this).val() === 'autre') {
            $('#otherFaculte').show();
        } else {
            $('#otherFaculte').hide();
        }
    });

    $('#ville').change(function() {
        if ($(this).val() === 'autre') {
            $('#otherVille').show();
        } else {
            $('#otherVille').hide();
        }
    });

    $('#grade').change(function() {
        if ($(this).val() === 'autre') {
            $('#otherGrade').show();
        } else {
            $('#otherGrade').hide();
        }
    });

    function updateFaculteOptions(selectedUniversite) {
        $('#faculte').empty().append('<option value="">Choisir une faculté</option>');
        @foreach($facultes as $faculte)
            if ('{{ $faculte->id_universite }}' == selectedUniversite) {
                $('#faculte').append('<option value="{{ $faculte->id }}">{{ $faculte->nom }}</option>');
            }
        @endforeach
        $('#faculte').append('<option value="autre">Autre</option>');
    }

    function updateVilleOptions(selectedUniversite) {
        $('#ville').empty().append('<option value="">Choisir une ville</option>');
        @foreach($facultes->groupBy('ville') as $ville => $faculteGroup)
            $('#ville').append('<option value="{{ $ville }}">{{ $ville }}</option>');
        @endforeach
        $('#ville').append('<option value="autre">Autre</option>');
    }
});
</script>

@endsection
