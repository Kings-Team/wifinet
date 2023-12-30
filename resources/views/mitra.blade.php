<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Mitra</th>
            <th>Tanggal Buat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item['nama_mitra'] }}</td>
                <td>{{ $item['updated_at'] }}</td>
            </tr>
        @endforeach
    </tbody>
</body>

</html>
