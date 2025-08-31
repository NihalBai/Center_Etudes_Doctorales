@extends($layoutFile)

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Resultats</h1>
      <!-- <nav> -->
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item">Resultat</li>
        <li class="breadcrumb-item">Data Tables</li> 
      
        </ol>
      </nav> -->
    </div><!-- End Page Title -->

    
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tableau des Doctorants sans Resultats</h5>
              <p>Vous pouvez ajouter des resultats pour les soutenace déja passer sans resultats</p><br>
             
            </div>
            


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
    @if ($doctorant->soutenances->isNotEmpty())
    <tr onclick="location.href='{{ route('resultats.show', $doctorant->soutenances->first()->id) }}'">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $doctorant->nom }}</td>
            <td>{{ $doctorant->prenom }}</td>
            <td>{{ $doctorant->soutenances->first()->date }}</td>
            <td>{{ $doctorant->these ? $doctorant->these->titreOriginal : 'N/A' }}</td>
          
        </tr>
    @endif
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