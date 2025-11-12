<!-- {{-- <pre>
{{ App\Helpers\FileHelper::getBlockFilePath($course->id, $lesson->id, $content->id, $card->id, $block->id) }}
{{ $courses }}
</pre> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learning Path</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .course-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 25px; }
        .lesson-card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin-top: 10px; background: #fdfdfd; }
        .content-card { border-left: 4px solid #007bff; background: #fff; margin: 10px 0; padding: 15px; border-radius: 6px; }
        .block { background: #f1f5f9; padding: 10px; border-radius: 6px; margin-bottom: 8px; }
        .badge-type { font-size: 0.75rem; background: #007bff; }
    </style>
</head>
<body class="container py-5">
    <h1 class="mb-4 text-center fw-bold">Learning Path Explorer</h1>

    @foreach($courses as $course)
        <div class="course-card">
            <h2 class="fw-bold">{{ $course->title }}</h2>
            <p class="text-muted">{{ $course->description ?? 'Tidak ada deskripsi' }}</p>

            {{-- LESSON --}}
            @foreach($course->lessons as $lesson)
                <div class="lesson-card">
                    <h4 class="text-primary">📘 {{ $lesson->title }}</h4>
                    <p>{{ $lesson->description }}</p>

                    {{-- CONTENT --}}
                    @foreach($lesson->contents as $content)
                        <div class="content-card">
                            <h5 class="fw-semibold">{{ $content->title }}</h5>

                            {{-- CARD --}}
                            @foreach($content->cards as $card)
                                <div class="mt-3">
                                    <h6 class="text-secondary">Card #{{ $card->order_index }}</h6>

                                    {{-- BLOCK --}}
                                    @foreach($card->blocks as $block)
                                        <div class="block">
                                            <span class="badge text-light badge-type">{{ strtoupper($block->type->value ?? $block->type) }}</span>

                                            @switch($block->type->value ?? $block->type)
                                                @case('text')
                                                    <p class="mt-2">{{ $block->data['body'] ?? '[Teks tidak tersedia]' }}</p>
                                                    @break

                                                @case('image')
                                                    <img src="{{ asset(App\Helpers\FileHelper::getBlockFilePath($course->id, $lesson->id, $content->id, $card->id, $block->id)) }}" alt="{{ $block->data['alt'] ?? 'image' }}">
                                                    @break

                                                @case('gif')
                                                    <img src="{{ asset(App\Helpers\FileHelper::getBlockFilePath($course->id, $lesson->id, $content->id, $card->id, $block->id)) }}" alt="GIF">
                                                    @break

                                                @case('video')
                                                    <video controls>
                                                        <source src="{{ asset(App\Helpers\FileHelper::getBlockFilePath($course->id, $lesson->id, $content->id, $card->id, $block->id)) }}" type="video/mp4">
                                                    </video>
                                                    @break

                                                @case('code')
                                                    <pre class="bg-dark text-light p-3 rounded"><code>{{ $block->data['code'] ?? '[kode kosong]' }}</code></pre>
                                                    @break

                                                @case('quiz')
                                                    <div class="p-3 border rounded bg-light shadow-sm">
                                                        {{-- Pertanyaan --}}
                                                        <strong class="d-block mb-2 text-dark">
                                                            🧠 {{ $block->data['question'] ?? 'Pertanyaan tidak tersedia.' }}
                                                        </strong>

                                                        {{-- Pilihan Jawaban --}}
                                                        <ul class="list-group mb-3">
                                                            @foreach(($block->data['choices'] ?? []) as $key => $choice)
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <span>
                                                                        <strong>{{ $key }}.</strong> {{ $choice }}
                                                                    </span>
                                                                    @if(isset($block->data['answer']) && $block->data['answer'] === $key)
                                                                        <span class="badge bg-success">Benar</span>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        {{-- Penjelasan --}}
                                                        @if(!empty($block->data['explanation']))
                                                            <div class="alert alert-info mb-0">
                                                                💡 <em>{{ $block->data['explanation'] }}</em>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @break
                                            @endswitch
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> -->
