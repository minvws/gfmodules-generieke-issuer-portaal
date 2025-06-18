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
                                $loginMethod = config('login_method.method');
                                if ($loginMethod === 'vc') {
                                    $loginRoute = route('vc.login');
                                    $loginText = __('Login with') . ' VC';
                                } elseif ($loginMethod === 'noop') {
                                    $loginRoute = route('noop.login');
                                    $loginText = __('Login without authentication');
                                } else {
                                    $loginRoute = '#';
                                    $loginText = __('Login');
                                }
                            @endphp

                            <ul class="external-login">
                                <li>
                                    <a href="{{ $loginRoute }}">
                                        {{ $loginText }}
                                    </a>
                                </li>
                            </ul>
                        @else
                            <p>Je bent ingelogd als organisatie: {{ $state->getUser()->getName() }}</p>
                        @endif
                    </div>
                </li>
                <li>
                    <button aria-expanded="{{ $state->getUser() ? "true" : "false" }}" id="flow-credential">1.
                        Credential
                    </button>
                    <div aria-labelledby="flow-credential">
                        @if($state->getCredentialData())
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
