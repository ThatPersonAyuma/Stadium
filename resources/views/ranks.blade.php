<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranks</title>
</head>
<body>
    <h1>Daftar Rank</h1>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Min XP</th>
                <th>Max XP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ranks as $rank)
                <tr>
                    <td>{{ $rank->id }}</td>
                    <td>{{ $rank->title }}</td>
                    <td>{{ $rank->min_xp }}</td>
                    <td>{{ $rank->max_xp ?? '∞' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada data rank.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p><a href="{{ url('/users') }}">Lihat daftar pengguna</a></p>
</body>
</html> -->
