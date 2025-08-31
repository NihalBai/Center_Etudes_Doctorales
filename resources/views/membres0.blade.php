@extends('layouts\layout')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Data Tables</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Membres</li>
          <li class="breadcrumb-item">Data Tables</li>
          <!-- <li class="breadcrumb-item active">Data</li> -->
        </ol>
      </nav>
    </div><!-- End Page Title -->

    
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tableaux des Membres du jury</h5>
              <p>Vous pouvez ajouter et rechercher des profils de membres.</p><br>
               <!-- <form method="get" action="{{ route('membres.search') }}">
                <input type="text" name="search" placeholder="Recherche" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-primary">Rechercher</button> 
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
               <form method="get" action="{{ route('ajouter-membre') }}">
                <button type="submit" class="btn btn-primary" >Ajouter de nouveaux Membres</button>
            </div>
              </form>  
<br><br><br><br>
             <!-- Table with stripped rows -->
             
            <!-- <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Sexe</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Affiliation</th>
                </tr>
            </thead>
            <tbody>
            @foreach($membres as $membre)
            <div class="trsytle">
               <tr onclick="location.href='{{ route('membres.show', $membre->id) }}'">
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $membre->nom }}</td>
                    <td>{{ $membre->prenom }}</td>
                    <td>{{ $membre->email }}</td>
                    <td>{{ $membre->sex }}</td>
                    <td>{{ $membre->tele }}</td>
                    <td>{{ $membre->grade }}</td>
                    <td>{{ $membre->affiliation->name }}</td>
                </tr></div>
            @endforeach
            </tbody>
        </table>  -->

        @if($membres->isEmpty())
    <p>No results found.</p>
@endif

<table class="table datatable">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                  <!-- <th scope="col">Email</th>
                    <th scope="col">Sexe</th>
                    <th scope="col">Téléphone</th>-->
                    <th scope="col">Grade</th>  
                    <th scope="col">Affiliation</th>
                    <!-- <th scope="col">Profile</th> -->
                 </tr>
                </thead>
                <tbody>
                @foreach($membres as $membre)
                
               <!-- <tr onclick="location.href='{{ route('membres.show', $membre->id) }}'"> -->
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td scope="row">{{ $membre->nom }}</td>
                    <td scope="row">{{ $membre->prenom }}</td>
                    <!-- <td>{{ $membre->email }}</td>
                    <td>{{ $membre->sex }}</td>
                    <td>{{ $membre->tele }}</td> -->
                    <td>{{ $membre->grade }}</td> 
                   <td scope="row">{{ $membre->name }}</td>
                   <!-- <td><a href="{{ route('membres.show', $membre->id) }}">Visit</a></td> -->
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