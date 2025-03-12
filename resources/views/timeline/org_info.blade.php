@extends('layouts.app')

@section('content')
    <section>
        <div>
            <h1>Details</h1>


            <h2>Organization</h2>

            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>Name</th>
                    <td>{{ $organization['name'] }}</td>
                </tr>

                @foreach($organization['address'] as $address)
                <tr>
                    <th>Address <small>({{ $address['type'] }})</small></th>
                    <td>{{ $address['postalCode'] }}, {{ $address['city'] }} <br>
                        {{ $address['state'] }}, {{ $address['country'] }}</td>
                </tr>
                @endforeach

                <tr>
                    <th>Contact address</th>
                    <td>{{ $organization['contact'][0]['name']['text'] }},<br/>
                        {{ $organization['contact'][0]['address']['text'] }}</td>
                </tr>
                </tbody>
            </table>


            @if ($endpoints)
                <h2>Endpoints</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>URL</th>
                            <th>payloadMimeTypes</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($endpoints as $endpoint)
                        <tr>
                            <td>{{ $endpoint['name'] }}</td>
                            <td>{{ $endpoint['address'] }}</td>
                            <td>{{ implode(", ", $endpoint['payloadMimeType']) }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            @endif

        </div>
    </section>
@endsection
