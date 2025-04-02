@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="shadow-lg" style="border-radius: 40px;">
                <div class="border border-secondary p-2 culoare2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-user-group me-1"></i> Detalii Membru
                    </span>
                </div>

                <div class="card-body border border-secondary p-4" style="border-radius: 0px 0px 40px 40px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Nume:</strong> {{ $membru->nume }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Email:</strong> <a href="mailto:{{ $membru->email }}">{{ $membru->email }}</a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Telefon:</strong> {{ $membru->telefon }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Funcție:</strong> {{ $membru->functie }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Departament:</strong> {{ $membru->departament }}
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong>Adresă:</strong> {{ $membru->adresa }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Creat la:</strong> {{ $membru->created_at?->format('d.m.Y H:i') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Ultima modificare:</strong> {{ $membru->updated_at?->format('d.m.Y H:i') }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('membri.edit', $membru->id) }}" class="btn btn-primary text-white me-3 rounded-3">
                            <i class="fa-solid fa-edit me-1"></i> Modifică
                        </a>
                        <a class="btn btn-secondary rounded-3" href="{{ Session::get('returnUrl', route('membri.index')) }}">
                            <i class="fa-solid fa-arrow-left me-1"></i> Înapoi
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
