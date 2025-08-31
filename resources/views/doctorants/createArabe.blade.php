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
                    <form class="row g-3" id="create-doctorant-form" method="post" action="{{ route('doctorants.storeArabe') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="doctorant_id" value="{{ $doctorant->id }}">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">الاسم</label>
                            <input type="text" class="form-control" id="nom" name="nom">
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">اللقب</label>
                            <input type="text" class="form-control" id="prenom" name="prenom">
                        </div>
                        <div class="col-md-6">
                            <label for="discipline" class="form-label">التخصص</label>
                            <input type="text" class="form-control" id="discipline" name="discipline" required>
                        </div>
                        <div class="col-md-6">
                            <label for="specialite" class="form-label">التخصص الدقيق</label>
                            <input type="text" class="form-control" id="specialite" name="specialite" required>
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-primary" id="submitBtn">Ajouter</button>
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
    document.addEventListener('DOMContentLoaded', () => {
        const submitBtn = document.getElementById('submitBtn');

        submitBtn.addEventListener('click', () => {
            document.getElementById('create-doctorant-form').submit();
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
</style>
</body>
</html>
