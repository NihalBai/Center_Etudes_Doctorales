@extends($layoutFile)

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Data Tables</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Membres</li>
          <li class="breadcrumb-item">Data Tables</li>
          <li class="breadcrumb-item active">Data</li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->

    
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tableaux des Membres du jury</h5>
              <p>Vous pouvez ajouter et rechercher des profils de membres.</p><br>
              <form method="get" action="{{ route('ajouter-membre') }}">
                <button type="submit" class="btn btn-primary" >Ajouter de nouveaux Membres</button>
            </div>
              </form>
<br><br><br><br>

@if($membres->isEmpty())
    <p>No results found.</p>
@endif

<table class="table datatable">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Grade</th>  
                    <th scope="col">Affiliation</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($membres as $membre)
                
               <tr onclick="location.href='{{ route('membres.profil', $membre->id) }}'">
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td scope="row">{{ $membre->nom }}</td>
                    <td scope="row">{{ $membre->prenom }}</td>
                    
                    <td>{{ optional($membre->latestGrade())->nom ?? 'N/A' }}</td>


                   <td scope="row">{{ $membre->name }}</td>
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
      </div>
      </div>

    </section> 

  </main>
  <!-- End #main-->
@endsection




