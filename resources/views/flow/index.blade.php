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
            <h1>Informatie opvragen</h1>

            <ul class="accordion">
                <li>
                    <button aria-expanded="{{ !$state->getConsentData() ? "true" : "false" }}"
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
                    <button aria-expanded="{{ $state->getUser() ? "true" : "false" }}" id="flow-consent">2.
                        Toestemming
                    </button>
                    <div aria-labelledby="flow-consent">
                        @if(!$state->getConsentData() || $editConsent)
                            <form action="{{ route('flow-consent.store') }}" method="POST">
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
                                                value="{{ old('bsn', $state->getConsentData()?->getBsn()) }}"
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
                            <p>U gaat gegevens opvragen van bsn: {{ $state->getConsentData()?->getBsn() }}</p>
                            <a href="{{ route('flow-consent') }}" class="button ghost">Gegevens wijzigen</a>
                        @endif
                    </div>
                </li>
                <li>
                    <button aria-expanded="{{ $state->getConsentData() && !$editConsent  ? "true" : "false" }}"
                            id="flow-authorization">3. Autorisatie
                    </button>
                    <div aria-labelledby="flow-authorization">
                        @if(!$state->getAuthorizationData() || $editAuthorization)
                            <form action="{{ route('flow-authorization.store') }}" method="POST">
                                @csrf
                                <fieldset {{ !$state->getUser() || !$state->getConsentData() ? "disabled" : "" }}>
                                    <p>Controleer of u geautoriseerd bent voor het opvragen van het informatietype voor
                                        deze patient.</p>
                                    <div>
                                        <label for="flow-authorization-information-types">Type informatie</label>
                                        <span class="nota-bene">Welke informatietypen wilt u opvragen</span>
                                        <div>
                                            @error('information_types')
                                            <p class="error" id="flow-authorization-information-types-error-message">
                                                <span>Foutmelding:</span> {{ $message }}
                                            </p>
                                            @enderror
                                            @foreach($informationTypes as $informationTypeKey => $informationType)
                                                <div class="checkbox">
                                                    <input type="checkbox"
                                                           id="flow-authorization-information-types-{{ $informationTypeKey }}"
                                                           name="information_types[]" value="{{ $informationTypeKey }}"
                                                           aria-describedby="flow-authorization-information-types-error-message" {{ in_array($informationTypeKey, old('information_types', \App\Enums\DataDomain::toStringArray($state->getAuthorizationData()?->getInformationTypes() ?? []))) ? 'checked' : '' }}>
                                                    <label
                                                        for="flow-authorization-information-types-{{ $informationTypeKey }}">{{ $informationType }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <label for="flow-authorization-access-code">Accordering</label>
                                        <span class="nota-bene">Lorem ipsum token</span>
                                        <div>
                                            @error('access_code')
                                            <p class="error" id="flow-authorization-access-code-error-message">
                                                <span>Foutmelding:</span> {{ $message }}
                                            </p>
                                            @enderror
                                            <input
                                                id="flow-authorization-access-code"
                                                name="access_code"
                                                type="number"
                                                required
                                                aria-describedby="flow-authorization-access-code-error-message"
                                                value="{{ old('access_code', $state->getAuthorizationData()?->getAccessCode()) }}"
                                            />
                                        </div>
                                    </div>
                                    <button type="submit">Controleer autorisatie</button>
                                </fieldset>
                            </form>
                        @else
                            @php
                                $selectedInformationTypes = collect($informationTypes)->only(\App\Enums\DataDomain::toStringArray($state->getAuthorizationData()->getInformationTypes()))->toArray();
                            @endphp

                            <p>Selectie: {{ implode(', ', $selectedInformationTypes) }}</p>
                            <a href="{{ route('flow-authorization') }}" class="button ghost">Selectie wijzigen</a>
                        @endif
                    </div>
                </li>
            </ul>
            <form class="inline" action="{{ route('flow.retrieve-timeline') }}" method="POST">
                @csrf
                <button
                    type="submit" {{ $state->getConsentData() && $state->getAuthorizationData() ? "" : "disabled" }}>
                    Informatie opvragen
                </button>
            </form>
        </div>
    </section>
@endsection
