@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="border border-secondary p-2 culoare2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-handshake me-1"></i>
                        {{ isset($client) ? 'Editează Client' : 'Adaugă Client' }}
                    </span>
                </div>

                @include ('errors.errors')

                <div id="datePicker" class="card-body py-3 px-4 border border-secondary"
                    style="border-radius: 0px 0px 40px 40px;"
                >
                    <form class="needs-validation" novalidate
                          method="POST"
                          action="{{ isset($client) ? route('clienti.update', $client->id) : route('clienti.store') }}">
                        @csrf
                        @if(isset($client))
                            @method('PUT')
                        @endif

                        @include ('clienti.form', [
                            'client' => $client ?? null,
                            'buttonText' => isset($client) ? 'Salvează modificările' : 'Adaugă Client',
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
