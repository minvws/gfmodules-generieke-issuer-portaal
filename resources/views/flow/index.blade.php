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
                        <p>Je bent ingelogd.</p>
                    </div>
                </li>
                <li>
                    <button aria-expanded="{{ $state->getCredentialData() ? "true" : "false" }}" id="flow-credential">2.
                        Credential
                    </button>
                    <div aria-labelledby="flow-credential">
                        @if(!$state->getCredentialData() || $editCredential)
                            <form action="{{ route('flow-credential.store') }}" method="POST">
                                @csrf
                                <fieldset>
                                    <p>Geef hier je eigen credential uit.</p>
                                    <div>
                                        <label for="flow-credential-subject">Attributen</label>
                                        <span
                                            class="nota-bene">Attributen van het credential</span>
                                        <div>
                                            @error('subject')
                                            <p class="error" id="flow-credential-subject-error-message">
                                                <span>Foutmelding:</span> {{ $message }}
                                            </p>
                                            @enderror
                                            <textarea
                                                id="flow-credential-subject"
                                                name="subject"
                                                required
                                                aria-describedby="flow-credential-subject-error-message"
                                                rows="10"
                                                >{{ old('subject', $state->getCredentialData()?->getSubject() ?? $defaultCredentialSubject) }}</textarea>
                                        </div>
                                    </div>
                                    <button type="submit">Opslaan</button>
                                </fieldset>
                            </form>
                        @else
                            <p>U gaat een credential uitgeven met de volgende attributen.</p>
                            <table>
                                <tr>
                                    <th>Attribuut</th>
                                    <th>Waarde</th>
                                </tr>
                                @foreach($state->getCredentialData()->getSubjectAsArray() as $key => $value )
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ is_string($value) || is_numeric($value) ? $value : json_encode($value)  }}</td>
                                </tr>
                                @endforeach
                            </table>
                            <a href="{{ route('flow-credential') }}" class="button ghost">Credential wijzigen</a>
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
