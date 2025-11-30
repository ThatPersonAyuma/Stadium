@extends('layouts.main')

@section('title', '403 Forbidden')

@section('content')
<section class="error-section">
    <h1>Oops, 403 Forbidden</h1>
    <img src="{{ asset('images/403.png') }}" alt="ERROR 403">
    <p>You do not have permission to access this page.</p>
    <a href="{{ url('/dashboard') }}" class="btn-cta">
        Back to Dashboard
    </a>
</section>
@endsection
