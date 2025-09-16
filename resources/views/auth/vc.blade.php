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
            <h1>@lang('VC Login - Present credential')</h1>

            <input readonly value="{{ $vpUrl }}"/>

            <h2>Inladen in een wallet</h2>

            <form class="horizontal-view" id="present-credential-form">
                <input type="hidden" name="present_credential_uri" value="{{ $vpUrl }}">
                <div>
                    <label for="wallet-url">Wallet URL</label>
                    <div>
                        <input id="wallet-url" name="wallet_url" type="text" required>
                    </div>
                </div>
                <button type="submit">Naar wallet</button>
            </form>

            <ul class="accordion">
                <li>
                    <button aria-expanded="false" id="debug-accordion">
                        Debug
                    </button>
                    <div aria-labelledby="debug-accordion">
                        @env('local')
                            <p>Lokale wallet URL: http://localhost:8562/api/siop/initiatePresentation</p>
                        @endenv
                    </div>
                </li>
            </ul>
        </div>
    </section>
@endsection
