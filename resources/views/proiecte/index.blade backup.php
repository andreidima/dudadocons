@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-2">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-folder me-1"></i> {{ $proiectTip->nume }}
            </span>
        </div>

        {{-- Search form --}}
        <div class="col-lg-8">
            <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                @csrf
                <div class="row mb-1 custom-search-form justify-content-center">
                    <div class="col-lg-6">
                        <input type="text" class="form-control rounded-3" id="searchTemaDeProiectare" name="searchTemaDeProiectare" placeholder="Tema de proiectare" value="{{ $searchTemaDeProiectare }}">
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control rounded-3" id="searchContract" name="searchContract" placeholder="Contract" value="{{ $searchContract }}">
                    </div>
                    <div class="col-lg-3">
                        <input type="text" class="form-control rounded-3" id="searchClient" name="searchClient" placeholder="Client" value="{{ $searchClient }}">
                    </div>
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
                <i class="fas fa-plus-circle text-white me-1"></i> Adaugă proiect
            </a>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body px-0 py-3">

        @include ('errors.errors')

        @php
            $slots = [
                ['key'=>'dtac_arhitectura', 'label'=>'DTAC Arhitectură'],
                ['key'=>'dtac_structura',   'label'=>'DTAC Structură'],
                ['key'=>'dtac_instalatii',  'label'=>'DTAC Instalații'],
                ['key'=>'pth_arhitectura',  'label'=>'PTh Arhitectură'],
                ['key'=>'pth_structura',    'label'=>'PTh Structură'],
                ['key'=>'pth_instalatii',   'label'=>'PTh Instalații'],
            ];
        @endphp

        <div class="table-responsive rounded smaller-875rem">
            <table class="table table-sm table-striped table-border border-dark table-hover rounded">
                <thead class="text-white rounded">
                    <tr class="" style="">
                        <th rowspan="2" class="text-white culoare2">
                            <i class="fa-solid fa-hashtag small"></i>
                        </th>
                        <th rowspan="2" class="text-white culoare2 small">
                            <i class="fa-solid fa-clipboard-list"></i>
                            Tema de proiectare
                            <br>
                            <i class="fa-solid fa-file-contract"></i>
                            Contract
                        </th>
                        <th rowspan="2" class="text-white culoare2">
                            <i class="fa-solid fa-handshake"></i>
                            Clienți
                        </th>
                        <th colspan="3" class="text-white culoare2 text-center border">
                            DTAC
                        </th>
                        <th colspan="3" class="text-white culoare2 text-center border">
                            PTh
                        </th>
                        <th rowspan="2" class="text-white culoare2">
                            {{-- <i class="fa-solid fa-calendar-check"></i> --}}
                            Fișiere
                        </th>
                        <th rowspan="2" class="text-white culoare2 text-end">
                            {{-- <i class="fa-solid fa-cogs"></i> --}}
                            Acțiuni
                        </th>
                    </tr>
                    <tr class="" style="">
                        <th class="text-white culoare2 text-center border">
                            Arhitectură
                        </th>
                        <th class="text-white culoare2 text-center border">
                            Structură
                        </th>
                        <th class="text-white culoare2 text-center border">
                            Instalații
                        </th>
                        <th class="text-white culoare2 text-center border">
                            Arhitectură
                        </th>
                        <th class="text-white culoare2 text-center border">
                            Structură
                        </th>
                        <th class="text-white culoare2 text-center border">
                            Instalații
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proiecte as $proiect)
                        <tr>
                            <td>{{ ($proiecte->currentpage() - 1) * $proiecte->perpage() + $loop->index + 1 }}</td>
                            <td>
                                {{ $proiect->tema_de_proiectare ?? '-' }}
                                <br>
                                {{ $proiect->contract ?? '-' }}
                            <td>
                                @if($proiect->clienti->isNotEmpty())
                                    @foreach($proiect->clienti as $client)
                                        {{ $loop->iteration }}.
                                        <a href="{{ $client->path() }}" style="text-decoration: none;">
                                            {{ $client->nume }}
                                            {{-- @if ($client->pivot->observatii)
                                                - {{ $client->pivot->observatii }}
                                            @endif --}}
                                        </a>
                                        <br>
                                    @endforeach
                                @endif
                            </td>

                            @foreach($slots as $slot)
                                <td>
                                    <div class="">
                                        <ol class="m-0 ps-3">
                                            @foreach ($proiect->membri->filter(fn($m) => $m->pivot->tip === $slot['key']) as $membru)
                                                <li class="">
                                                    <div class="d-flex m-0 p-0 align-items-center">
                                                        <span class="me-2">
                                                            <a href="{{ $membru->path() }}" style="text-decoration: none;">
                                                                {{ $membru->nume }}
                                                            </a>
                                                        </span>

                                                        {{-- Edit button --}}
                                                        <button class="btn btn-xs btn-link py-0 px-1 edit-assignment"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#assignMembriModal"
                                                                data-action-url="{{ route('proiecte.membri.modifica', [
                                                                    'proiectTip'=> 'proiecte',
                                                                    'proiect'   => $proiect->id,
                                                                    'pivotMembruProiectId'   => $membru->pivot->id,
                                                                ]) }}"
                                                                data-proiect-id="{{ $proiect->id }}"
                                                                data-tip="{{ $slot['key'] }}"
                                                                data-observatii="{{ $membru->pivot->observatii }}"
                                                                data-modal-title="{{ $proiect->tema_de_proiectare }} - {{ $slot['label'] }} - Modifică membru"
                                                                data-pivot-membru-proiect-id="{{ $membru->pivot->id }}"
                                                                data-member-id="{{ $membru->id }}">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>

                                                        {{-- Delete button --}}
                                                        <button class="btn btn-xs btn-link p-0 text-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#confirmDeleteModal"
                                                                data-delete-url="{{ route('proiecte.membri.sterge', [
                                                                    'proiectTip'           => 'proiecte',
                                                                    'proiect'              => $proiect->id,
                                                                    'pivotMembruProiectId' => $membru->pivot->id,
                                                                ]) }}"
                                                                data-modal-title="{{ $proiect->tema_de_proiectare }} - {{ $slot['label'] }} - Șterge membru">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>

                                    {{-- Add button --}}
                                    <div class="text-center">
                                        <button class="btn btn-xs m-0 p-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#assignMembriModal"
                                            data-action-url="{{ route('proiecte.assign.membri', [
                                                // 'proiectTip'=> $proiectTip->id,
                                                'proiectTip'=> 'proiecte',
                                                'proiect'   => $proiect->id,
                                            ]) }}"
                                            data-proiect-id="{{ $proiect->id }}"
                                            data-tip="{{ $slot['key'] }}"
                                            data-modal-title="{{ $proiect->tema_de_proiectare }} - {{ $slot['label'] }} - Adaugă membru">
                                            <i class="fa-solid fa-square-plus text-success"></i>
                                        </button>
                                    </div>
                                </td>
                            @endforeach

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
                                <div class="d-flex justify-content-end mb-1">
                                    <a href="{{ $proiect->path() }}" class="btn btn-sm bg-success text-white py-0 px-1 me-1" title="Vizualizează Proiect">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ $proiect->path('edit') }}" class="btn btn-sm bg-primary text-white py-0 px-1" title="Modifică Proiect">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                </div>
                                <div class="d-flex justify-content-end">
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
                            <td colspan="11" class="text-center text-muted py-3">
                                <i class="fa-solid fa-exclamation-circle me-1"></i> Nu s-au găsit înregistrări în baza de date.
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

{{-- Modals for members --}}
<div class="modal fade" id="assignMembriModal" tabindex="-1" aria-labelledby="assignMembriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="assignForm" method="POST" action="">
        @csrf
        {{-- For PATCH in “edit” mode --}}
        <input type="hidden" name="_method" value="POST">

        {{-- Used only in edit mode to identify which pivot row --}}
        <input type="hidden" name="pivotMembruProiectId" value="">

        <input type="hidden" name="proiect_id" value="">
        <input type="hidden" name="tip"  value="">

        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white" id="assignMembriModalLabel">
                    {{-- Filled by JS --}}
                </h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="member_id" class="form-label">Membru</label>
                    <select name="member_id" id="member_id" class="form-select" required>
                    <option value="" disabled selected>Alege membrul…</option>
                    @foreach($membri as $membru)
                        <option value="{{ $membru->id }}">{{ $membru->nume }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="observatii" class="form-label">Observații</label>
                    <textarea name="observatii" id="observatii" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                <button type="submit" class="btn btn-success">
                    {{-- <i class="fa-solid fa-plus me-1"></i> Adaugă --}}
                    {{-- Filled by JS --}}
                </button>
            </div>
        </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // 1) grab your modal and its form
  var assignModal = document.getElementById('assignMembriModal');
  var assignForm  = document.getElementById('assignForm');

  assignModal.addEventListener('show.bs.modal', function(event) {
    var btn         = event.relatedTarget;                           // the button that opened the modal
    var isEdit      = btn.classList.contains('edit-assignment');     // edit vs add
    var actionUrl   = btn.getAttribute('data-action-url');           // route to post/patch to
    var proiectId   = btn.getAttribute('data-proiect-id');           // project id
    var tip         = btn.getAttribute('data-tip');                  // 'dtac_arhitectura', etc.
    var modalTitle  = btn.getAttribute('data-modal-title');          // full title text
    var methodInput = assignForm.querySelector('input[name="_method"]');
    var pivotInput  = assignForm.querySelector('input[name="pivotMembruProiectId"]');
    var projInput   = assignForm.querySelector('input[name="proiect_id"]');
    var tipInput    = assignForm.querySelector('input[name="tip"]');
    var memberSel   = assignForm.querySelector('select[name="member_id"]');
    var obsTextarea = assignForm.querySelector('textarea[name="observatii"]');
    var submitBtn   = assignForm.querySelector('button[type="submit"]');
    var titleEl     = assignModal.querySelector('#assignMembriModalLabel');

    // 2) common setup
    assignForm.action               = actionUrl;
    projInput.value                 = proiectId;
    tipInput.value                  = tip;
    titleEl.textContent             = modalTitle;

    if (isEdit) {
      // 3a) EDIT mode
      var pivotId    = btn.getAttribute('data-pivot-membru-proiect-id');
      var memberId   = btn.getAttribute('data-member-id');
      var observatii = btn.getAttribute('data-observatii') || '';

      methodInput.value            = 'PATCH';
      pivotInput.value             = pivotId;
      memberSel.value              = memberId;
      obsTextarea.value            = observatii;

      submitBtn.textContent        = 'Salvează';
      submitBtn.classList.remove('btn-success');
      submitBtn.classList.add('btn-primary');
    } else {
      // 3b) ADD mode
      methodInput.value            = 'POST';
      pivotInput.value             = '';
      memberSel.selectedIndex      = 0;
      obsTextarea.value            = '';

      submitBtn.textContent        = 'Adaugă';
      submitBtn.classList.remove('btn-primary');
      submitBtn.classList.add('btn-success');
    }
  });
});
</script>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="deleteForm" method="POST" action="">
      @csrf
      @method('DELETE')
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white" id="confirmDeleteModalLabel">Confirmă ștergerea</h5>
          <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Ești sigur că vrei să ștergi acest membru din proiect?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
          <button type="submit" class="btn btn-danger">Șterge</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var confirmModal = document.getElementById('confirmDeleteModal');
  var deleteForm   = document.getElementById('deleteForm');
  var titleEl      = confirmModal.querySelector('.modal-title');

  confirmModal.addEventListener('show.bs.modal', function(event) {
    var btn        = event.relatedTarget;
    var actionUrl  = btn.getAttribute('data-delete-url');
    var modalTitle = btn.getAttribute('data-modal-title');

    deleteForm.action       = actionUrl;
    titleEl.textContent     = modalTitle;
  });
});
</script>



{{-- Modals to delete projects --}}
@foreach ($proiecte as $proiect)
    <div class="modal fade text-dark" id="stergeProiect{{ $proiect->id }}" tabindex="-1" role="dialog" aria-labelledby="stergeProiectLabel{{ $proiect->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="stergeProiectLabel{{ $proiect->id }}">
                        <i class="fa-solid fa-trash me-1"></i> {{ $proiect->tema_de_proiectare }}
                    </h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    Ești sigur că vrei să ștergi această înregistrare?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                    <form method="POST" action="{{ $proiect->path('destroy') }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger text-white">
                            <i class="fa-solid fa-trash me-1"></i> Șterge
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
