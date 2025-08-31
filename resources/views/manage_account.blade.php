
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateur</title>
</head>
<base href="/public"> 
<body>
@extends($layoutFile)

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Gestion du Compte</h1>
    </div>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informations du Compte</h5>
                            @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                            @if(isset($user1))
                            <form action="{{ route('user.update', ['userId' => $user1->id]) }}" method="POST">

                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user1->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user1->email }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau Mot de Passe</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le Mot de Passe</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>

                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </form>
                            @else
    <p>User not found.</p>
    @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
