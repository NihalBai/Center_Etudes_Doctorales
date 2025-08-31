@extends('layouts.layoutAdmin')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Templates</h1><br>
      
    </div><!-- End Page Title -->

    
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"> Modifier les Modèles</h5>
              <p>Cette page permet d'imprimer, remplacer et personnaliser vos modèles de documents en un clic. Gérer et mettre à jour vos modèles devient simple et rapide.
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i> Assurez-vous de placer le fichier correct dans l'emplacement du modèle avant de cliquer sur remplacer.</span> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              </p><br>
              @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    @foreach($templates as $template)
  
        <h3>{{ $template }}</h3>
        <div class="button-container">
    
            <form action="{{ route('print.template', ['template' => $template]) }}" method="GET">
           
            &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <button class="btn btn-primary" type="submit">Imprimer Modèle</button>
                
            </form>
            
      
            <form action="{{ route('replace.template', ['template' => $template]) }}" method="POST" enctype="multipart/form-data" class="replace-form">
                @csrf
                 
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   <input class="form-control" type="file" name="file" required>
              
                <button  class="btn btn-outline-success" type="submit">Remplacer Le Modèle</button><br><br><br>
                
            </form>
       </div><br>
    @endforeach
                            </div>
                                    </div>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->




@endsection