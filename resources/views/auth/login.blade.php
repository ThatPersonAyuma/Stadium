@extends('layouts.main')

@section('title', 'Login - Stadium')

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <h1>LOGIN</h1>

        <form action="{{ route('login.post') }}" method="POST" class="auth-form">
            @csrf
            <h3 class="form-label">Username</h3>
            <input type="text" name="username" placeholder="USERNAME" required>
            <h3 class="form-label">Password</h3>
            <input type="password" name="password" placeholder="PASSWORD" required>
            <a href="#" class="auth-link">Forgot Password?</a>

            <button type="submit" class="btn-auth">LOGIN</button>

            <p class="auth-switch">
                Don’t have an account?
                <a href="{{ route('register') }}">Create Account</a>
            </p>
        </form>
    </div>
</section>
@endsection
