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

            <input value="{{ $issuanceUrl }}" readonly/>

            <h2>Inladen in een wallet</h2>

            <form class="horizontal-view" id="load-credential-into-wallet-form">
                <input type="hidden" name="credential_offer_uri" value="{{ $credentialOfferUri }}">
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
                        <p>Credential Offer URI:<a href="{{ $credentialOfferUri }}">{{ $credentialOfferUri }}</a></p>
                        @env('local')
                            <p>Lokale wallet URL: {{ env('LOCAL_WALLET_URL') }}/api/siop/initiateIssuance</p>
                        @endenv
                    </div>
                </li>
            </ul>
        </div>
    </section>
@endsection
