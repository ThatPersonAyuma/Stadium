@extends('layouts.main')

@section('title', '500 Server Error')

@section('content')
<section class="error-section">
    <h1>Oops, 500 Server Error</h1>
    <img src="{{ asset('images/500.png') }}" alt="ERROR 500">
    <p>Something went wrong on our end. Please try again later.</p>
    <a href="{{ url('/dashboard') }}" class="btn-cta">
        Back to Dashboard
    </a>
</section>
@endsection
