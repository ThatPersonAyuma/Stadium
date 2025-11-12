<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
    <h1>Daftar Pengguna</h1>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Email</th>
                <th>XP</th>
                <th>Rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->experience }}</td>
                    <td>{{ $user->rank->title ?? 'Tidak ada rank' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada data pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p><a href="{{ url('/ranks') }}">Lihat daftar rank</a></p>
</body>
</html> -->
