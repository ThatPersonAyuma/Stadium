<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Quiz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/echo.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-md text-center">
        <h1 class="text-red-2xl font-bold mb-4">Live Quiz</h1>

        <div id="question-container">
            <p id="question" class="text-red-lg font-semibold mb-4">Menunggu soal...</p>
            <div id="options" class="space-y-2"></div>
        </div>
    </div>

    <script>
        const quizId = 1; // Ubah sesuai quiz id aktif

        // Fungsi untuk update tampilan soal dan opsi
        function updateQuestion(question, options) {
            document.getElementById('question').textContent = question;
            const optionsDiv = document.getElementById('options');
            optionsDiv.innerHTML = '';
            options.forEach(opt => {
                const btn = document.createElement('button');
                btn.textContent = opt;
                btn.className = 'w-full py-2 px-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition';
                optionsDiv.appendChild(btn);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            console.log("DOM siap, inisialisasi Echo");

            window.Echo.channel('quiz.' + quizId)
                .listen('.question.sent', e => {
                    console.log('📩 Event diterima:', e);
                    updateQuestion(e.question, e.options);
                });
        });
    </script>
</body>
</html>

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Quiz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-md text-center">
        <h1 class="text-2xl font-bold mb-4">Live Quiz</h1>

        <div id="question-container">
            <p id="question" class="text-lg font-semibold mb-4">Menunggu soal...</p>
            <div id="options" class="space-y-2"></div>
        </div>
    </div>

    <script>
        const quizId = 1; // Ubah sesuai quiz id yang aktif
        console.log("runned")

        // Fungsi untuk update tampilan soal dan opsi
        function updateQuestion(question, options) {
            document.getElementById('question').textContent = question;
            const optionsDiv = document.getElementById('options');
            optionsDiv.innerHTML = ''; // Kosongkan dulu
            options.forEach(opt => {
                const btn = document.createElement('button');
                btn.textContent = opt;
                btn.className = 'w-full py-2 px-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition';
                optionsDiv.appendChild(btn);
            });
        }
    </script>
</body>
</html> --}}
