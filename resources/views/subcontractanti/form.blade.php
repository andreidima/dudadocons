@csrf
<div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
    <div class="col-lg-6 mb-4">
        <label for="nume" class="mb-0 ps-3">Nume<span class="text-danger">*</span></label>
        <input
            type="text"
            class="form-control bg-white rounded-3 {{ $errors->has('nume') ? 'is-invalid' : '' }}"
            name="nume"
            id="nume"
            value="{{ old('nume', $subcontractant->nume ?? '') }}"
            required>
    </div>

    <div class="col-lg-3 mb-4">
        <label for="tip" class="mb-0 ps-3">Tip</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="tip"
            id="tip"
            value="{{ old('tip', $subcontractant->tip ?? '') }}">
    </div>

    <div class="col-lg-3 mb-4">
        <label for="numar_inregistrare" class="mb-0 ps-3">Număr Înregistrare</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="numar_inregistrare"
            id="numar_inregistrare"
            value="{{ old('numar_inregistrare', $subcontractant->numar_inregistrare ?? '') }}">
    </div>

    <div class="col-lg-3 mb-4">
        <label for="cod_fiscal" class="mb-0 ps-3">Cod Fiscal</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="cod_fiscal"
            id="cod_fiscal"
            value="{{ old('cod_fiscal', $subcontractant->cod_fiscal ?? '') }}">
    </div>

    <div class="col-lg-3 mb-4">
        <label for="status" class="mb-0 ps-3">Status</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="status"
            id="status"
            value="{{ old('status', $subcontractant->status ?? '') }}">
    </div>

    <div class="col-lg-3 mb-4">
        <label for="email" class="mb-0 ps-3">Email</label>
        <input
            type="email"
            class="form-control bg-white rounded-3"
            name="email"
            id="email"
            autocomplete="email"
            value="{{ old('email', $subcontractant->email ?? '') }}">
    </div>

    <div class="col-lg-3 mb-4">
        <label for="telefon" class="mb-0 ps-3">Telefon</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="telefon"
            id="telefon"
            value="{{ old('telefon', $subcontractant->telefon ?? '') }}">
    </div>
</div>

<div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:rgb(241, 250, 250)">
    <div class="col-lg-6 mb-4">
        <label for="adresa" class="mb-0 ps-3">Adresă</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="adresa"
            id="adresa"
            value="{{ old('adresa', $subcontractant->adresa ?? '') }}">
    </div>

    <div class="col-lg-2 mb-4">
        <label for="oras" class="mb-0 ps-3">Oraș</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="oras"
            id="oras"
            value="{{ old('oras', $subcontractant->oras ?? '') }}">
    </div>

    <div class="col-lg-2 mb-4">
        <label for="cod_postal" class="mb-0 ps-3">Cod Postal</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="cod_postal"
            id="cod_postal"
            value="{{ old('cod_postal', $subcontractant->cod_postal ?? '') }}">
    </div>

    <div class="col-lg-2 mb-4">
        <label for="tara" class="mb-0 ps-3">Țară</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="tara"
            id="tara"
            value="{{ old('tara', $subcontractant->tara ?? '') }}">
    </div>

    <div class="col-lg-2 mb-4 text-center">
        <label for="data_inceput_contract" class="mb-0 ps-0">Data Început Contract</label>
        <vue-datepicker-next
            id="data_inceput_contract"
            data-veche="{{ old('data_inceput_contract', $subcontractant->data_inceput_contract ?? null) }}"
            nume-camp-db="data_inceput_contract"
            tip="date"
            value-type="YYYY-MM-DD"
            format="DD.MM.YYYY"
            :latime="{ width: '125px' }"
        ></vue-datepicker-next>
    </div>

    <div class="col-lg-2 mb-4 text-center">
        <label for="data_sfarsit_contract" class="mb-0 ps-0">Data Sfârșit Contract</label>
        <vue-datepicker-next
            id="data_sfarsit_contract"
            data-veche="{{ old('data_sfarsit_contract', $subcontractant->data_sfarsit_contract ?? null) }}"
            nume-camp-db="data_sfarsit_contract"
            tip="date"
            value-type="YYYY-MM-DD"
            format="DD.MM.YYYY"
            :latime="{ width: '125px' }"
        ></vue-datepicker-next>
    </div>

    <div class="col-lg-2 mb-4">
        <label for="tarif_orar" class="mb-0 ps-3">Tarif Orar</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="tarif_orar"
            id="tarif_orar"
            value="{{ old('tarif_orar', $subcontractant->tarif_orar ?? '') }}">
    </div>

    <div class="col-lg-2 mb-4">
        <label for="pret_fix" class="mb-0 ps-3">Preț Fix</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="pret_fix"
            id="pret_fix"
            value="{{ old('pret_fix', $subcontractant->pret_fix ?? '') }}">
    </div>

    <div class="col-lg-2 mb-4">
        <label for="moneda" class="mb-0 ps-3">Monedă</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="moneda"
            id="moneda"
            value="{{ old('moneda', $subcontractant->moneda ?? '') }}">
    </div>
</div>

<div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
    <div class="col-lg-6 mb-4">
        <label for="conditii_plata" class="mb-0 ps-3">Condiții Plată</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="conditii_plata"
            id="conditii_plata"
            value="{{ old('conditii_plata', $subcontractant->conditii_plata ?? '') }}">
    </div>

    <div class="col-lg-6 mb-4">
        <label for="specializare" class="mb-0 ps-3">Specializare</label>
        <input
            type="text"
            class="form-control bg-white rounded-3"
            name="specializare"
            id="specializare"
            value="{{ old('specializare', $subcontractant->specializare ?? '') }}">
    </div>

    <div class="col-lg-12 mb-4">
        <label for="observatii" class="mb-0 ps-3">Observații</label>
        <textarea
            class="form-control bg-white rounded-3"
            id="observatii"
            rows="5">{{ old('observatii', $subcontractant->observatii ?? '') }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mb-2 d-flex justify-content-center">
        <button type="submit" class="btn btn-primary text-white me-3 rounded-3">
            <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
        </button>
        <a class="btn btn-secondary rounded-3" href="{{ Session::get('returnUrl', route('subcontractanti.index')) }}">
            Renunță
        </a>
    </div>
</div>
