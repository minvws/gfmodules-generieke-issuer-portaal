@extends('layouts.app')

@section('content')
    <section>
        <div>
            <h1>TIMELINE RESULT</h1>

            @if (count($errors) > 0)
                <div class="error" role="group" aria-label="foutmelding">
                    <h2>Foutmeldingen</h2>
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{ $error['details'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($patient)
            <h2>Tijdslijn van {{ $patient['display'] }} <small>({{$bsn}})</small></h2>
            @endif

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                <tr>
                    <th>Datum</th>
                    <th>Tijd</th>
                    <th>Modaliteit</th>
                    <th>Omschrijving</th>
                    <th>Beelden</th>
                    <th>BodyPart</th>
                    <th>Instelling</th>
                    <th>Actie</th>
                    <th>Ura</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($series as $entry)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($entry['resource']['started'])->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($entry['resource']['started'])->format('H:i') }}</td>
                        <td>{{ $entry['resource']['modality']['display'] ?? '-' }}</td>
                        <td>{{ $entry['resource']['description'] ?? '-' }}</td>
                        <td>{{ count($entry['resource']['instance']) }}</td>
                        <td>{{ $entry['resource']['bodySite']['concept']['coding'][0]['display'] ?? '-' }}</td>
                        <td>{{ $entry['references']['organization']['name'] ?? '-' }}</td>
                        <td>&nbsp;</td>
                        <td><a href="{{route('timeline.org_info', ['ref' => $entry['references']['addressingInformation']['organizationId'] ])}}">{{ $entry['references']['addressingInformation']['ura'] }}</a></td>

                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </section>
@endsection
