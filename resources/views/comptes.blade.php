
@extends($layoutFile)

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Gestion des Comptes </h1>
        <nav>
            <ol class="breadcrumb">
                
                <li class="breadcrumb-item">Active / Désactive </li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Comptes des  Utilisateurs</h5>
                    <>Sur cette page, vous pouvez voir la liste des utilisateurs de l'application. Vous pouvez activer ou désactiver un utilisateur en cliquant sur un bouton correspondant à son état. Vous pouvez également cliquer sur un utilisateur pour accéder à une page où vous pouvez modifier son nom, son email et son mot de passe.</p><br>
<div class="container">
   
    <table class="table datatable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th  scope="col">Name</th>
                <th  scope="col">Email</th>
                <th  scope="col">Type</th>
                <th  scope="col">Etat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr onclick="location.href='{{ route('user.account', ['user' => $user->id]) }}'">
                <th scope="row">{{ $loop->iteration }}</th>
                    <td scope="row">{{ $user->name }}</td>
                    <td scope="row">{{ $user->email }}</td>
                    <td scope="row">{{ $user->type }}</td></a>
                    <td scope="row">
                        @if($user->etat == 'active')
                        <button class="btn btn-secondary change-etat" data-id="{{ $user->id }}" data-etat="inactive" onclick="event.stopPropagation();">Désactive</button>
                        @else
                        <button class="btn btn-primary change-etat" data-id="{{ $user->id }}" data-etat="active" onclick="event.stopPropagation();">Activate</button>
                        @endif
                        <!-- <button class="btn btn-danger delete-user" data-id="{{ $user->id }}">Delete</button> -->
                    </td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
            </div>
        </div>
    </div>
</section>

</main><!-- End #main -->



<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.change-etat').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            const etat = this.getAttribute('data-etat');
            fetch(`/utilisateurs/${userId}/etat`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ etat })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI immediately
                    this.setAttribute('data-etat', data.etat);
                    this.textContent = data.etat === 'active' ? 'Deactivate' : 'Activate';
                } else {
                    alert('Failed to update user status.');
                }
            });
        });
    });

    // document.querySelectorAll('.delete-user').forEach(button => {
    //     button.addEventListener('click', function () {
    //         const userId = this.getAttribute('data-id');
    //         // Show confirmation dialog
    //         if (confirm('Are you sure you want to delete this user?')) {
    //             fetch(`/utilisateurs/${userId}`, {
    //                 method: 'DELETE',
    //                 headers: {
    //                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //                 }
    //             })
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (data.success) {
    //                     // Update UI immediately
    //                     const row = this.closest('tr');
    //                     row.parentNode.removeChild(row);
    //                 } else {
    //                     alert('Failed to delete user.');
    //                 }
    //             });
    //         }
    //     });
    // });
});

</script> -->

<script>
   document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.change-etat').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            const currentEtat = this.getAttribute('data-etat');
            const newEtat = currentEtat === 'active' ? 'inactive' : 'active';
            fetch(`/utilisateurs/${userId}/etat`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ etat: newEtat })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI immediately
                    this.setAttribute('data-etat', data.etat);
                    this.textContent = data.etat === 'active' ? 'Désactive' : 'Activate';
                    this.classList.toggle('btn-primary', data.etat === 'inactive');
                    this.classList.toggle('btn-secondary', data.etat === 'active');
                } else {
                    alert('Failed to update user status.');
                }
            });
        });
    });});

</script>
@endsection
