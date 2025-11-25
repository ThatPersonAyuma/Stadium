{{-- <!-- {{-- <pre>
{{ App\Helpers\FileHelper::getBlockUrl($course->id, $lesson->id, $content->id, $card->id, $block->id) }}
{{ $courses }}
</pre> --}}
{{-- <pre>
{{ dd(Storage::disk('public')->url('courses')); }}
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
        <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .btn-open-popup {
            padding: 12px 24px;
            font-size: 18px;
            background-color: green;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-open-popup:hover {
            background-color: #4caf50;
        }

        .overlay-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .popup-box {
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
            width: 320px;
            text-align: center;
            opacity: 0;
            transform: scale(0.8);
            animation: fadeInUp 0.5s ease-out forwards;
            max-height: 80vh; /* atau berapa pun */
        overflow-y: auto;
        }

        .form-container {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            margin-bottom: 10px;
            font-size: 16px;
            color: #444;
            text-align: left;
        }

        .form-input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        .btn-submit,
        .btn-close-popup {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-submit {
            background-color: green;
            color: #fff;
        }

        .btn-close-popup {
            margin-top: 12px;
            background-color: #e74c3c;
            color: #fff;
        }

        .btn-submit:hover,
        .btn-close-popup:hover {
            background-color: #4caf50;
        }

        /* Keyframes for fadeInUp animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animation for popup */
        .overlay-container.show {
            display: flex;
            opacity: 1;
        }
    </style>
</head>
<body class="container py-5">
    @if ($errors->any())
                <div style="color: red; margin-bottom: 10px;">
                    {{ $errors->first() }}
                </div>
            @endif
    <h1 class="mb-4 text-center fw-bold">Learning Path Explorer</h1>
    @foreach($courses as $course)
            <div class="course-card">
                        <div class="d-flex gap-2 mb-2">
                <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-warning btn-sm">✏ Edit</a>
                <form action="{{ route('courses.destroy', $course->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus course ini?')">🗑 Delete</button>
                </form>
            </div>
            <h2 class="fw-bold">{{ $course->title }}</h2>
            <p class="text-muted">{{ $course->description ?? 'Tidak ada deskripsi' }}</p>

            {{-- LESSON --}}
            <a href="{{ route('lessons.create', ['course_id' => $course->id]) }}"
                class="btn btn-success btn-sm">➕ Add Lesson</a>

            @foreach($course->lessons as $lesson)
                <div class="lesson-card">
                    <div class="d-flex gap-2 mb-2">
                        <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-warning btn-sm">✏ Edit</a>
                        <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus lesson?')">🗑 Delete</button>
                        </form>
                    </div>
                    <h4 class="text-primary">📘 {{ $lesson->title }}</h4>
                    <p>{{ $lesson->description }}</p>

                    {{-- CONTENT --}}

                    <button
                        class="bg-blue-600 text-white px-3 py-2 rounded-lg"
                        onclick="openPopup(
                            '/contents/create',   // fetch form structure
                            '/contents',          // submit URL
                            {{ $lesson->id }},    // relation id
                            'Tambah Content'      // modal title
                        )"
                    >
                        + Tambah Content
                    </button>



                    @foreach($lesson->contents as $content)
                        <button class="btn-open-popup" onclick="openPopup('/contents/create', '/contents', {{ $lesson->id ?? 'null' }})">
                            Open Popup Form
                        </button>
                        <div class="content-card">
                            <div class="d-flex gap-2 mb-2">
                                <a href="{{ route('contents.edit', $content->id) }}" class="btn btn-warning btn-sm">✏ Edit</a>
                                <form action="{{ route('contents.destroy', $content->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus content?')">🗑 Delete</button>
                                </form>
                            </div>
                            
                            <h5 class="fw-semibold">{{ $content->title }}</h5>

                            {{-- CARD --}}
                            <a href="{{ route('cards.create', ['content_id' => $content->id]) }}"
                                class="btn btn-success btn-sm">➕ Add Card</a>

                            @foreach($content->cards as $card)
                                <div class="mt-3">
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ route('cards.edit', $card->id) }}" class="btn btn-warning btn-sm">✏ Edit</a>
                                        <form action="{{ route('cards.destroy', $card->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus card?')">🗑 Delete</button>
                                        </form>
                                    </div>

                                    <h6 class="text-secondary">Card #{{ $card->order_index }}</h6>

                                    {{-- BLOCK --}}
                                    {{-- <a href="{{ route('blocks.create', ['card_id' => $card->id]) }}"
                                        class="btn btn-success btn-sm">➕ Add Block</a> --}}
                                    {{-- <button
                                        class="bg-blue-600 text-white px-3 py-2 rounded-lg"
                                        onclick="openPopup(
                                            '/blocks/create?type=gif',   // fetch form structure
                                            '/blocks',          // submit URL
                                            {{ $content->id }},    // relation id
                                            'Tambah Content'      // modal title
                                        )"
                                    >
                                        + Tambah Block
                                    </button> --}}
                                    <button
                                        class="bg-blue-600 text-white px-3 py-2 rounded-lg"
                                        onclick="openPopupBlockType(
                                            {{ $course->id }},{{ $lesson->id }}, {{ $content->id }}, {{ $card->id }}
                                            )"
                                    >
                                        + Tambah Block
                                    </button>
                                    @foreach($card->blocks as $block)
                                        <div class="block">
                                            <div class="d-flex gap-2 mb-2">
                                                <a href="{{ route('blocks.edit', $block->id) }}" class="btn btn-warning btn-sm">✏ Edit</a>
                                                <form action="{{ route('blocks.destroy', $block->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus block?')">🗑 Delete</button>
                                                </form>
                                            </div>

                                            <span class="badge text-light badge-type">{{ strtoupper($block->type->value ?? $block->type) }}</span>

                                            @switch($block->type->value ?? $block->type)
                                                @case('text')
                                                    <p class="mt-2">{{ $block->data['body'] ?? $block->data['content'] }}</p>
                                                    @break

                                                @case('image')
                                                    <img src="{{ asset(App\Helpers\FileHelper::getBlockUrl($course->id, $lesson->id, $content->id, $card->id, $block->id)) }}" alt="{{ $block->data['alt'] ?? 'image' }}">
                                                    @break

                                                @case('gif')
                                                    <img src="{{ asset(App\Helpers\FileHelper::getBlockUrl($course->id, $lesson->id, $content->id, $card->id, $block->id)) }}" alt="GIF">
                                                    @break

                                                @case('video')
                                                    <video controls>
                                                        <source src="{{ asset(App\Helpers\FileHelper::getBlockUrl($course->id, $lesson->id, $content->id, $card->id, $block->id)) }}" type="video/mp4">
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
                                        <button
                                            class="bg-blue-600 text-white px-3 py-2 rounded-lg"
                                            onclick="openEditPopup(
                                                '{{ route('blocks.edit', $block->id) }}',
                                                '{{ route('blocks.update', $block->id) }}',
                                                {{ $course->id }},{{ $lesson->id }}, {{ $content->id }}, {{ $card->id }}
                                                )"
                                        >
                                            + Edit Block
                                        </button>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endforeach
    

    <div id="popupOverlay" class="overlay-container">
        <div class="popup-box">
            <h2 id="popupTitle" style="color: green;">Popup Form</h2>

            <form id="PopUpForm" class="form-container" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Dynamic fields will be inserted here -->
                <div id="DynamicFields"></div>

                <button class="btn-submit" type="submit">Submit</button>
            </form>

            <button class="btn-close-popup" onclick="togglePopup()">
                Close
            </button>
        </div>
    </div>

    <div id="popUpeditOverlay" class="overlay-container">
        <div class="popup-box">
            <h2 id="popupTitle" style="color: green;">Edit Form</h2>

            <form id="EditPopUpForm" class="form-container" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Dynamic fields will be inserted here -->
                <div id="EditDynamicFields"></div>

                <button class="btn-submit" type="submit">Submit</button>
            </form>

            <button class="btn-close-popup" onclick="toggleEditPopup()">
                Close
            </button>
        </div>
    </div>

    <div id="popupType" class="overlay-container">
        <div class="popup-box">
            <h2 id="popupTitle" style="color: green;">Pilih Tipe Block</h2>
            <div id="typeField" class="display:flex;">

            </div>
            <button class="btn-close-popup" onclick="toggleType()">
                Close
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function togglePopup() {
        const overlay = document.getElementById('popupOverlay');
        overlay.classList.toggle('show');
    }
    function toggleType() {
        const overlay = document.getElementById('popupType');
        overlay.classList.toggle('show');
    }

    const form = document.getElementById("PopUpForm");
    const container = document.getElementById("DynamicFields");

    const typeField = document.getElementById("typeField");

    async function openPopupBlockType(courseId, lessonId, contentId, cardId) {
        toggleType();
        console.log(`Haloo ${courseId}, ${lessonId}, ${contentId}, ${cardId}`)
        const res = await fetch('{{ route('get-type') }}');
        const types = await res.json(); 
        // contoh: ["text","image","code","quiz","gif","video"]
        typeField.innerHTML = "";
        types.forEach(type => {
            const btn = document.createElement("button");
            btn.textContent = type.toUpperCase();
            btn.dataset.type = type;
            btn.classList.add("btn", "btn-primary", "m-1");
            console.log(type)
            btn.addEventListener("click", () => {toggleType();openPopup(`{{ route('blocks.create') }}?type=${type}&card_id=${cardId}`, `{{ route('blocks.store') }}`,courseId, lessonId, contentId, cardId, null, type)});
            typeField.appendChild(btn);
        });
    }

    async function openPopup(fetchUrl, submitUrl, courseId, lessonId = null, contentId=null, cardId=null, blockId=null, type=null) {
        togglePopup(); // show popup
        
        form.action = submitUrl;

        // empty old fields
        container.innerHTML = "";

        // fetch structure from Laravel create()
        const response = await fetch(fetchUrl);
        const fields = await response.json();
        console.log(fields)
        const hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "course_id"; 
        hidden.value = courseId;
        container.appendChild(hidden);
        // add parent id (lesson_id, content_id, card_id, etc)
        if (lessonId !== null && lessonId !== "null") {
            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = "lesson_id"; 
            hidden.value = lessonId;
            container.appendChild(hidden);
            if (contentId !== null && contentId !== "null") {
                const hidden1 = document.createElement("input");
                hidden1.type = "hidden";
                hidden1.name = "content_id"; 
                hidden1.value = contentId;
                container.appendChild(hidden1);
                if (cardId !== null && cardId !== "null") {
                    const hidden2 = document.createElement("input");
                    hidden2.type = "hidden";
                    hidden2.name = "card_id"; 
                    hidden2.value = cardId;
                    container.appendChild(hidden2);
                    if (blockId !== null && blockId !== "null") {
                        const hidden3 = document.createElement("input");
                        hidden3.type = "hidden";
                        hidden3.name = "block_id"; 
                        hidden3.value = blockId;
                        container.appendChild(hidden3);
                    }
                }
            }
        }
        console.log(`Ini tipe: ${type}`)
        if (type != null){
            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = "type"; 
            hidden.value = type;
            container.appendChild(hidden);
        }

        // create fields dynamically
        fields.forEach(field => {
            const wrapper = document.createElement("div");
            wrapper.classList.add("form-group");
            console.log(field.name)
            if (field.type=='textarea'){
                wrapper.innerHTML = `
                    <label class="form-label">${field.label}</label>
                    <textarea name="${field.name}" class="form-input" required></textarea>
                `;
            }
            else{
                wrapper.innerHTML = `
                    <label class="form-label">${field.label}</label>
                    <input 
                        type="${field.type}" 
                        name="${field.name}" 
                        class="form-input" 
                        required>
                `;
            }
            console.log(field.type)
            container.appendChild(wrapper);
        });
    }
    function toggleEditPopup() {
        const overlay = document.getElementById('popUpeditOverlay');
        overlay.classList.toggle('show');
    }
    const editForm = document.getElementById("EditPopUpForm");
    const editContainer = document.getElementById("EditDynamicFields");
    async function openEditPopup(fetchUrl, submitUrl, courseId, lessonId = null, contentId=null, cardId=null, blockId=null, type=null) {
        toggleEditPopup();

        editForm.action = submitUrl;

        editContainer.innerHTML = "";

        const response = await fetch(fetchUrl);
        const { schema, value } = await response.json();
        console.log("Ini bisa bang\n", schema, value)
        const getNestedValue = (obj, path) => {
            if (!obj) return "";
            const keys = path.replace(/\]/g, "").split("[");
            return keys.reduce((acc, key) => {
                if (acc && acc[key] !== undefined) return acc[key];
                return "";
            }, obj);
        };
        
        const hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "course_id"; 
        hidden.value = courseId;
        editContainer.appendChild(hidden);

        if (lessonId !== null && lessonId !== "null") {
            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = "lesson_id"; 
            hidden.value = lessonId;
            editContainer.appendChild(hidden);
            if (contentId !== null && contentId !== "null") {
                const hidden1 = document.createElement("input");
                hidden1.type = "hidden";
                hidden1.name = "content_id"; 
                hidden1.value = contentId;
                editContainer.appendChild(hidden1);
                if (cardId !== null && cardId !== "null") {
                    const hidden2 = document.createElement("input");
                    hidden2.type = "hidden";
                    hidden2.name = "card_id"; 
                    hidden2.value = cardId;
                    editContainer.appendChild(hidden2);
                    if (blockId !== null && blockId !== "null") {
                        const hidden3 = document.createElement("input");
                        hidden3.type = "hidden";
                        hidden3.name = "block_id"; 
                        hidden3.value = blockId;
                        editContainer.appendChild(hidden3);
                    }
                }
            }
        }

        // Generate all schema fields
        schema.forEach(field => {
            const wrapper = document.createElement("div");
            wrapper.classList.add("form-group");

            const val = getNestedValue(value, field.name);
            console.log(val)
            // For textarea
            if (field.type === "textarea") {
                wrapper.innerHTML = `
                    <label class="form-label">${field.label}</label>
                    <textarea name="${field.name}" class="form-input" required>${val}</textarea>
                `;
            } 
            // For file input (file cannot have value)
            else if (field.type === "file") {
                wrapper.innerHTML = `
                    <label class="form-label">${field.label}</label>
                    <input type="file" name="${field.name}" class="form-input">
                `;
            }
            // For normal <input>
            else {
                wrapper.innerHTML = `
                    <label class="form-label">${field.label}</label>
                    <input 
                        type="${field.type}"
                        name="${field.name}"
                        value="${val}"
                        class="form-input"
                        required
                    >
                `;
            }

            editContainer.appendChild(wrapper);}
        )
    }
    </script>



</body>
</html>


