@extends('layouts.main')

@section('title', '404 Not Found')

@section('content')
<section class="error-section">
    <h1>Oops, 404 Not Found</h1>
    <img src="{{ asset('images/EROR.png') }}" alt="ERROR 404">
    <p>We are sorry, looks like we cannot find the page you are looking for.</p>
    <a href="{{ url('/dashboard') }}" class="btn-cta">
        Back to Dashboard
    </a>
</section>
@endsection
