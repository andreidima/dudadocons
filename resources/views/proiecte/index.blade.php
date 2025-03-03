@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-2">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-folder me-1"></i> Proiecte
            </span>
        </div>

        {{-- Search form --}}
        <div class="col-lg-8">
            <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                @csrf
                <div class="row mb-1 custom-search-form justify-content-center">
                    <div class="col-lg-6">
                        <input type="text" class="form-control rounded-3" id="searchDenumire" name="searchDenumire" placeholder="Denumire contract" value="{{ $searchDenumire }}">
                    </div>
                    <div id="datePicker" class="col-lg-6 d-flex align-items-center">
                        <label for="searchIntervalDataContract" class="mb-0 ps-3">Data&nbsp;Contract:&nbsp;</label>
                        <vue-datepicker-next
                            id="searchIntervalDataContract"
                            data-veche="{{ $searchIntervalDataContract }}"
                            nume-camp-db="searchIntervalDataContract"
                            tip="date"
                            range="range"
                            value-type="YYYY-MM-DD"
                            format="DD.MM.YYYY"
                            :latime="{ width: '210px' }"
                        ></vue-datepicker-next>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control rounded-3" id="searchNrContract" name="searchNrContract" placeholder="Nr. Contract" value="{{ $searchNrContract }}">
                    </div>
                    @if(in_array($tipProiect, ['civile', 'privati']))
                        <div class="col-lg-4">
                            <input type="text" class="form-control rounded-3" id="searchMembru" name="searchMembru" placeholder="Membru" value="{{ $searchMembru }}">
                        </div>
                    @endif
                    @if(in_array($tipProiect, ['civile', 'apa-canal', 'drumuri', 'privati', 'pug']))
                        <div class="col-lg-4">
                            <input type="text" class="form-control rounded-3" id="searchSubcontractant" name="searchSubcontractant" placeholder="Subcontractant" value="{{ $searchSubcontractant }}">
                        </div>
                    @endif
                </div>
                <div class="row custom-search-form justify-content-center">
                    <div class="col-lg-4">
                        <button class="btn btn-sm w-100 btn-primary text-white border border-dark rounded-3" type="submit">
                            <i class="fas fa-search text-white me-1"></i>Caută
                        </button>
                    </div>
                    <div class="col-lg-4">
                        <a class="btn btn-sm w-100 btn-secondary text-white border border-dark rounded-3" href="{{ url()->current() }}" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Button to add new project --}}
        <div class="col-lg-2 text-end">
            <a class="btn btn-sm btn-success text-white border border-dark rounded-3 col-md-8" href="{{ url()->current() }}/adauga" role="button">
                <i class="fas fa-plus-circle text-white me-1"></i> Adaugă Proiect
            </a>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body px-0 py-3">

        @include ('errors.errors')

        <div class="table-responsive rounded">
            <table class="table table-sm table-striped table-hover rounded">
                <thead class="text-white rounded">
                    <tr class="thead-danger" style="padding:2rem">
                        <th class="text-white culoare2"><i class="fa-solid fa-hashtag"></i></th>
                        <th class="text-white culoare2">
                            <i class="fa-solid fa-hashtag"></i> Nr. Contract
                            <br>
                            <i class="fa-solid fa-file-contract"></i> Denumire contract</th>
                        <th class="text-white culoare2">
                            <i class="fa-solid fa-calendar-alt"></i> Data Contract
                            <br>
                            <i class="fa-solid fa-calendar-check"></i> Data limită predare
                        </th>
                        @if(in_array($tipProiect, ['civile', 'privati']))
                            <th class="text-white culoare2"><i class="fa-solid fa-user-group"></i> Membri</th>
                        @endif
                        @if(in_array($tipProiect, ['civile', 'apa-canal', 'drumuri', 'privati', 'pug']))
                            <th class="text-white culoare2"><i class="fa-solid fa-handshake"></i> Subcontractanți</th>
                        @endif
                        <th class="text-white culoare2"><i class="fa-solid fa-calendar-check"></i> Fișiere</th>
                        <th class="text-white culoare2 text-end"><i class="fa-solid fa-cogs"></i> Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proiecte as $proiect)
                        <tr>
                            <td>{{ ($proiecte->currentpage() - 1) * $proiecte->perpage() + $loop->index + 1 }}</td>
                            <td>
                                {{ $proiect->nr_contract ?? '-' }}
                                <br>
                                {{ $proiect->denumire_contract }}</td>
                            <td>
                                {{ $proiect->data_contract?->format('d.m.Y') ?? '-' }}
                                <br>
                                {{ $proiect->data_limita_predare ?? '-' }}
                            </td>
                            @if(in_array($tipProiect, ['civile', 'privati']))
                                <td>
                                    @if($proiect->membri->isNotEmpty())
                                        @foreach($proiect->membri as $membru)
                                            {{ $loop->iteration }}.
                                            <a href="{{ $membru->path() }}" style="text-decoration: none;">
                                                {{ $membru->nume }} {{ $membru->prenume }}
                                            </a>
                                            <!-- Email icon for member -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#emailModal_{{ $proiect->id }}_membru_{{ $membru->id }}" title="Trimite Email">
                                                <i class="fa-solid fa-envelope text-info"></i></a>

                                            @if ($proiect->emailuriTrimise)
                                                @php
                                                    $emailCount = $proiect->emailuriTrimise
                                                        ->where('destinatar_id', $membru->id)
                                                        ->where('destinatar_type', 'membru')
                                                        ->count();
                                                @endphp
                                                @if ($emailCount > 0)
                                                    <a href="{{ route('proiecte.show.emailuri', [
                                                        'tipProiect' => $tipProiect,
                                                        'proiect' => $proiect->id,
                                                        'destinatar_type' => 'membru',
                                                        'destinatar_id' => $membru->id
                                                    ]) }}" style="text-decoration: none;">
                                                        (<span title="Afișează emailurile trimise">{{ $emailCount }}</span>)
                                                    </a>
                                                @endif
                                            @endif
                                            <br>
                                        @endforeach
                                    @endif
                                </td>
                            @endif
                            @if(in_array($tipProiect, ['civile', 'apa-canal', 'drumuri', 'privati', 'pug']))
                                <td>
                                    @if($proiect->subcontractanti->isNotEmpty())
                                        @foreach($proiect->subcontractanti as $subcontractant)
                                            {{ $loop->iteration }}.
                                            <a href="{{ $subcontractant->path() }}" style="text-decoration: none;">
                                                {{ $subcontractant->nume }}</a>
                                            <!-- Email icon for subcontractant with yellow color -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#emailModal_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}" title="Trimite Email">
                                                <i class="fa-solid fa-envelope text-info ms-1"></i></a>

                                            @if ($proiect->emailuriTrimise)
                                                @php
                                                    $emailCount = $proiect->emailuriTrimise
                                                        ->where('destinatar_id', $subcontractant->id)
                                                        ->where('destinatar_type', 'subcontractant')
                                                        ->count();
                                                @endphp
                                                @if ($emailCount > 0)
                                                    <a href="{{ route('proiecte.show.emailuri', [
                                                        'tipProiect' => $tipProiect,
                                                        'proiect' => $proiect->id,
                                                        'destinatar_type' => 'subcontractant',
                                                        'destinatar_id' => $subcontractant->id
                                                    ]) }}" style="text-decoration: none;">
                                                        (<span title="Afișează emailurile trimise">{{ $emailCount }}</span>)
                                                    </a>
                                                @endif
                                            @endif
                                            <br>
                                        @endforeach
                                    @endif
                                </td>
                            @endif
                            <td>
                                @if($proiect->fisiere->isNotEmpty())
                                    @foreach($proiect->fisiere as $fisier)
                                        {{ $loop->iteration }}.
                                        <a href="{{ route('fisiere.view', $fisier->id) }}" target="_blank" style="text-decoration: none;">
                                            {{ $fisier->nume_fisier }}
                                        </a>
                                        <br>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ $proiect->path() }}" class="btn btn-sm bg-success text-white py-0 px-1 me-1" title="Vizualizează Proiect">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ $proiect->path('edit') }}" class="btn btn-sm bg-primary text-white py-0 px-1 me-1" title="Modifică Proiect">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <a href="{{ route('fisiere.manage', ['owner_type' => 'proiect', 'owner_id' => $proiect->id]) }}" class="btn btn-sm bg-warning py-0 px-1 me-1" title="Gestionează Fișiere">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#stergeProiect{{ $proiect->id }}" class="btn btn-sm bg-danger text-white py-0 px-1" title="Șterge Proiect">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                <i class="fa-solid fa-exclamation-circle me-1"></i> Nu s-au găsit proiecte în baza de date.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                {{ $proiecte->appends(Request::except('page'))->links() }}
            </ul>
        </nav>
    </div>
</div>

{{-- Email Modals --}}
<div id="sendEmailModals">
@foreach ($proiecte as $proiect)
    @if($proiect->membri->isNotEmpty())
        @foreach($proiect->membri as $membru)
            <!-- Email Modal for member -->
            <div class="modal fade text-dark" id="emailModal_{{ $proiect->id }}_membru_{{ $membru->id }}" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel_{{ $proiect->id }}_membru_{{ $membru->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title text-white" id="emailModalLabel_{{ $proiect->id }}_membru_{{ $membru->id }}">
                                Trimite Email către {{ $membru->nume }} {{ $membru->prenume }}
                            </h5>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Închide"></button>
                        </div>
                        <form action="{{ route('email.send') }}" method="POST">
                            @csrf
                            <input type="hidden" name="openModal" value="emailModal_{{ $proiect->id }}_membru_{{ $membru->id }}">
                            <input type="hidden" name="proiect_id" value="{{ $proiect->id }}">
                            <input type="hidden" name="destinatar_id" value="{{ $membru->id }}">
                            <input type="hidden" name="destinatar_type" value="membru">
                            <div class="modal-body text-start">
                                @if (old('openModal') == "emailModal_{$proiect->id}_membru_{$membru->id}")
                                    <div class="">
                                        @include('errors.errors')
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label for="recipient-email_{{ $proiect->id }}_membru_{{ $membru->id }}" class="form-label">Email destinatar</label>
                                    <input type="email" class="form-control" id="recipient-email_{{ $proiect->id }}_membru_{{ $membru->id }}" name="email_destinatar" value="{{ $membru->email }}">
                                </div>
                                <div class="mb-3">
                                    <label for="email-subject_{{ $proiect->id }}_membru_{{ $membru->id }}" class="form-label">Subiect</label>
                                    <input type="text" class="form-control" id="email-subject_{{ $proiect->id }}_membru_{{ $membru->id }}" name="email_subiect" placeholder="Subiect email">
                                </div>
                                <div class="mb-3">
                                    <label for="email-message_{{ $proiect->id }}_membru_{{ $membru->id }}" class="form-label">Mesaj</label>
                                    <textarea class="form-control" id="email-message_{{ $proiect->id }}_membru_{{ $membru->id }}" name="email_mesaj" rows="5">Bună {{ $membru->nume }} {{ $membru->prenume }},


Cu stimă,
Alma Consulting</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Închide</button>
                                <disable-once-button
                                    class="btn btn-primary"
                                    type="submit"
                                    processing-text="Se procesează cererea..."
                                    :hide-on-click="false"
                                >
                                    Trimite Email
                                </disable-once-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if($proiect->subcontractanti->isNotEmpty())
        @foreach($proiect->subcontractanti as $subcontractant)
            <!-- Email Modal for subcontractant -->
            <div class="modal fade text-dark" id="emailModal_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title text-white" id="emailModalLabel_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}">
                                Trimite Email către {{ $subcontractant->nume }}
                            </h5>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Închide"></button>
                        </div>
                        <form action="{{ route('email.send') }}" method="POST">
                            @csrf
                            <input type="hidden" name="openModal" value="emailModal_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}">
                            <input type="hidden" name="proiect_id" value="{{ $proiect->id }}">
                            <input type="hidden" name="destinatar_id" value="{{ $subcontractant->id }}">
                            <input type="hidden" name="destinatar_type" value="subcontractant">
                            <div class="modal-body text-start">
                                @if(old('openModal') == "emailModal_{$proiect->id}_subcontractant_{$subcontractant->id}")
                                    <div class="">
                                        @include('errors.errors')
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label for="recipient-email_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}" class="form-label">Email destinatar</label>
                                    <input type="email" class="form-control" id="recipient-email_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}" name="email_destinatar" value="{{ $subcontractant->email }}">
                                </div>
                                <div class="mb-3">
                                    <label for="email-subject_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}" class="form-label">Subiect</label>
                                    <input type="text" class="form-control" id="email-subject_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}" name="email_subiect" placeholder="Subiect email">
                                </div>
                                <div class="mb-3">
                                    <label for="email-message_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}" class="form-label">Mesaj</label>
                                    <textarea class="form-control" id="email-message_{{ $proiect->id }}_subcontractant_{{ $subcontractant->id }}" name="email_mesaj" rows="5">Bună {{ $subcontractant->nume }},

Cu stimă,
Alma Consulting</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Închide</button>
                                <disable-once-button
                                    class="btn btn-primary"
                                    type="submit"
                                    processing-text="Se procesează cererea..."
                                    :hide-on-click="false"
                                >
                                    Trimite Email
                                </disable-once-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endforeach
</div>
@if (old('openModal'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalElement = document.getElementById("{{ old('openModal') }}");
            if(modalElement) {
                var modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        });
    </script>
@endif

{{-- Modals to delete projects --}}
@foreach ($proiecte as $proiect)
    <div class="modal fade text-dark" id="stergeProiect{{ $proiect->id }}" tabindex="-1" role="dialog" aria-labelledby="stergeProiectLabel{{ $proiect->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="stergeProiectLabel{{ $proiect->id }}">
                        <i class="fa-solid fa-trash me-1"></i> Proiect: <b>{{ $proiect->denumire_contract }}</b>
                    </h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    Ești sigur că vrei să ștergi acest proiect?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                    <form method="POST" action="{{ $proiect->path('destroy') }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger text-white">
                            <i class="fa-solid fa-trash me-1"></i> Șterge Proiect
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
