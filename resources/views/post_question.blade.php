<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kirim Pertanyaan Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
        }
        form {
            margin-bottom: 1.5rem;
        }
        button {
            padding: 0.5rem 1rem;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4338ca;
        }
        #result {
            margin-top: 1rem;
            padding: 1rem;
            background: #f3f4f6;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <h1>Kirim Pertanyaan ke Peserta</h1>

    <form id="quizForm">
        @csrf
        <label for="quizId">Quiz ID:</label>
        <input type="number" id="quizId" name="quizId" value="1" required>
        <button type="submit">Kirim Pertanyaan</button>
    </form>

    <div id="result">Belum ada hasil.</div>

    <script>
        document.getElementById('quizForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const quizId = document.getElementById('quizId').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const res = await fetch('/post-question', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({ quizId }),
            });

            const data = await res.json();
            document.getElementById('result').innerHTML = `
                <strong>Status:</strong> ${data.status} <br>
                <strong>Pertanyaan:</strong> ${data.question.question} <br>
                <strong>Pilihan:</strong>
                <ul>${data.question.options.map(o => `<li>${o}</li>`).join('')}</ul>
            `;
        });
    </script>
</body>
</html>
