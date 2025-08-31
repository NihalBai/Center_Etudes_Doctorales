<title>Diplome </title>
@extends($layoutFile)
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Diplomes</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Diplome  Doctoral</li>
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
              <h5 class="card-title">Tableaux des Diplomes</h5>
              <p>Vous pouvez ajouter et rechercher des profils de membres.</p><br>
               
<br>

@if($doctorants->isEmpty())
    <p>No results found.</p>
@endif

<table class="table datatable">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Date de Soutenance</th>
                    <th scope="col">Titre de these</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($doctorants as $doctorant)
                
               <tr onclick="location.href='{{ route('doctorant-redirect', ['id' => $doctorant->id]) }}'">
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td scope="row">{{ $doctorant->nom }}</td>
                    <td scope="row">{{ $doctorant->prenom }}</td>
                    <td>{{ $doctorant->soutenances->first()->date }}</td>
                    <td>{{ $doctorant->these ? $doctorant->these->titreOriginal : 'N/A' }}</td>
                   </tr>
            @endforeach
            </tbody>
        </table> 
              <!-- End Table with stripped rows -->

              
            </div>
          </div>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

@endsection