@extends('layouts.layoutAdmin')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Tableaux </h1><br>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Modifier les tableaux</h5>
                        <p>Cette page vous permet de modifier rapidement les valeurs des tableaux "Facultés", "Universités" et "Grades". Vous pouvez mettre à jour les informations existantes ou enregistrer de nouvelles entrées en quelques clics.</p>

                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            Les modifications apportées aux valeurs des tables sont instantanément répercutées dans vos documents, assurant ainsi une gestion des données fluide et efficace.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if (session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4">
                                <h2>Facultés</h2>
                                <form action="{{ route('store', 'faculte') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="nom_faculte">Nom</label>
                                        <input type="text" name="nom" id="nom_faculte" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="ville_faculte">Ville</label>
                                        <input type="text" name="ville" id="ville_faculte" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_universite_faculte">Université</label>
                                        <select name="id_universite" id="id_universite_faculte" class="form-control" required>
                                            @foreach($universites as $universite)
                                            <option value="{{ $universite->id }}">{{ $universite->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div><br>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </form>
                            </div>

                            <div class="col-md-4">
                                <h2>Universités</h2>
                                <form action="{{ route('store', 'universite') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="nom_universite">Nom</label>
                                        <input type="text" name="nom" id="nom_universite" class="form-control" required>
                                    </div><br>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </form>
                            </div>

                            <div class="col-md-4">
                                <h2>Grades</h2>
                                <form action="{{ route('store', 'grade') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="nom_grade">Nom</label>
                                        <input type="text" name="nom" id="nom_grade" class="form-control" required>
                                    </div><br>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Facultés</h3>
            <table class="table datatable">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Université</th>
                        <th>Editer</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facultes as $faculte)
                    <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $faculte->nom }}</td>
                        <td>{{ $faculte->ville }}</td>
                        <td>{{ $faculte->universite->nom }}</td>
                        <td>
                         <a href="javascript:void(0)" onclick="showEditModal('faculte', {{ $faculte->id }})" class="btn btn-info btn-sm">Editer</a>
                    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">No records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row">
      
        <div class="col-md-6">
            <h3>Universités</h3>
            <table class="table datatable">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                        <th>Nom</th>
                        <th></th>
                        <th>Editer</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($universites as $universite)
                    <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $universite->nom }}</td>
                        <td></td>
                        <td>
                                            <a href="javascript:void(0)" onclick="showEditModal('universite', {{ $universite->id }})" class="btn btn-info btn-sm">Editer</a>
                                        </td>
                                        <td></td>
                    </tr>
                    @empty
                    <tr>
                        <td>No records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <div class="col-md-6">
            <h3>Grades</h3>
            <table class="table datatable">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                        <th>Nom</th>
                        <th></th>
                        <th>Editer</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades as $grade)
                    <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $grade->nom }}</td>
                        <td></td>
                        <td>
                          <a href="javascript:void(0)" onclick="showEditModal('grade', {{ $grade->id }})" class="btn btn-info btn-sm">Editer</a>
                      </td>
                      <td></td>
                    </tr>
                    @empty
                    <tr>
                        <td>No records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </section>
</main><!-- End #main -->
                            <!-- Modal for editing -->
                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Modifier</h5>
                                           
                                        </div>
                                        <form id="editForm" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <!-- Form fields will be dynamically populated here -->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="reset" class="btn btn-secondary" data-dismiss="modal"aria-label="Close" onclick="closeModal()">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function showEditModal(model, id) {
        $('#editModal').modal('show');
        $('#editForm').attr('action', '/update/' + model + '/' + id);

        $.ajax({
            url: '/edit/' + model + '/' + id,
            type: 'GET',
            success: function(data) {
                $('#editModalLabel').text('Edit ' + model);
                var modalBody = $('#editForm .modal-body');
                modalBody.empty();

                $.each(data, function(key, value) {
                    var field = $('<div class="form-group"></div>');
                    field.append('<label for="' + key + '">' + key + '</label>');
                    field.append('<input type="text" name="' + key + '" id="' + key + '" class="form-control" value="' + value + '" required>');
                    modalBody.append(field);
                });
            }
        });
    }
    function closeModal() {
        $('#editModal').modal('hide');
    }
</script>
