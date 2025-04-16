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

    <section class="layout-form">
        <div>
            <h1>Credential uitgeven</h1>

            <input value="{{ $issuanceUrl }}"/>
        </div>
    </section>
@endsection
