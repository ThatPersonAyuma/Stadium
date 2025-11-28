@extends('layouts.main')

@section('title', 'Oops! 403 Not Found')

@section('content')
<section class="error-section">
    <h1>Oops, 403 Error!</h1>
    <img src="{{ asset('images/EROR.png') }}" alt="404 Illustration">
    <p>We’re sorry, looks like we can’t find the page you are looking for.</p>
    <a href="{{ url('/dashboard') }}" class="btn-cta">
        Back to Dashboard
    </a>
</section>
@endsection