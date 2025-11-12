@extends('layouts.main')

@section('title', 'Register - Stadium')

@section('content')
<section class="auth-section register">
    <div class="auth-card">
        <h1>Registration</h1>

        <form action="{{ route('register.post') }}" method="POST" class="auth-form">
            @csrf
            <h3 class="form-label">Full Name</h3>
            <input type="text" name="fullname" placeholder="Please enter your full name" required>
            <h3 class="form-label">Email</h3>
            <input type="email" name="email" placeholder="Please enter your email" required>
            <h3 class="form-label">Username</h3>
            <input type="text" name="username" placeholder="Please enter your username" required>
            <h3 class="form-label">Password</h3>
            <input type="password" name="password" placeholder="Please enter your password" required>

            <button type="submit" class="btn-auth">Sign Up</button>

            <p class="auth-switch">
                Already have an account?
                <a href="{{ route('login') }}">Login</a>
            </p>
        </form>
    </div>
</section>
@endsection
