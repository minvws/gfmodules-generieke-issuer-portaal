@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'error']) }} role="group" aria-label="{{__('Error') }}">
        <span>{{__('Error') }}:</span>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
