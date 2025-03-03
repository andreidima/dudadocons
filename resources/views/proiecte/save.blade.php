@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="border border-secondary p-2 culoare2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-folder-{{ isset($proiect) ? 'edit' : 'plus' }} me-1"></i>
                        {{ isset($proiect) ? 'Editează Proiect' : 'Adaugă Proiect' }}
                    </span>
                </div>

                @include ('errors.errors')

                <div class="card-body py-3 px-4 border border-secondary" style="border-radius: 0px 0px 40px 40px;">
                    <form class="needs-validation" novalidate
                        method="POST"
                        action="{{ isset($proiect) ? route('proiecte.update', ['tipProiect' => $tipProiect, 'proiect' => $proiect->id]) : route('proiecte.store', $tipProiect) }}">
                        @csrf
                        @if(isset($proiect))
                            @method('PUT')
                        @endif

                        @include ('proiecte.form', [
                            'proiect' => $proiect ?? null,
                            'buttonText' => isset($proiect) ? 'Salvează modificările' : 'Adaugă Proiect',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
