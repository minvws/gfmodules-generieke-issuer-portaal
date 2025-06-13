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
                    <button aria-expanded="true" id="flow-credential">1.
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
                            <x-recursive-table :data="$state->getCredentialData()->getSubjectAsArray()" />
                            <a href="{{ route('flow-credential') }}" class="button ghost">Attributen wijzigen</a>
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
