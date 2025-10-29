<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload Avatar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5 p-4 bg-white rounded shadow-sm">
        <h2 class="mb-4">Upload Avatar untuk {{ $user->name }}</h2>

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Avatar saat ini --}}
        @if($avatarPath)
            <div class="mb-3">
                <h5>Avatar Saat Ini:</h5>
                <img src="{{ asset($avatarPath) }}" alt="Avatar {{ $user->name }}" width="150" class="rounded-circle shadow">
            </div>
        @else
            <p class="text-muted">Belum ada avatar yang diunggah.</p>
        @endif

        {{-- Form upload --}}
        <form action="{{ route('avatar.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="mb-3">
                <label for="avatar" class="form-label">Pilih File Avatar</label>
                <input type="file" name="avatar" id="avatar" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Upload Avatar</button>
        </form>
    </div>
</body>
</html>
