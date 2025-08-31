

@extends($layoutFile)

@section('content')

    
    <main id="main" class="main">
    <div class="pagetitle">
        <h1>Utilisateurs</h1>
        <nav>
            <ol class="breadcrumb">
                
                <li class="breadcrumb-item">Ajouter Utilisateurs</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Nouveau Utilisateur</h5>
                    <p>Vous pouvez créer un nouvel utilisateur et définir son type (administrateur, service CED, directeur ou rapporteur) et créer un mot de passe également..</p><br>
                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                   
                   
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <!-- Multi Columns Form  -->
                    <form class="row g-3" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom et Prénom:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="sex" class="form-label">Sexe</label>
                            <select class="form-control" id="sex" name="sex" >
                                <option value="male">Homme</option>
                                <option value="female">Femme</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tele" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="tele" name="tele" >
                        </div>
                        <div class="col-md-6">
                                <label for="type" class="form-label">Type:</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="admin">Admin</option>
                                    <option value="service_ced">Service CED</option>
                                    <option value="directeur">Directeur</option>
                                    <option value="rapporteur">Rapporteur</option>
                                </select>
                            </div>
                         <div class="col-md-6">
                            <label for="profile_photo_path" class="form-label">Photo de profil:</label>
                            <input type="file" id="profile_photo_path" class="form-control" name="profile_photo_path" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de passe:</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe:</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
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
@endsection