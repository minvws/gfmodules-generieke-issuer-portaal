@if (session()->has('success'))
<section
    role="alert"
    class="confirmation no-print"
    role="group"
    aria-label="{{__('confirmation') }}">
    <div>
        <p><span>{{__('Confirmation') }}:</span> {{ session('success') }}</p>
    </div>
</section>
@endif

@if (session()->has('error'))
<section role="alert" class="error no-print" aria-label="{{__('error') }}">
    <div>
        <p><span>{{__('Error') }}:</span> {{ session('error') }}</p>
    </div>
</section>
@endif

