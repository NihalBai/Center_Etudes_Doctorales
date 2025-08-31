<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diplome </title>
    
</head>
<base href="/public"> 
<body></body>

@extends($layoutFile)
@section('content')


<main id="main" class="main">

    <div class="pagetitle">
      <h1>Diplomes</h1>
      <nav>
        <ol class="breadcrumb">
          <!-- <li class="breadcrumb-item">Diplome  Doctoral</li> -->
          <!-- <li class="breadcrumb-item">Data Tables</li> -->
          <!-- <li class="breadcrumb-item active">Data</li> -->
        </ol>
      </nav>
    </div><!-- End Page Title -->

    
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"> Diplome</h5>
              <p>Vous pouvez saisir la date de l'imprement.</p><br>
               
              <form class="row g-3" method="POST" action="#">
                            @csrf

                   <div class="col-md-6">
                                <label for="nom" class="form-label">Date : </label>
                                <input  type="date" id="date" name="date" class="form-control"  lang="fr" required>
                            </div>
                            <div class="text-center">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                </form>
                <form action="{{ route('soutenance.attestation.doctorant', ['doctorantId' => $id]) }}" method="POST">
                  
                  @csrf
                  <input type="hidden" name="doctorantId" id="doctorantId" value="{{ $id }}">
                  <button type="submit" class="btn btn-primary ">
                      <i class="bi bi-file-earmark-arrow-down-fill"></i> Imprimer attestation doctorant
                  </button>
                  
                </form>
                <br>
                <form action="{{ route('soutenance.diploma', ['doctorantId' => $id]) }}" method="POST">
                  

                  @csrf
                  <input type="hidden" name="doctorantId" id="doctorantId" value="{{ $id }}">
                  <button type="submit" class="btn btn-primary ">
                      <i class="bi bi-file-earmark-arrow-down-fill"></i> Imprimer Diplome 
                  </button>
                
              </form>
            

                            </div>
                                    </div>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->



@endsection