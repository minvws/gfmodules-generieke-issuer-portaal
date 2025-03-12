@if(config('app.env') === "production")
<a href="/" class="ro-logo" aria-label="{{__('Rijksoverheid logo, go to the homepage')}}">
    <img src="{{ asset('img/ro-logo.svg') }}" alt="Logo Rijksoverheid">Rijksoverheid
</a>
@else
<a href="/" class="ro-logo" aria-label="{{__('Rijksoverheid logo, go to the homepage')}}">
    <img src="{{ asset('img/llama-dos.png') }}" alt="Logo Rijksoverheid">Ministry of Llamas
</a>
@endif
