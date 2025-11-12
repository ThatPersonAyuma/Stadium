<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Baru</title>
    @vite('resources/css/app.css')
</head>

<body>
    <h1>List KRS Mahasiswa</h1>
    <p>Hai {{ $user->username }}<p>
    <button class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
        Button
    </button>
    <a>Halooo<a>
    {{-- @foreach ($users as $user)
        <h3>{{ $user->name }} - {{ $user->email }}</h3>
        <ul>
            @foreach ($user->krses as $krs)
                @foreach ($matkuls as $matkul)
                    @if ($krs->matkul_id == $matkul->id)
                        <li>{{ $matkul->nama_matkul }}</li>
                    @endif
                @endforeach
            @endforeach
        </ul>
    @endforeach --}}

    {{-- <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form> --}}
</body>

</html>