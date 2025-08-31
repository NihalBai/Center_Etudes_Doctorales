<base href="/public">
@extends($layoutFile)

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Information du soutenance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Soutenance</li>
                <li class="breadcrumb-item">planifier une date</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Soutenance</h5>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- Multi Columns Form  -->
                        <h1>Soutenance de Thèse de Doctorat</h1>

                    <p>
                        Le Doyen de la Faculté des Sciences et Techniques de Béni Mellal porte à la connaissance du public que <b>{{ $doctorant->sex === 'male' ? 'Monsieur' : 'Madame' }}
                        {{ $doctorant->prenom }} {{ $doctorant->nom }}</b> soutiendra une thèse de Doctorat intitulée : <b>"{{ $doctorant->theses->titreOriginal }}"</b>.
                    </p>

                    <!-- Create Soutenance Form -->
                    <form action="{{ route('soutenance.store') }}" method="POST">
                        @csrf

                        <!-- doctorant id -->
                        <input type="hidden" name="doctorantId" id="doctorantId" value="{{ $doctorant->id }}">

                        <!-- Date and Time Input -->
                        <label for="date_time">Date and Time:</label>
                        <input type="datetime-local" id="date_time" name="date_time" required><br>

                        <!-- Location Selection -->
                        <label for="localisation">Location:</label>
                        <select name="localisation" id="localisation" required class="form-select w-50" required>
                            @foreach ($localisations as $localisation)
                                <option value="{{ $localisation->id }}">{{ $localisation->nom }} Block {{$localisation->block}}</option>
                            @endforeach
                        </select><br>


                        <div class="mb-3">
                        <!-- Numero Ordre Input -->
                        <label for="numero_ordre">Numero d'ordre doit être supérieur à <b>{{ $lastNumeroOrdre }}</b> :</label><br>
                        <input type="number" id="numero_ordre" name="numero_ordre" value="{{ $lastNumeroOrdre + 1 }}" required><br>
                        </div>

                        <!-- Default Jury Members -->
                        
                    
                        
                            <!-- Member Selection Dropdown -->
                            <div class="mb-3">
                                <label for="selectMember" class="form-label">Sélectionner un membre :</label>
                                <select class="form-select" id="selectMember">
                                    <option value="" selected disabled>Choisir un membre...</option>
                                    @foreach ($members as $member)
                                        <option value="{{ json_encode($member) }}">
                                            {{ $member->nom }} {{ $member->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <!-- Role Selection Dropdown -->
                            <div class="mb-3">
                                <label for="role" class="form-label">Sélectionner le rôle :</label>
                                <select class="form-select" id="role" required>
                                    <option value="" selected disabled>Choisir un rôle...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <!-- Add Member Button -->
                            <button type="button" class="btn btn-primary" id="addMemberBtn">Ajouter Membre</button><br><br>
                        
                            <!-- Jury Members List -->
                            <h2>Jury Members</h2>
                            <div id="juryMembersList">
                                <!-- Dynamic members will be added here -->
                            </div>
                        
                            <!-- Submit Button for Soutenance Creation -->
                            <button type="button" class="btn btn-success mt-3" id="createSoutenanceBtn">Créer Soutenance</button>
                    </div>
                    

                    </form>





    <script>

            document.addEventListener('DOMContentLoaded', function () {
                const selectMember = document.getElementById('selectMember');
                const roleSelect = document.getElementById('role');
                const addMemberBtn = document.getElementById('addMemberBtn');
                const juryMembersList = document.getElementById('juryMembersList');

                // Set to store selected member IDs
                const selectedMemberIds = new Set();

                // Function to add a new member entry to the jury members list
                function addMemberEntry(memberId, memberNom, memberPrenom, memberSex, role) {
                    const newMemberEntry = `
                        <div class="d-flex align-items-center mb-2 memberEntry">
                            <input type="hidden" name="juryMemberIds[]" value="${memberId}">
                            <div>${memberSex === 'male' ? 'M. ' : 'Mme. '} <b>${memberNom} ${memberPrenom}</b> en tant que <b>${role}</b></div>
                            <button type="button" class="btn btn-danger ms-auto removeMemberBtn">
                                <i class="bi bi-dash"></i>
                            </button>
                        </div>
                    `;
                    const newMemberElement = document.createElement('div');
                    newMemberElement.innerHTML = newMemberEntry;
                    juryMembersList.appendChild(newMemberElement);

                    // Add memberId to the Set of selected members
                    selectedMemberIds.add(memberId);

                }

                // Add event listener to "Ajouter Membre" button
                addMemberBtn.addEventListener('click', function () {
                    const selectedOption = selectMember.options[selectMember.selectedIndex];
                    if (!selectedOption || !selectedOption.value) {
                        alert('Veuillez sélectionner un membre valide.');
                        return;
                    }
                    const member = JSON.parse(selectedOption.value);
                    const memberId = member.id;
                    const memberNom = member.nom;
                    const memberPrenom = member.prenom;
                    const memberSex = member.sex;
                    const role = roleSelect.value;

                    // Check if memberId is already selected
                    if (selectedMemberIds.has(memberId)) {
                        alert('Ce membre a déjà été sélectionné.');
                        return;
                    }

                    // Check if the role is selected
                    if (!role || role === 'default') {
                        alert('Veuillez sélectionner un rôle valide.');
                        return;
                    }

                    // add the member to the list
                    addMemberEntry(memberId, memberNom, memberPrenom, memberSex, role);

                    // Clear selection after adding member
                    selectMember.value = 'default';
                    roleSelect.value = 'default';
                });

                // Prepopulate jury members list with default members (director of these and rapporteurs)
                const directorId = '{{ $directorOfThese->id }}';
                const directorName = '{{ $directorOfThese->nom }}';
                const directorPrenom = '{{ $directorOfThese->prenom }}';
                const directorSex = '{{ $directorOfThese->sex }}';
                const directorRole = 'Directeur de thèse';
                addMemberEntry(directorId, directorName, directorPrenom, directorSex, directorRole);

                // Add rapporteurs as default member entries
                const rapporteurs = @json($rapporteurs);
                rapporteurs.forEach(rapporteur => {
                    const rapporteurId = rapporteur.id;
                    const rapporteurNom = rapporteur.nom;
                    const rapporteurPrenom = rapporteur.prenom;
                    const rapporteurSex = rapporteur.sex;
                    const rapporteurRole = 'Rapporteur';
                    addMemberEntry(rapporteurId, rapporteurNom, rapporteurPrenom, rapporteurSex, rapporteurRole);
                });


                // Event listener for removing members
                document.addEventListener('click', function (event) {
                    if (event.target.classList.contains('removeMemberBtn')) {
                        const memberEntry = event.target.closest('.memberEntry');
                        if (memberEntry) {
                            const memberIdInput = memberEntry.querySelector('input[name="juryMemberIds[]"]');
                            if (memberIdInput) {
                                const memberId = memberIdInput.value;
                                // Remove memberId from the Set of selected members
                                selectedMemberIds.delete(memberId);
                            }
                            memberEntry.remove();
                        }
                    }
                });

                

                // Handle submit button click for soutenance creation
                const createSoutenanceBtn = document.getElementById('createSoutenanceBtn');
                createSoutenanceBtn.addEventListener('click', function () {
                    const juryMemberEntries = document.querySelectorAll('.memberEntry');

                    let presidentCount = 0;
                    let directorCount = 0;
                    let presidentRapporteurCount = 0;

                    juryMemberEntries.forEach(entry => {
                        const roleText = entry.querySelector('div b:last-child').textContent;

                        if (roleText.includes('Président/rapporteur')) {
                            presidentRapporteurCount++;
                        } else if (roleText.includes('Président')) {
                            presidentCount++;
                        } else if (roleText.includes('Directeur de thèse')) {
                            directorCount++;
                        }
                    });

                    if ((presidentCount > 0 && presidentRapporteurCount > 0) || (presidentCount > 1 || presidentRapporteurCount > 1)) {
                        alert('Invalid state: Both Président and Président/rapporteur exist or more than one of them exists.');
                        return;
                    } else if (presidentCount === 1 || presidentRapporteurCount === 1) {
                        console.log('Valid state: One of Président or Président/rapporteur exists.');
                    } else {
                        alert('Invalid state: Neither Président nor Président/rapporteur exists.');
                        return;
                    }

                    // if (presidentCount !== 1) {
                    //     alert('Vous devez sélectionner exactement 1 Président.');
                    //     return;
                    // }

                    if (directorCount !== 1) {
                        alert('Vous devez sélectionner exactement 1 Directeur de thèse.');
                        return;
                    }

                    const doctorantId = document.getElementById('doctorantId').value;
                    const dateTime = document.getElementById('date_time').value;
                    const locationId = document.getElementById('localisation').value;
                    const numeroDordre = document.getElementById('numero_ordre').value;

                    const juryMembers = Array.from(juryMemberEntries).map(entry => {
                        const memberId = entry.querySelector('input[name="juryMemberIds[]"]').value;
                        const memberRole = entry.querySelector('div b:last-child').textContent;
                        return { memberId, memberRole };
                    });

                    const formData = {
                        doctorantId,
                        date_time: dateTime,
                        localisation: locationId,
                        numeroDordre,
                        juryMembers
                    };

                    console.log('Submitting form with data:', formData);

                    const requestBody = JSON.stringify(formData);
                    console.log('Form Data JSON:', requestBody);

                    let errorDisplayed = false; // Flag to track if a specific error alert has been displayed
                    
                    fetch('{{ route('soutenance.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                // Check the error code to differentiate error types
                                switch (data.code) {
                                    case 1:
                                        // Handle location already booked error
                                        alert(data.error);
                                        errorDisplayed = true;
                                        break;
                                    case 2:
                                        // Handle duplicate order number error
                                        alert(data.error);
                                        errorDisplayed = true;
                                        break;
                                    case 3:
                                        // Handle jury member conflict error
                                        alert(data.error);
                                        errorDisplayed = true;
                                        break;
                                    default:
                                        // Handle other errors or fallback
                                        alert('An error occurred');
                                }
                            });
                        }
                        // Handle success response
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response from server:', data);

                        if (data && data.message === 'Soutenance created successfully') {
                            // Display success message in an alert
                            alert('Soutenance créée avec succès !');
                            // Redirect to the soutenance.index route after the alert is dismissed
                            window.location.href = '{{ route('soutenance.index') }}';
                        }
                    })
                    .catch(error => {
                        console.error('Error submitting form:', error);

                        // Check if error message is related to network or parsing errors
                        if (error instanceof Error && !errorDisplayed) {
                            // Show the generic error alert only if no specific error alert has been displayed
                            alert('Une erreur s\'est produite lors de la soumission du formulaire.');
                        } else {
                            // Handle other specific error cases based on error details
                            const errorMessage = error.message || 'Une erreur inattendue s\'est produite.';
                            if (!errorDisplayed) {
                                alert(errorMessage);
                            }
                        }
                    });
                });


            });






    </script>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@endsection
