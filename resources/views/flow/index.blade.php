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
                    <button aria-expanded="true"
                            id="flow-identification-authentication">1. Identificatie en Authenticatie
                    </button>
                    <div aria-labelledby="flow-identification-authentication">
                        @if(!$state->getUser())
                            @error('login')
                            <p class="error"><span>@lang('Error'):</span> {{ $message }}</p>
                            @enderror
                            @php
                                $enabledMethods = config('login_method.enabled_methods');
                            @endphp
                            <ul class="external-login">
                                @foreach($enabledMethods as $loginMethod)
                                    @php
                                        switch ($loginMethod) {
                                            case 'openid4vp':
                                                $loginRoute = route('vc.login');
                                                $loginText = __('Login met') . ' OID4VC';
                                                break;
                                            case 'oidc':
                                                $loginRoute = route('oidc.login');
                                                $loginText = __('Login met') . ' OIDC';
                                                break;
                                            case 'mock':
                                            default:
                                                $loginRoute = route('mock.login');
                                                $loginText = __('Login zonder authenticatie');
                                                break;
                                        }
                                    @endphp
                                    <li>
                                        <a href="{{ $loginRoute }}">
                                            {{ $loginText }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>Je bent ingelogd als organisatie: {{ $state->getUser()->getName() }}</p>
                        @endif
                    </div>
                </li>
                <li>
                    <button aria-expanded="{{ $state->getUser() ? "true" : "false" }}" id="flow-credential">2. Brondata ophalen
                    </button>
                    <div aria-labelledby="flow-credential">
                        @if(!$credentialEnriched)
                            <form action="{{ route('flow-credential.enrich') }}" method="POST">
                                @csrf
                                <fieldset {{ !$state->getUser() ? "disabled" : "" }}>
                                    <p>Verrijk je credential met brondata.</p>
                                    <button type="submit">Verrijk credential</button>
                                </fieldset>
                            </form>
                        @else
                            <p>U gaat een credential uitgeven met de volgende attributen.</p>
                            <x-recursive-table :data="$state->getCredentialData()->getSubjectAsArray()" />
                        @endif
                    </div>
                </li>
            </ul>
            <form class="inline" action="{{ route('flow.retrieve-credential') }}" method="POST">
                @csrf
                <button
                    type="submit" {{ $state->getCredentialData() ? "" : "disabled" }}>
                    Credential uitgeven
                </button>
            </form>
        </div>
    </section>
@endsection
