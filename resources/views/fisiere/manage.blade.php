@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="shadow-lg" style="border-radius: 40px;">
                    <!-- Header -->
                    <div class="border border-secondary p-2 culoare2" style="border-radius: 40px 40px 0 0;">
                        <span class="badge text-light fs-5">
                            <i class="fa-solid fa-folder-open me-1"></i>
                            Gestionare fișiere - {{ $owner->nume ?? $owner->denumire_contract }}
                        </span>
                    </div>

                    @include('errors.errors')

                    <div class="card-body py-3 px-4 border border-secondary" style="border-radius: 0 0 40px 40px;">

                        <!-- Existing Files List -->
                        <div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
                            <div class="col-lg-12">
                                <h5 class="mb-3">Fișiere încărcate</h5>
                                @if($fisiere->count())
                                    <ul class="list-group mb-4">
                                        @foreach($fisiere as $fisier)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="{{ route('fisiere.view', $fisier->id) }}" target="_blank" style="text-decoration: none;">
                                                    {{ $fisier->nume_fisier }}
                                                </a>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#stergeFisier{{ $fisier->id }}" class="btn btn-sm bg-danger text-white" title="Șterge Fișier">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">Nu există fișiere încărcate.</p>
                                @endif
                            </div>
                        </div>

                        <!-- File Upload Form -->
                        <div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem #e66800 solid; background-color:rgb(241, 250, 250)">
                            <div class="col-lg-12">
                                <h5 class="mb-3">Adaugă fișiere noi</h5>
                                <form method="POST" action="{{ route('fisiere.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Hidden fields to establish polymorphic association -->
                                    <input type="hidden" name="owner_type" value="{{ $ownerType ?? get_class($owner) }}">
                                    <input type="hidden" name="owner_id" value="{{ $owner->id }}">

                                    <div class="mb-3">
                                        <input type="file" name="fisiere[]" id="fisiere" multiple class="form-control">
                                        <small class="form-text text-muted ps-3">* Poți selecta unul sau mai multe fișiere.</small>
                                    </div>
                                    <div class="mb-4 text-center">
                                        <button type="submit" class="btn btn-primary text-white rounded-3">
                                            <i class="fa-solid fa-upload"></i> Upload Fișiere
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12 px-4 py-2 mb-0 text-center">
                                <a class="btn btn-secondary rounded-3" href="{{ Session::get('returnUrl', route('acasa')) }}">
                                    <i class="fa-solid fa-arrow-left"></i> Înapoi
                                </a>
                            </div>
                        </div>

                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col-lg-8 -->
        </div><!-- end row -->
    </div><!-- end container -->
@endsection

{{-- Modals to delete projects --}}
@foreach ($fisiere as $fisier)
    <div class="modal fade text-dark" id="stergeFisier{{ $fisier->id }}" tabindex="-1" role="dialog" aria-labelledby="stergeFisierLabel{{ $fisier->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="stergeFisierLabel{{ $fisier->id }}">
                        <i class="fa-solid fa-trash me-1"></i> Fișier: {{ $fisier->nume_fisier }}
                    </h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    Ești sigur că vrei să ștergi acest fișier?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>
                    <form action="{{ route('fisiere.destroy', $fisier->id) }}" method="POST" class="mb-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash me-1"></i> Șterge Fișier
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
