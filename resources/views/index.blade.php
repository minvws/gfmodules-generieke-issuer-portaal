@extends('layouts.guest')

@section('content')
    @if (session()->has('error'))
        <section role="alert" class="error no-print" aria-label="{{ __('error') }}">
            <div>
                <h4>{{ session('error') }}</h4>
                <p>{{ session('error_description') }}</p>
            </div>
        </section>
    @endif

    <section>
        <div>
            <h1>Generieke VC Uitgever</h1>

            <p>Here be llamas...</p>

            <a href="{{ route('flow') }}">To the flow</a>
        </div>
    </section>
@endsection
