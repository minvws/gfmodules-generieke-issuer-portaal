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

            <ul class="accordion">
                <li>
                    <button aria-expanded="{{ !$state->getCredentialData() ? "true" : "false" }}"
                            id="flow-identification-authentication">1. Identificatie en Authenticatie
                    </button>
                    <div aria-labelledby="flow-identification-authentication">
                        @if(!$state->getUser())
                            <ul class="external-login">
                                <li>
                                    <a href="{{ route('oidc.login') }}">
                                        <img src="{{ asset('img/signin-method-logo.png') }}" alt="" rel="external">
                                        @lang('Login with') Dezi-online
                                    </a>
                                </li>
                            </ul>
                        @else
                            <p>Je bent ingelogd als: {{ $state->getUser()->getName() }}</p>
                        @endif
                    </div>
                </li>
                <li>
                    <button aria-expanded="{{ $state->getUser() ? "true" : "false" }}" id="flow-credential">2.
                        Credential
                    </button>
                    <div aria-labelledby="flow-credential">
                        @if(!$state->getCredentialData() || $editCredential)
                            <form action="{{ route('flow-credential.store') }}" method="POST">
                                @csrf
                                <fieldset {{ !$state->getUser() ? "disabled" : "" }}>
                                    <p>Controleer of u toestemming heeft om de gegevens van de patient/client of burger
                                        op te vragen.</p>
                                    <div>
                                        <label for="flow-consent-bsn">Burgerservicenummer</label>
                                        <span
                                            class="nota-bene">BSN van de persoon waarvan u de gegevens op wilt vragen</span>
                                        <div>
                                            @error('bsn')
                                            <p class="error" id="flow-consent-bsn-error-message">
                                                <span>Foutmelding:</span> {{ $message }}
                                            </p>
                                            @enderror
                                            <input
                                                id="flow-consent-bsn"
                                                name="bsn"
                                                type="text"
                                                minlength="8"
                                                maxlength="9"
                                                required
                                                aria-describedby="flow-consent-bsn-error-message"
                                                value="{{ old('bsn', $state->getCredentialData()?->getBsn()) }}"
                                            />
                                        </div>
                                    </div>
                                    {{--                                <div>--}}
                                    {{--                                    <label for="flow-consent-birthyear">Geboortejaar</label>--}}
                                    {{--                                    <span class="nota-bene">JJJJ</span>--}}
                                    {{--                                    <div>--}}
                                    {{--                                        @error('birthyear')--}}
                                    {{--                                        <p class="error" id="flow-consent-birthyear-error-message">--}}
                                    {{--                                            <span>Foutmelding:</span> {{ $message }}--}}
                                    {{--                                        </p>--}}
                                    {{--                                        @enderror--}}
                                    {{--                                        <input--}}
                                    {{--                                            id="flow-consent-birthyear"--}}
                                    {{--                                            name="birthyear"--}}
                                    {{--                                            type="number"--}}
                                    {{--                                            required--}}
                                    {{--                                            aria-describedby="flow-consent-birthyear-error-message"--}}
                                    {{--                                            value="{{ old('birthyear', $state->getConsentData()?->getBirthYear()) }}"--}}
                                    {{--                                        />--}}
                                    {{--                                    </div>--}}
                                    {{--                                </div>--}}
                                    <div>
                                        <label for="flow-consent-consent">Behandelrelatie</label>
                                        <div>
                                            <p class="warning" id="flow-consent-consent-warning-message">
                                                <span>Waarschuwing:</span> De behandelrelatie wordt steeksproefsgewijs
                                                gecontroleerd. Indien er geen sprake blijkt te zijn van een geldige
                                                relatie kan dit tot royement leiden.
                                            </p>
                                            <div>
                                                @error('consent')
                                                <p class="error" id="flow-consent-consent-error-message">
                                                    <span>Foutmelding:</span> {{ $message }}
                                                </p>
                                                @enderror
                                                <div class="checkbox">
                                                    <input type="checkbox" id="flow-consent-consent" name="consent"
                                                           class="warning" required
                                                           aria-describedby="flow-consent-consent-warning-message flow-consent-consent-error-message">
                                                    <label for="flow-consent-consent">Ik heb een behandelrelatie met
                                                        deze patient</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit">Controleer toestemming</button>
                                </fieldset>
                            </form>
                        @else
                            <p>U gaat gegevens opvragen van bsn: {{ $state->getCredentialData()?->getBsn() }}</p>
                            <a href="{{ route('flow-credential') }}" class="button ghost">Gegevens wijzigen</a>
                        @endif
                    </div>
                </li>
            </ul>
            <form class="inline" action="{{ route('flow.retrieve-timeline') }}" method="POST">
                @csrf
                <button
                    type="submit" {{ $state->getCredentialData() ? "" : "disabled" }}>
                    Credential uitgeven
                </button>
            </form>
        </div>
    </section>
@endsection
