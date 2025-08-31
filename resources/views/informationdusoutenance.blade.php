<base href="/public">
@extends($layoutFile)

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Soutenance de thèse </h1>
        <nav>
            {{-- <ol class="breadcrumb">
                <li class="breadcrumb-item">Soutenance</li>
                <li class="breadcrumb-item">planifier une date</li>
            </ol> --}}
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">inforamtion du soutenance</h5>
                        <form action="{{ route('soutenance.edit', ['doctorantId' => $doctorant->id]) }}" method="GET">
                            @csrf
                            <input type="hidden" name="doctorantId" id="doctorantId" value="{{ $doctorant->id }}">
                            <button type="submit" class="btn btn-secondary float-end">
                                <i class="bi bi-pen"></i> Edit
                            </button>
                        </form>
                        
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- Multi Columns Form  -->
                        <h1>Avis de soutenance de Thèse de Doctorat</h1>
                        

                        <p>
                            Le Doyen de la Faculté des Sciences et Techniques de Béni Mellal porte à la connaissance du public que <b>{{ $doctorant->sex === 'male' ? 'Monsieur' : 'Madame' }}
                            {{ $doctorant->nom }} {{ $doctorant->prenom }}</b> soutiendra une thèse de Doctorat intitulée :  <b>«{{ $doctorant->theses->titreOriginal }}»</b>.
                        </p>
                    
                        @if($doctorant->soutenances->isNotEmpty())
                            @php
                                $soutenance = $doctorant->soutenances->first();
                                $dayOfWeek = ucfirst(\Carbon\Carbon::parse($soutenance->date)->locale('fr')->isoFormat('dddd'));
                                $dayOfMonth = ucfirst(\Carbon\Carbon::parse($soutenance->date)->isoFormat('D'));
                                $month = ucfirst(\Carbon\Carbon::parse($soutenance->date)->locale('fr')->isoFormat('MMMM'));
                            @endphp
                            <p>
                                La soutenance publique aura lieu le <b>{{ $dayOfWeek }} {{ $dayOfMonth }} {{ $month }} {{ \Carbon\Carbon::parse($soutenance->heure)->format('Y') }} à {{ \Carbon\Carbon::parse($soutenance->heure)->format('H:i') }} à la {{ $soutenance->localisation->nom }}</b> de la Faculté des Sciences et Techniques de Béni Mellal devant le jury composé de :
                            </p>

                            <ul>
                                @foreach($soutenance->juryMembers as $juryMember)
                                    @php
                                        $grade = $juryMember->getGradeBefore($soutenance->date);
                                    @endphp
                                    <li>
                                        <b>{{ $juryMember->sex === 'male' ? 'Monsieur' : 'Madame' }} {{ $juryMember->nom }} {{ $juryMember->prenom }}</b> : 
                                        {{ $grade ? $grade->nom : 'Grade not found' }},
                                        {{ $juryMember->getNameAttribute() }}, 
                                        <b>{{ $juryMember->pivot->qualite }}</b>;
                                    </li>
                                @endforeach

                            </ul>

                            <br><br>
                            <form action="{{ route('soutenance.avis', ['doctorantId' => $doctorant->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="doctorantId" id="doctorantId" value="{{ $doctorant->id }}">
                                <button type="submit" class="btn btn-primary ">
                                    <i class="bi bi-file-earmark-arrow-down-fill"></i> Imprimer Avis
                                </button>
                            </form>
                            <form action="{{ route('soutenance.attestation.jury', ['doctorantId' => $doctorant->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="doctorantId" id="doctorantId" value="{{ $doctorant->id }}">
                                <button type="submit" class="btn btn-primary ">
                                    <i class="bi bi-file-earmark-arrow-down-fill"></i> Imprimer attestation
                                </button>
                            </form>
                            <form action="{{ route('soutenance.invitation.jury', ['doctorantId' => $doctorant->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="doctorantId" id="doctorantId" value="{{ $doctorant->id }}">
                                <button type="submit" class="btn btn-primary ">
                                    <i class="bi bi-file-earmark-arrow-down-fill"></i> Imprimer invitation
                                </button>
                            </form>
                            
                            <form action="{{ route('soutenance.rapport', ['doctorantId' => $doctorant->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="doctorantId" id="doctorantId" value="{{ $doctorant->id }}">
                                <button type="submit" class="btn btn-primary ">
                                    <i class="bi bi-file-earmark-arrow-down-fill"></i>  Imprimer rapport du soutenance 
                                </button>
                            </form>
                           
                            
                        @else
                            <p>Aucune soutenance trouvée pour ce doctorant.</p>
                        @endif

                                        

                    </form>
</main>
                

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@endsection
