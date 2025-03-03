@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="shadow-lg" style="border-radius: 40px;">
                <div class="border border-secondary p-2 culoare2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-folder me-1"></i> Detalii Proiect
                    </span>
                </div>

                <div class="card-body border border-secondary p-4" style="border-radius: 0px 0px 40px 40px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Denumire Contract:</strong> {{ $proiect->denumire_contract }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Nr. Contract:</strong> {{ $proiect->nr_contract }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Data Contract:</strong> {{ $proiect->data_contract?->format('d.m.Y') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Data Limită Predare:</strong> {{ $proiect->data_limita_predare }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Nr. Proces Verbal Predare-Primire:</strong> {{ $proiect->nr_proces_verbal_predare_primire }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Data Proces Verbal Predare-Primire:</strong> {{ $proiect->data_proces_verbal_predare_primire?->format('d.m.Y') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Stare Contract:</strong> {{ $proiect->stare_contract }}
                        </div>

                        <!-- Membri Section -->
                        @if(in_array($tipProiect, ['civile', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>Membri</strong>
                                @if($proiect->membri->isNotEmpty())
                                    <ul class="list-group">
                                        @foreach($proiect->membri as $membru)
                                            <li class="list-group-item">
                                                {{ $loop->iteration }}.
                                                <a href="{{ $membru->path() }}" style="text-decoration: none;">
                                                    {{ $membru->nume }} {{ $membru->prenume }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">Nici un membru atașat.</p>
                                @endif
                            </div>
                        @endif

                        <!-- Subcontractanti Section -->
                        @if(in_array($tipProiect, ['civile', 'apa-canal', 'drumuri', 'privati', 'pug']))
                            <div class="col-md-6 mb-3">
                                <strong>Subcontractanti</strong>
                                @if($proiect->subcontractanti->isNotEmpty())
                                    <ul class="list-group">
                                        @foreach($proiect->subcontractanti as $subcontractant)
                                            <li class="list-group-item">
                                                {{ $loop->iteration }}.
                                                <a href="{{ $subcontractant->path() }}" style="text-decoration: none;">
                                                    {{ $subcontractant->nume }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">Nici un subcontractant atașat.</p>
                                @endif
                            </div>
                        @endif

                        @if(in_array($tipProiect, ['civile', 'apa-canal', 'drumuri', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>CU:</strong> {{ $proiect->cu }}
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['apa-canal', 'drumuri', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>Nr. proiect:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->nr_proiect }} </span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['civile', 'apa-canal', 'drumuri', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>Studii Teren:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->studii_teren }} </span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['civile', 'apa-canal', 'drumuri', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>Avize:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->avize }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['civile', 'apa-canal', 'drumuri', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>FAZA:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->faza }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['civile', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>Arhitectură:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->arhitectura }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['civile', 'apa-canal', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>Rezistență:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->rezistenta }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['civile', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>Instalații:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->instalatii }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['apa-canal']))
                            <div class="col-md-6 mb-3">
                                <strong>Tratare:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->tratare }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['apa-canal']))
                            <div class="col-md-6 mb-3">
                                <strong>Rețele:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->retele }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['drumuri']))
                            <div class="col-md-6 mb-3">
                                <strong>Partea desenată:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->partea_desenata }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['drumuri']))
                            <div class="col-md-6 mb-3">
                                <strong>Partea scrisă:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->partea_scrisa }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['civile', 'apa-canal', 'drumuri', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>Partea Economică:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->partea_economica }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['civile', 'privati']))
                            <div class="col-md-6 mb-3">
                                <strong>Autorizație de Construire:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->autorizatie_de_construire }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['achizitii']))
                            <div class="col-md-6 mb-3">
                                <strong>Documentație eligibilitate:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->documentatie_eligibilitate }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['achizitii']))
                            <div class="col-md-6 mb-3">
                                <strong>Personal:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->personal }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['achizitii']))
                            <div class="col-md-6 mb-3">
                                <strong>Formulare:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->formulare }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['achizitii']))
                            <div class="col-md-6 mb-3">
                                <strong>Propunere tehnică:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->propunere_tehnica }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['achizitii']))
                            <div class="col-md-6 mb-3">
                                <strong>Propunere financiară:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->propunere_financiara }}</span>
                            </div>
                        @endif
                        @if(in_array($tipProiect, ['achizitii']))
                            <div class="col-md-6 mb-3">
                                <strong>Stadiu încărcare:</strong>
                                <span style="white-space: pre-wrap;">{{ $proiect->stadiu_incarcare }}</span>
                            </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <strong>Observații:</strong>
                            <span style="white-space: pre-wrap;">{{ $proiect->observatii }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Creat la:</strong> {{ $proiect->created_at?->format('d.m.Y H:i') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Ultima modificare:</strong> {{ $proiect->updated_at?->format('d.m.Y H:i') }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('proiecte.edit', ['tipProiect' => $tipProiect, 'proiect' => $proiect->id]) }}" class="btn btn-primary text-white me-3 rounded-3">
                            <i class="fa-solid fa-edit"></i> Modifică
                        </a>
                        <a class="btn btn-secondary rounded-3" href="{{ Session::get('returnUrl', route('proiecte.index', $tipProiect)) }}">
                            <i class="fa-solid fa-arrow-left"></i> Înapoi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
