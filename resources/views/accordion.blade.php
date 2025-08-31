<main id="main" class="main">

<section class="section">
  <div class="row">
    <div class="col-lg-6">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Soutenances Evaluées</h5>

          <!-- Default Accordion -->
          <div class="accordion" id="accordionExample">
            foreach(soutenances as soutenance)
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Le {{$soutenance->date}} A {{$soutenance->heure}};
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <strong>This is the first item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                </div>
              </div>
            </div>
            @end

          </div><!-- End Default Accordion Example -->
        </div>
      </div>
    </div>
  </div>
</section>

</main><!-- End #main -->

   


<section class="section">
<div class="row">
<div class="col-lg-12">

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Accordion without outline borders</h5>

    <!-- Accordion without outline borders -->
    <div class="accordion accordion-flush" id="accordionFlushExample">
    @foreach ($data as $item)
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
            Thèse: {{ $item['these']['titreOriginal'] }}
          </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <!--My section for this -->
          <section class="section">
          <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">{{ $item['these']['titreOriginal'] }}</h5>
            <p class="card-text">Doctorant: {{ $item['these']['nom'] }} {{ $item['these']['prenom'] }}</p>
            <p class="card-text">Structure de Recherche: {{ $item['these']['structure_recherche'] }}</p>
            <p class="card-text">Date de Première Inscription: {{ $item['these']['date_premiere_inscription'] }}</p>
            <p class="card-text">Nombre de Publications (Article): {{ $item['these']['nombre_publications_article'] }}</p>
            <p class="card-text">Nombre de Publications (Conférence): {{ $item['these']['nombre_publications_conference'] }}</p>
            <p class="card-text">Nombre de Communications: {{ $item['these']['nombre_communications'] }}</p>
            <p>Session de : {{ \Carbon\Carbon::parse($item['these']['session_date'])->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
            <h6>Documents :</h6>
<ul>
    @foreach ($data as $item)
        <li>
            <strong>{{ $item['these']['nom'] }} {{ $item['these']['prenom'] }}</strong>
            <ul>
                @if ($item['documents']['rapportThese'])
                    @php
                        $extensionRapport = pathinfo($item['documents']['rapportThese'], PATHINFO_EXTENSION);
                    @endphp
                    <li>
                        <a href="{{ asset('storage/dossiersdoctorants/'.$item['documents']['rapportThese']) }}" 
                           download="Rapport_{{ $item['these']['nom'] }}-{{ $item['these']['prenom'] }}.{{ $extensionRapport }}">
                           Télécharger Rapport de Thèse
                        </a>
                    </li>
                @endif
                @if ($item['documents']['cv'])
                    @php
                        $extensionCv = pathinfo($item['documents']['cv'], PATHINFO_EXTENSION);
                    @endphp
                    <li>
                        <a href="{{ asset('storage/dossiersdoctorants/'.$item['documents']['cv']) }}" 
                           download="CV_{{ $item['these']['nom'] }}-{{ $item['these']['prenom'] }}.{{ $extensionCv }}">
                           Télécharger CV
                        </a>
                    </li>
                @endif
                @if ($item['documents']['dossierScientifique'])
                    @php
                        $extensionDossier = pathinfo($item['documents']['dossierScientifique'], PATHINFO_EXTENSION);
                    @endphp
                    <li>
                        <a href="{{ asset('storage/dossiersdoctorants/'.$item['documents']['dossierScientifique']) }}" 
                           download="Dossier_Scientifique_{{ $item['these']['nom'] }}-{{ $item['these']['prenom'] }}.{{ $extensionDossier }}">
                           Télécharger Dossier Scientifique
                        </a>
                    </li>
                @endif
            </ul>
        </li>
    @endforeach
</ul>

<script>
    // Téléchargement automatique lors du chargement de la page
    document.addEventListener("DOMContentLoaded", function() {
        var rapportTheseLink = document.getElementById('rapportTheseLink');
        var cvLink = document.getElementById('cvLink');
        var dossierScientifiqueLink = document.getElementById('dossierScientifiqueLink');
        
        if (rapportTheseLink) rapportTheseLink.click();
        if (cvLink) cvLink.click();
        if (dossierScientifiqueLink) dossierScientifiqueLink.click();
    });
</script>




          </div>
        </div>
  </section>




          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingTwo">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
            Accordion Item #2
          </button>
        </h2>
        <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.</div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
            Accordion Item #3
          </button>
        </h2>
        <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
        </div>
      </div>
    </div><!-- End Accordion without outline borders -->

  </div>
</div>

</div>
</div>
</section>

<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Remplir Avis et Rapport</h5>
              <p>Merci de remplir le formulaire ci-dessous en indiquant votre avis (favorable ou défavorable) 
                et en téléchargeant votre rapport de thèse.</p>

              <!-- Basic Modal -->
              <form action="#" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="col-lg-4 mb-3">
                    <label for="rapport" class="form-label">Rapport de Thèse:</label>
                    <input class="form-control" type="file" id="rapport" name="rapport" required>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label">Votre avis:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="avis" id="avisFavorable" value="favorable" required>
                        <label class="form-check-label" for="avisFavorable">
                            Favorable
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="avis" id="avisDefavorable" value="defavorable" required>
                        <label class="form-check-label" for="avisDefavorable">
                            Défavorable
                        </label>
                    </div>
                    </div>

              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">
                Confirmation
              </button>
              <div class="modal fade" id="basicModal" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Confirmation</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                   <P>Votre contribution est essentielle pour assurer la qualité et l'intégrité de notre processus de validation des thèses.</p>

                    <p>Merci pour votre coopération et votre engagement envers nos étudiants et notre institution.</p>
                    <h5><center>Centre d'Etudes Doctorales - Fstbm</center></h5>
                    </div>

                    <div class="modal-footer">
                      <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">annuler</button>
                      <button type="submit" class="btn btn-primary">Ok</button>
              </form>
                    </div>
                  </div>
                </div>
              </div><!-- End Basic Modal-->

            </div>
          </div>
        </div>
      </div>
    </section>
    </main><!-- End Main Content -->






    <div class="accordion accordion-flush" id="accordionFlushExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
            Evaluation:
          </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <!--My section for this -->






            <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Remplir Avis et Rapport</h5>
              <p>Merci de remplir le formulaire ci-dessous en indiquant votre avis (favorable ou défavorable) 
                et en téléchargeant votre rapport de thèse.</p>

              <!-- Basic Modal -->
              <form action="#" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="col-lg-4 mb-3">
                    <label for="rapport" class="form-label">Rapport de Thèse:</label>
                    <input class="form-control" type="file" id="rapport" name="rapport" required>
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label">Votre avis:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="avis" id="avisFavorable" value="favorable" required>
                        <label class="form-check-label" for="avisFavorable">
                            Favorable
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="avis" id="avisDefavorable" value="defavorable" required>
                        <label class="form-check-label" for="avisDefavorable">
                            Défavorable
                        </label>
                    </div>
                    </div>

              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">
                Confirmation
              </button>
              <div class="modal fade" id="basicModal" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Confirmation</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                   <P>Votre contribution est essentielle pour assurer la qualité et l'intégrité de notre processus de validation des thèses.</p>

                    <p>Merci pour votre coopération et votre engagement envers nos étudiants et notre institution.</p>
                    <h5><center>Centre d'Etudes Doctorales - Fstbm</center></h5>
                    </div>

                    <div class="modal-footer">
                      <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">annuler</button>
                      <button type="submit" class="btn btn-primary">Ok</button>
              </form>
                    </div>
                  </div>
                </div>
              </div><!-- End Basic Modal-->

            </div>
          </div>
        </div>
      </div>
    </section>






            </div>
            </div>
            </div>
            </div>
