@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Edit Content</h2>

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('contents.update', $content->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Laravel butuh ini untuk update --}}

        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" 
                   value="{{ old('title', $content->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="order_index" class="form-label">Order Index</label>
            <input type="number" name="order_index" class="form-control"
                   value="{{ old('order_index', $content->order_index) }}" required>
        </div>

        <div class="mb-3">
            <label for="course_id" class="form-label">Course ID</label>
            <input type="number" name="course_id" class="form-control"
                   value="{{ old('course_id', $content->lesson->course_id ?? null) }}" required>
        </div>

        <div class="mb-3">
            <label for="lesson_id" class="form-label">Lesson ID</label>
            <input type="number" name="lesson_id" class="form-control"
                   value="{{ old('lesson_id', $content->lesson_id) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Update Content
        </button>
    </form>
</div>
@endsection
