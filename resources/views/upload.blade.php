<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload File Block</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        form {
            max-width: 600px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        label {
            display: block;
            font-weight: bold;
            margin-top: 12px;
        }
        input, select, textarea, button {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            margin-top: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <h2>Upload File untuk Block</h2>

    {{-- Notifikasi error --}}
    @if ($errors->any())
        <div class="error">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Upload --}}
    <form action="/add-file" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="type">Tipe Konten</label>
        <select name="type" id="type" required>
            <option value="">-- Pilih --</option>
            <option value="text">Text</option>
            <option value="image">Image</option>
            <option value="gif">GIF</option>
            <option value="video">Video</option>
            <option value="code">Code</option>
            <option value="quiz">Quiz</option>
        </select>

        <label for="file">File (hanya untuk image/gif/video)</label>
        <input type="file" name="file" id="file" accept=".jpg,.jpeg,.png,.gif,.mp4,.webm">

        <label for="data">Data (JSON opsional)</label>
        <textarea name="data" id="data" rows="4" placeholder='{"text": "Contoh teks"}'></textarea>

        <label for="order_index">Order Index</label>
        <input type="number" name="order_index" id="order_index" value="0" min="0">

        <label for="course_id">Course ID</label>
        <input type="number" name="course_id" id="course_id" required>

        <label for="lesson_id">Lesson ID</label>
        <input type="number" name="lesson_id" id="lesson_id" required>

        <label for="content_id">Content ID</label>
        <input type="number" name="content_id" id="content_id" required>

        <label for="card_id">Card ID</label>
        <input type="number" name="card_id" id="card_id" required>

        <button type="submit">Upload File</button>
    </form>
</body>
</html>
