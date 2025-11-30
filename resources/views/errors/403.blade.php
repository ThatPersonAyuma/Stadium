@extends('layouts.main')

@section('title', 'Oops! 403 Forbidden')

@section('content')
<section class="error-section">
    <h1>Oops, 403 Error!</h1>
    <img src="{{ asset('images/EROR.png') }}" alt="403 Illustration">
    <p>
        {{ $exception->getMessage() ?: "You do not have permission to access this resource." }}
    </p>
    <a href="{{ url('/dashboard') }}" class="btn-cta">
        Back to Dashboard
    </a>
</section>
@endsection
