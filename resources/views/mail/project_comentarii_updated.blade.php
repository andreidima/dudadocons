<div style="margin:0 auto;width:100%; background-color:#eff1f0;">
    <div style="margin:0 auto; max-width:800px!important; background-color: white;">

        @include ('mail.headerFooter.header')

        <div style="padding:20px 20px; max-width:760px!important;margin:0 auto; font-size:18px">
            @php
                $numeProiect = $proiect->denumire_contract ?? 'un proiect';
                $comentarii = $proiect->comentarii;
            @endphp

            <p>Bună,</p>

            <p>Comentariile au fost actualizate pentru proiectul <strong>{{ $numeProiect }}</strong>.</p>

            @if($comentarii)
                <p><strong>Comentarii noi:</strong></p>
                <blockquote style="border-left: 4px solid #ccc; padding-left: 10px;">
                    {!! nl2br(e($comentarii)) !!}
                </blockquote>
            @else
                <p>Comentariile au fost șterse.</p>
            @endif

            <p>Poți vizualiza proiectul aici: <a href="{{ $proiect->path() }}" target="_blank">{{ $proiect->path() }}</a></p>

            <p>Toate cele bune,<br>Echipa Alma Consulting</p>
        </div>
    </div>

    @include ('mail.headerFooter.footer')
</div>

