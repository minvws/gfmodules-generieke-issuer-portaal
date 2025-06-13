<table class="nested-table nested-level-{{ $level }}">
    <tr>
        <th>Attribuut</th>
        <th>Waarde</th>
    </tr>
    @foreach($data as $key => $value)
        <tr>
            <td>{{ $key }}</td>
            <td>
                @if(is_array($value) || is_object($value))
                    <x-recursive-table :data="(array)$value" :level="$level + 1" />
                @else
                    {{ is_string($value) || is_numeric($value) ? $value : json_encode($value) }}
                @endif
            </td>
        </tr>
    @endforeach
</table>
