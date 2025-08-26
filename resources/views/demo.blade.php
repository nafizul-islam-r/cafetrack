<div>
    <table border="1">
        @foreach ($data as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['age'] }}</td>
                <td>{{ $item['city'] }}</td>
            </tr>
        @endforeach
    </table>
</div>
