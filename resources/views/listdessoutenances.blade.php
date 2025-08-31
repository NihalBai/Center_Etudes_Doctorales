<base href="/public">
@extends($layoutFile)

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Data Tables</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Cree Avis de Soutenance</li>
          <li class="breadcrumb-item">Data Table</li>
          <!-- <li class="breadcrumb-item active">Data</li> -->
        </ol>
      </nav>
    </div><!-- End Page Title -->

    
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tableaux des Soutenances</h5>
              <p>Vous pouvez chercher une Soutenance</p><br>
  
            </div>
               
<br><br><br><br>
          <table class="table datatable">
                          <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">Nom</th>
                              <th scope="col">Prenom</th>
                              <th scope="col">date de soutenance</th>  
                              <th scope="col">localisation</th>
                          </tr>
                          </thead>
                          @if ($doctorants->isNotEmpty())
                          <tbody>
                          @foreach($doctorants as $doctorant)
                          @php
                              $pivotData = $doctorant->soutenances()->first();
                              $soutenance = $pivotData ? $pivotData->pivot->numero_ordre : null;
                          @endphp
                          
                          <tr onclick="location.href='{{ route('soutenance.show', ['doctorantId' => $doctorant->id]) }}'">
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $doctorant->nom }}</td>
                            <td>{{ $doctorant->prenom }}</td>
                            <td>{{ optional($pivotData)->date }}</td> 
                            <td>{{ optional(optional($pivotData)->localisation)->nom }}</td>
                        </tr>
                      @endforeach
                      </tbody>
                      @else
                          <p>No doctorants found.</p><br>
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