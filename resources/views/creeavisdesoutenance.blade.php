@extends($layoutFile)


@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Crée soutenance</h1>
      <nav>
        {{-- <ol class="breadcrumb">
          <li class="breadcrumb-item">Cree Avis de Soutenance</li>
          <li class="breadcrumb-item">Data Table</li>
          <!-- <li class="breadcrumb-item active">Data</li> -->
        </ol> --}}
      </nav>
    </div><!-- End Page Title -->

    
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tableaux des Doctorant éligible pour une soutenance de thèse</h5>
              <p>Vous pouvez planifier une Soutenance</p><br>
  
            </div>
               
<br><br><br><br>
          <table class="table datatable">
                          <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">Nom</th>
                              <th scope="col">Prenom</th>
                              <th scope="col">Encadrant</th>  
                              <th scope="col">Titre de These</th>
                          </tr>
                          </thead>
                          @if ($doctorants->isNotEmpty())
                          <tbody>
                          @foreach($doctorants as $doctorant)
                          
                          <tr onclick="location.href='{{ route('soutenance.create', ['doctorantId' => $doctorant->id]) }}'">

                              <th scope="row">{{ $loop->iteration }}</th>
                              <td scope="row">{{ $doctorant->nom }}</td>
                              <td scope="row">{{  $doctorant->prenom  }}</td>
                              <td>{{  $doctorant->encadrant_id->nom  }} {{  $doctorant->encadrant_id->prenom  }}</td> 
                            <td scope="row">{{  $doctorant->theses->titreOriginal  }}</td>
                          </tr>
                          @endforeach
                          </tbody>
                          @else
                          {{-- <p>No doctorants found.</p><br> --}}
                          <p>{{ $message ?? 'No doctorants found.' }}</p>
                          @endif
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