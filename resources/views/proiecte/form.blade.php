@csrf

<div id="datePicker" class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
    <div class="col-lg-12 mb-4">
        <label for="denumire_contract" class="mb-0 ps-3">Denumire Contract</label>
        <textarea
            class="form-control bg-white rounded-3"
            name="denumire_contract"
            id="denumire_contract"
            rows="3">{{ old('denumire_contract', $proiect->denumire_contract ?? '') }}</textarea>
    </div>

    <div class="col-lg-8 mb-4">
        <label for="nr_contract" class="mb-0 ps-3">Nr. Contract</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="nr_contract"
            id="nr_contract"
            value="{{ old('nr_contract', $proiect->nr_contract ?? '') }}">
    </div>

    <div class="col-lg-2 mb-4 text-center">
        <label for="data_contract" class="mb-0 ps-0">Data Contract</label>
        <vue-datepicker-next
            id="data_contract"
            data-veche="{{ old('data_contract', $proiect->data_contract ?? null) }}"
            nume-camp-db="data_contract"
            tip="date"
            value-type="YYYY-MM-DD"
            format="DD.MM.YYYY"
            :latime="{ width: '125px' }"
        ></vue-datepicker-next>
    </div>

    <div class="col-lg-2 mb-4 text-center">
        <label for="data_limita_predare" class="mb-0 ps-0">Data Limită Predare</label>
        <vue-datepicker-next
            id="data_limita_predare"
            data-veche="{{ old('data_limita_predare', $proiect->data_limita_predare ?? null) }}"
            nume-camp-db="data_limita_predare"
            tip="date"
            value-type="YYYY-MM-DD"
            format="DD.MM.YYYY"
            :latime="{ width: '125px' }"
        ></vue-datepicker-next>
    </div>

    <div class="col-lg-9 mb-4">
        <label for="nr_proces_verbal_predare_primire" class="mb-0 ps-3">Nr. Proces Verbal Predare-Primire</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="nr_proces_verbal_predare_primire"
            id="nr_proces_verbal_predare_primire"
            value="{{ old('nr_proces_verbal_predare_primire', $proiect->nr_proces_verbal_predare_primire ?? '') }}">
    </div>

    <div class="col-lg-3 mb-4 text-center">
        <label for="data_proces_verbal_predare_primire" class="mb-0 ps-0 small">Data Proces Verbal Predare-Primire</label>
        <vue-datepicker-next
            id="data_proces_verbal_predare_primire"
            data-veche="{{ old('data_proces_verbal_predare_primire', $proiect->data_proces_verbal_predare_primire ?? null) }}"
            nume-camp-db="data_proces_verbal_predare_primire"
            tip="date"
            value-type="YYYY-MM-DD"
            format="DD.MM.YYYY"
            :latime="{ width: '125px' }"
        ></vue-datepicker-next>
    </div>

    <div class="col-lg-12 mb-4">
        <label for="stare_contract" class="mb-0 ps-3">Stare Contract</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="stare_contract"
            id="stare_contract"
            value="{{ old('stare_contract', $proiect->stare_contract ?? '') }}">
    </div>

    @can('admin-action')
        <div class="col-lg-3 mb-4">
            <label for="pret" class="mb-0 ps-3">Preț</label>
            <input
                type="text"
                class="form-control bg-white rounded-3"
                name="pret"
                id="pret"
                value="{{ old('pret', $proiect->pret ?? '') }}">
        </div>

        <div class="col-lg-9 mb-4">
            <label for="pret_observatii" class="mb-0 ps-3">Preț observații</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="pret_observatii"
                id="pret_observatii"
                rows="3">{{ old('pret_observatii', $proiect->pret_observatii ?? '') }}</textarea>
        </div>
    @endcan
</div>

@if(in_array($proiectTip->slug, ['civile', 'privati']))
    <div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
        <div class="col-lg-12">
            <!-- Where we mount our MembriSelector Vue app -->
            <div id="membriSelectorApp">
                <!-- Pass the data from the controller as JSON props -->
                <membri-selector
                    :all-membri='@json($allMembri)'
                    :existing-membri='@json($existingMembri)'
                >
                </membri-selector>
            </div>
        </div>
    </div>
@endif

@if(in_array($proiectTip->slug, ['civile', 'apa-canal', 'drumuri', 'privati', 'pug']))
    <div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:#fff9f5">
        <div class="col-lg-12">
            <!-- Where we mount our SubcontractantiSelector Vue app -->
            <div id="subcontractantiSelectorApp">
                <!-- Pass the data from the controller as JSON props -->
                <subcontractanti-selector
                    :all-subcontractanti='@json($allSubcontractanti)'
                    :existing-subcontractanti='@json($existingSubcontractanti)'
                >
                </subcontractanti-selector>
            </div>
        </div>
    </div>
@endif

<div id="datePicker" class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
    @if(in_array($proiectTip->slug, ['civile', 'apa-canal', 'drumuri', 'privati']))
        <div class="col-lg-6 mb-4">
            <label for="cu" class="mb-0 ps-3">CU</label>
            <input
                type="text"
                class="form-control bg-white rounded-3"
                name="cu"
                id="cu"
                value="{{ old('cu', $proiect->cu ?? '') }}">
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['apa-canal', 'drumuri', 'privati']))
        <div class="col-lg-6 mb-4">
            <label for="nr_proiect" class="mb-0 ps-3">Nr. proiect</label>
            <input
                type="text"
                class="form-control bg-white rounded-3"
                name="nr_proiect"
                id="nr_proiect"
                value="{{ old('nr_proiect', $proiect->nr_proiect ?? '') }}">
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['civile', 'apa-canal', 'drumuri', 'privati']))
        <div class="col-lg-6 mb-4">
            <label for="studii_teren" class="mb-0 ps-3">Studii Teren</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="studii_teren"
                id="studii_teren"
                rows="5">{{ old('studii_teren', $proiect->studii_teren ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['civile', 'apa-canal', 'drumuri', 'privati']))
        <div class="col-lg-6 mb-4">
            <label for="avize" class="mb-0 ps-3">Avize</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="avize"
                id="avize"
                rows="5">{{ old('avize', $proiect->avize ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['civile', 'apa-canal', 'drumuri', 'privati']))
        <div class="col-lg-6 mb-4">
            <label for="faza" class="mb-0 ps-3">FAZA</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="faza"
                id="faza"
                rows="5">{{ old('faza', $proiect->faza ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['civile', 'privati']))
        <div class="col-lg-6 mb-4">
            <label for="arhitectura" class="mb-0 ps-3">Arhitectură</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="arhitectura"
                id="arhitectura"
                rows="5">{{ old('arhitectura', $proiect->arhitectura ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['civile', 'apa-canal', 'privati']))
        <div class="col-lg-6 mb-4">
            <label for="rezistenta" class="mb-0 ps-3">Rezistență</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="rezistenta"
                id="rezistenta"
                rows="5">{{ old('rezistenta', $proiect->rezistenta ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['civile', 'privati']))
        <div class="col-lg-6 mb-4">
            <label for="instalatii" class="mb-0 ps-3">Instalații</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="instalatii"
                id="instalatii"
                rows="5">{{ old('instalatii', $proiect->instalatii ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['apa-canal']))
        <div class="col-lg-6 mb-4">
            <label for="tratare" class="mb-0 ps-3">Tratare</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="tratare"
                id="tratare"
                rows="5">{{ old('tratare', $proiect->tratare ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['apa-canal']))
        <div class="col-lg-6 mb-4">
            <label for="retele" class="mb-0 ps-3">Rețele</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="retele"
                id="retele"
                rows="5">{{ old('retele', $proiect->retele ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['drumuri']))
        <div class="col-lg-6 mb-4">
            <label for="partea_desenata" class="mb-0 ps-3">Partea desenată</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="partea_desenata"
                id="partea_desenata"
                rows="5">{{ old('partea_desenata', $proiect->partea_desenata ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['drumuri']))
        <div class="col-lg-6 mb-4">
            <label for="partea_scrisa" class="mb-0 ps-3">Partea scrisă</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="partea_scrisa"
                id="partea_scrisa"
                rows="5">{{ old('partea_scrisa', $proiect->partea_scrisa ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['civile', 'apa-canal', 'drumuri', 'privati']))
        <div class="col-lg-6 mb-4">
            <label for="partea_economica" class="mb-0 ps-3">Partea Economică</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="partea_economica"
                id="partea_economica"
                rows="5">{{ old('partea_economica', $proiect->partea_economica ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['civile', 'privati']))
        <div class="col-lg-6 mb-4">
            <label for="autorizatie_de_construire" class="mb-0 ps-3">Autorizație de Construire</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="autorizatie_de_construire"
                id="autorizatie_de_construire"
                rows="5">{{ old('autorizatie_de_construire', $proiect->autorizatie_de_construire ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['achizitii']))
        <div class="col-lg-6 mb-4">
            <label for="documentatie_eligibilitate" class="mb-0 ps-3">Documentație eligibilitate</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="documentatie_eligibilitate"
                id="documentatie_eligibilitate"
                rows="5">{{ old('documentatie_eligibilitate', $proiect->documentatie_eligibilitate ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['achizitii']))
        <div class="col-lg-6 mb-4">
            <label for="personal" class="mb-0 ps-3">Personal</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="personal"
                id="personal"
                rows="5">{{ old('personal', $proiect->personal ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['achizitii']))
        <div class="col-lg-6 mb-4">
            <label for="formulare" class="mb-0 ps-3">Formulare</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="formulare"
                id="formulare"
                rows="5">{{ old('formulare', $proiect->formulare ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['achizitii']))
        <div class="col-lg-6 mb-4">
            <label for="propunere_tehnica" class="mb-0 ps-3">Propunere tehnică</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="propunere_tehnica"
                id="propunere_tehnica"
                rows="5">{{ old('propunere_tehnica', $proiect->propunere_tehnica ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['achizitii']))
        <div class="col-lg-6 mb-4">
            <label for="propunere_financiara" class="mb-0 ps-3">Propunere financiară</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="propunere_financiara"
                id="propunere_financiara"
                rows="5">{{ old('propunere_financiara', $proiect->propunere_financiara ?? '') }}</textarea>
        </div>
    @endif

    @if(in_array($proiectTip->slug, ['achizitii']))
        <div class="col-lg-6 mb-4">
            <label for="stadiu_incarcare" class="mb-0 ps-3">Stadiu încărcare</label>
            <textarea
                class="form-control bg-white rounded-3"
                name="stadiu_incarcare"
                id="stadiu_incarcare"
                rows="5">{{ old('stadiu_incarcare', $proiect->stadiu_incarcare ?? '') }}</textarea>
        </div>
    @endif

    <div class="col-lg-12 mb-4">
        <label for="comentarii" class="mb-0 ps-3">Comentarii</label>
        <textarea
            class="form-control bg-white rounded-3"
            name="comentarii"
            id="comentarii"
            rows="5">{{ old('comentarii', $proiect->comentarii ?? '') }}</textarea>
    </div>

    <div class="col-lg-12 mb-4">
        <label for="observatii" class="mb-0 ps-3">Observații</label>
        <textarea
            class="form-control bg-white rounded-3"
            name="observatii"
            id="observatii"
            rows="5">{{ old('observatii', $proiect->observatii ?? '') }}</textarea>
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
