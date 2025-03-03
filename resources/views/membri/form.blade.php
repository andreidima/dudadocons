<div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
    <div class="col-lg-6 mb-4">
        <label for="prenume" class="mb-0 ps-3">Prenume<span class="text-danger">*</span></label>
        <input
            type="text"
            class="form-control bg-white rounded-3 {{ $errors->has('prenume') ? 'is-invalid' : '' }}"
            name="prenume"
            id="prenume"
            value="{{ old('prenume', $preFilledFields['prenume'] ?? $membru->prenume ?? '') }}"
            required>
    </div>

    <div class="col-lg-6 mb-4">
        <label for="nume" class="mb-0 ps-3">Nume<span class="text-danger">*</span></label>
        <input
            type="text"
            class="form-control bg-white rounded-3 {{ $errors->has('nume') ? 'is-invalid' : '' }}"
            name="nume"
            id="nume"
            value="{{ old('nume', $preFilledFields['nume'] ?? $membru->nume ?? '') }}"
            required>
    </div>

    <div class="col-lg-6 mb-4">
        <label for="email" class="mb-0 ps-3">Email</label>
        <input
            type="email"
            class="form-control bg-white rounded-3 {{ $errors->has('email') ? 'is-invalid' : '' }}"
            name="email"
            id="email"
            autocomplete="email"
            value="{{ old('email', $preFilledFields['email'] ?? $membru->email ?? '') }}">
    </div>

    <div class="col-lg-6 mb-4">
        <label for="telefon" class="mb-0 ps-3">Telefon</label>
        <input
            type="text"
            class="form-control bg-white rounded-3 {{ $errors->has('telefon') ? 'is-invalid' : '' }}"
            name="telefon"
            id="telefon"
            value="{{ old('telefon', $preFilledFields['telefon'] ?? $membru->telefon ?? '') }}">
    </div>

    <div class="col-lg-6 mb-4">
        <label for="functie" class="mb-0 ps-3">Funcție</label>
        <input
            type="text"
            class="form-control bg-white rounded-3 {{ $errors->has('functie') ? 'is-invalid' : '' }}"
            name="functie"
            id="functie"
            value="{{ old('functie', $preFilledFields['functie'] ?? $membru->functie ?? '') }}">
    </div>

    <div class="col-lg-6 mb-4">
        <label for="departament" class="mb-0 ps-3">Departament</label>
        <input
            type="text"
            class="form-control bg-white rounded-3 {{ $errors->has('departament') ? 'is-invalid' : '' }}"
            name="departament"
            id="departament"
            value="{{ old('departament', $preFilledFields['departament'] ?? $membru->departament ?? '') }}">
    </div>

    <div class="col-lg-12 mb-4">
        <label for="adresa" class="mb-0 ps-3">Adresă</label>
        <input
            type="text"
            class="form-control bg-white rounded-3 {{ $errors->has('adresa') ? 'is-invalid' : '' }}"
            name="adresa"
            id="adresa"
            value="{{ old('adresa', $preFilledFields['adresa'] ?? $membru->adresa ?? '') }}">
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
        <a class="btn btn-secondary rounded-3" href="{{ Session::get('returnUrl', route('membri.index')) }}">
            Renunță
        </a>
    </div>
</div>
