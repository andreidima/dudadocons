@extends('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0 0;">
        <div class="col-lg-2">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-envelope me-1"></i>
                {{ $proiectTip->nume }}: {{ $proiect->denumire_contract }} - Emailuri trimise către:
                @if($destinatar_type === 'membru')
                    @php
                       $membru = App\Models\Membru::findOrFail($destinatar_id)
                    @endphp
                    {{ $membru->nume ?? '' }}
                @elseif($destinatar_type === 'subcontractant')
                    @php
                       $subcontractant = App\Models\Subcontractant::findOrFail($destinatar_id)
                    @endphp
                    {{ $subcontractant->nume ?? ''}}
                @endif
            </span>
        </div>
        <div class="col-lg-8">
            <h5 class="mb-0">
            </h5>
        </div>
    </div>

    <div class="card-body px-0 py-3">
        @include('errors.errors')

        <div class="table-responsive rounded">
            <table class="table table-sm table-striped table-hover rounded">
                <thead class="text-white rounded">
                    <tr class="thead-danger" style="padding:2rem">
                        <th class="text-white culoare2"><i class="fa-solid fa-hashtag"></i></th>
                        <th class="text-white culoare2">Email destinatar</th>
                        <th class="text-white culoare2">Subiect</th>
                        <th class="text-white culoare2">Mesaj</th>
                        <th class="text-white culoare2">Data trimiterii</th>
                        <th class="text-white culoare2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($emailuri as $email)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $email->email_destinatar }}</td>
                            <td>{{ $email->email_subiect }}</td>
                            <td>{{ $email->email_mesaj }}</td>
                            <td>{{ $email->sent_at->format('d.m.Y H:i') }}</td>
                            <td>
                                @if($email->error_code)
                                    <span class="text-danger" title="Cod eroare: {{ $email->error_code }}; Mesaj: {{ $email->error_message }}">
                                        <i class="fa-solid fa-exclamation-circle"></i> Eroare
                                    </span>
                                @else
                                    <span class="text-success">
                                        <i class="fa-solid fa-check"></i> Trimis
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fa-solid fa-exclamation-circle me-1"></i> Nu s-au găsit emailuri trimise.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <a class="btn btn-secondary rounded-3" href="{{ Session::get('returnUrl', route('proiecte.index', $proiectTip->slug )) }}">
                <i class="fa-solid fa-arrow-left"></i> Înapoi
            </a>
        </div>
    </div>
</div>
@endsection
