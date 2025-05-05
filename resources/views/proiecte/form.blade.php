@csrf

<div id="datePicker" class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
    <div class="col-lg-8 mb-4">
        <label for="tema_de_proiectare" class="mb-0 ps-3">Tema de proiectare<span class="text-danger">*</span></label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="tema_de_proiectare"
            id="tema_de_proiectare"
            value="{{ old('tema_de_proiectare', $proiect->tema_de_proiectare ?? '') }}">
    </div>
    <div class="col-lg-4 mb-4">
        <label for="contract" class="mb-0 ps-3">Contract</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="contract"
            id="contract"
            value="{{ old('contract', $proiect->contract ?? '') }}">
    </div>
    <div class="col-lg-6 mb-4">
        <label for="oferta" class="mb-0 ps-3">Oferta</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="oferta"
            id="oferta"
            value="{{ old('oferta', $proiect->oferta ?? '') }}">
    </div>
    <div class="col-lg-6 mb-4">
        <label for="plati" class="mb-0 ps-3">Plăți</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="plati"
            id="plati"
            value="{{ old('plati', $proiect->plati ?? '') }}">
    </div>
</div>

<div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
    <div class="col-lg-12">
        <!-- Where we mount our ClientiSelector Vue app -->
        <div id="clientiSelectorApp">
            <!-- Pass the data from the controller as JSON props -->
            <clienti-selector
                :all-clienti='@json($allClienti)'
                :existing-clienti='@json($existingClienti)'
            >
            </clienti-selector>
        </div>
    </div>
</div>

<div id="datePicker" class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
    <div class="col-lg-4 mb-4">
        <label for="obtinere_certificat_urbanism" class="mb-0 ps-3">Obtinere Certificat Urbanism</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="obtinere_certificat_urbanism"
            id="obtinere_certificat_urbanism"
            value="{{ old('obtinere_certificat_urbanism', $proiect->obtinere_certificat_urbanism ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="dtoe" class="mb-0 ps-3">DTOE</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="dtoe" id="dtoe"
            value="{{ old('dtoe', $proiect->dtoe ?? '') }}">
    </div>
</div>

<div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
    <div class="col-lg-4 mb-4">
        <label for="certificat_de_urbanism" class="mb-0 ps-3">Certificat de Urbanism</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="certificat_de_urbanism" id="certificat_de_urbanism"
            value="{{ old('certificat_de_urbanism', $proiect->certificat_de_urbanism ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="alimentare_cu_apa" class="mb-0 ps-3">Alimentare cu Apa</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="alimentare_cu_apa" id="alimentare_cu_apa"
            value="{{ old('alimentare_cu_apa', $proiect->alimentare_cu_apa ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="canalizare" class="mb-0 ps-3">Canalizare</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="canalizare" id="canalizare"
            value="{{ old('canalizare', $proiect->canalizare ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="transport_urban" class="mb-0 ps-3">Transport Urban</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="transport_urban" id="transport_urban"
            value="{{ old('transport_urban', $proiect->transport_urban ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="nomenclator" class="mb-0 ps-3">Nomenclator</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="nomenclator" id="nomenclator"
            value="{{ old('nomenclator', $proiect->nomenclator ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="alimentare_cu_energie_electrica" class="mb-0 ps-3">Alimentare cu Energie Electrica</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="alimentare_cu_energie_electrica" id="alimentare_cu_energie_electrica"
            value="{{ old('alimentare_cu_energie_electrica', $proiect->alimentare_cu_energie_electrica ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="gaze_naturale" class="mb-0 ps-3">Gaze Naturale</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="gaze_naturale" id="gaze_naturale"
            value="{{ old('gaze_naturale', $proiect->gaze_naturale ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="telefonizare" class="mb-0 ps-3">Telefonizare</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="telefonizare" id="telefonizare"
            value="{{ old('telefonizare', $proiect->telefonizare ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="salubritate" class="mb-0 ps-3">Salubritate</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="salubritate" id="salubritate"
            value="{{ old('salubritate', $proiect->salubritate ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="alimentare_cu_energie_termica" class="mb-0 ps-3">Alimentare cu Energie Termica</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="alimentare_cu_energie_termica" id="alimentare_cu_energie_termica"
            value="{{ old('alimentare_cu_energie_termica', $proiect->alimentare_cu_energie_termica ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="isu" class="mb-0 ps-3">ISU</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="isu" id="isu"
            value="{{ old('isu', $proiect->isu ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="dsp" class="mb-0 ps-3">DSP</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="dsp" id="dsp"
            value="{{ old('dsp', $proiect->dsp ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="dsv" class="mb-0 ps-3">DSV</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="dsv" id="dsv"
            value="{{ old('dsv', $proiect->dsv ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="anif" class="mb-0 ps-3">ANIF</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="anif" id="anif"
            value="{{ old('anif', $proiect->anif ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="ospa" class="mb-0 ps-3">OSPA</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="ospa" id="ospa"
            value="{{ old('ospa', $proiect->ospa ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="apia" class="mb-0 ps-3">APIA</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="apia" id="apia"
            value="{{ old('apia', $proiect->apia ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="daj" class="mb-0 ps-3">DAJ</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="daj" id="daj"
            value="{{ old('daj', $proiect->daj ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="mediu" class="mb-0 ps-3">Mediu</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="mediu" id="mediu"
            value="{{ old('mediu', $proiect->mediu ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="arie_protejata" class="mb-0 ps-3">Arie Protejata</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="arie_protejata" id="arie_protejata"
            value="{{ old('arie_protejata', $proiect->arie_protejata ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="studiu_geotehnic" class="mb-0 ps-3">Studiu Geotehnic</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="studiu_geotehnic" id="studiu_geotehnic"
            value="{{ old('studiu_geotehnic', $proiect->studiu_geotehnic ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="studiu_topo" class="mb-0 ps-3">Studiu Topo</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="studiu_topo" id="studiu_topo"
            value="{{ old('studiu_topo', $proiect->studiu_topo ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="verificare_tehnica_structura" class="mb-0 ps-3">Verificare Tehnica Structura</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="verificare_tehnica_structura" id="verificare_tehnica_structura"
            value="{{ old('verificare_tehnica_structura', $proiect->verificare_tehnica_structura ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="verificare_tehnica_instalatii" class="mb-0 ps-3">Verificare Tehnica Instalatii</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="verificare_tehnica_instalatii" id="verificare_tehnica_instalatii"
            value="{{ old('verificare_tehnica_instalatii', $proiect->verificare_tehnica_instalatii ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="dovada_oar" class="mb-0 ps-3">Dovada OAR</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="dovada_oar" id="dovada_oar"
            value="{{ old('dovada_oar', $proiect->dovada_oar ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="ipj" class="mb-0 ps-3">IPJ</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="ipj" id="ipj"
            value="{{ old('ipj', $proiect->ipj ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="cj" class="mb-0 ps-3">CJ</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="cj" id="cj"
            value="{{ old('cj', $proiect->cj ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="conpet" class="mb-0 ps-3">CONPET</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="conpet" id="conpet"
            value="{{ old('conpet', $proiect->conpet ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="cfr" class="mb-0 ps-3">CFR</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="cfr" id="cfr"
            value="{{ old('cfr', $proiect->cfr ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="sga" class="mb-0 ps-3">SGA</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="sga" id="sga"
            value="{{ old('sga', $proiect->sga ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="nzeb" class="mb-0 ps-3">NZEB</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="nzeb" id="nzeb"
            value="{{ old('nzeb', $proiect->nzeb ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="saer" class="mb-0 ps-3">SAER</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="saer" id="saer"
            value="{{ old('saer', $proiect->saer ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="expertiza_tehnica" class="mb-0 ps-3">Expertiza Tehnica</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="expertiza_tehnica" id="expertiza_tehnica"
            value="{{ old('expertiza_tehnica', $proiect->expertiza_tehnica ?? '') }}">
    </div>
</div>

<div id="datePicker" class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
    <div class="col-lg-4 mb-4">
        <label for="notare_ac_la_ocpi" class="mb-0 ps-3">Notare A.C. la OCPI</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="notare_ac_la_ocpi" id="notare_ac_la_ocpi"
            value="{{ old('notare_ac_la_ocpi', $proiect->notare_ac_la_ocpi ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="comunicare_incepere_lucrari_ziar" class="mb-0 ps-3">Comunicare Incepere Lucrari Ziar</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="comunicare_incepere_lucrari_ziar" id="comunicare_incepere_lucrari_ziar"
            value="{{ old('comunicare_incepere_lucrari_ziar', $proiect->comunicare_incepere_lucrari_ziar ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="comunicare_incepere_lucrari_isc" class="mb-0 ps-3">Comunicare Incepere Lucrari ISC</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="comunicare_incepere_lucrari_isc" id="comunicare_incepere_lucrari_isc"
            value="{{ old('comunicare_incepere_lucrari_isc', $proiect->comunicare_incepere_lucrari_isc ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="comunicare_incheiere_lucrari_primarie" class="mb-0 ps-3">Comunicare Incheiere Lucrari Primarie</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="comunicare_incheiere_lucrari_primarie" id="comunicare_incheiere_lucrari_primarie"
            value="{{ old('comunicare_incheiere_lucrari_primarie', $proiect->comunicare_incheiere_lucrari_primarie ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="dirigentie_santier" class="mb-0 ps-3">Dirigentie Santier</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="dirigentie_santier" id="dirigentie_santier"
            value="{{ old('dirigentie_santier', $proiect->dirigentie_santier ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="receptie_depunere_documentatie" class="mb-0 ps-3">Receptie (Depunere Documentatie)</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="receptie_depunere_documentatie" id="receptie_depunere_documentatie"
            value="{{ old('receptie_depunere_documentatie', $proiect->receptie_depunere_documentatie ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="edificare_depunere_documentatie" class="mb-0 ps-3">Edificare (Depunere Documentatie)</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="edificare_depunere_documentatie" id="edificare_depunere_documentatie"
            value="{{ old('edificare_depunere_documentatie', $proiect->edificare_depunere_documentatie ?? '') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="inscriere_constructie" class="mb-0 ps-3">Inscriere Constructie</label>
        <input type="text" class="form-control bg-white rounded-3"
            name="inscriere_constructie" id="inscriere_constructie"
            value="{{ old('inscriere_constructie', $proiect->inscriere_constructie ?? '') }}">
    </div>
</div>


<div class="row">
    <div id="disableOnceButton" class="col-lg-12 px-4 py-2 mb-0 text-center">
        <disable-once-button
            class="btn btn-primary text-white me-3 rounded-3"
            type="submit"
            processing-text="Se procesează cererea..."
            :hide-on-click="false"
        >
            <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
        </disable-once-button>
        <a class="btn btn-secondary rounded-3" href="{{ Session::get('returnUrl', route('proiecte.index', $proiectTip->slug)) }}">
            Renunță
        </a>
    </div>
</div>
