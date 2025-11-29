@extends('layouts.main')

@section('title', 'Login - Stadium')

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <h1>LOGIN</h1>

        <form action="{{ route('login.post') }}" method="POST" class="auth-form">
            @csrf
            @if ($errors->any())
                <div style="color: red; margin-bottom: 10px;">
                    {{ $errors->first() }}
                </div>
            @endif
            <h3 class="form-label">Email</h3>
            <input type="text" name="email" placeholder="EMAIL" class="text-black" required>
            <h3 class="form-label">Password</h3>
            <input type="password" name="password" placeholder="PASSWORD" class="text-black" required>
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
