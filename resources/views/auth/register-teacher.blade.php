@extends('layouts.main')

@section('title', 'Register - Stadium')

@section('content')
<section class="auth-section register">
    <div class="auth-card">
        <h1>Teacher Registration</h1>

        <form action="{{ route('register.post') }}" method="POST" class="auth-form">
            @csrf
            <input type="hidden" name="role" value="{{ \App\Enums\UserRole::TEACHER->value }}">
            <h3 class="form-label">Full Name</h3>
            <input type="text" name="fullname" placeholder="Please enter your full name" class="text-black" required>
            <h3 class="form-label">Email</h3>
            <input type="email" name="email" placeholder="Please enter your email" class="text-black" required>
            <h3 class="form-label">Phone Number</h3>
            <input type="tel" name="phone_number" placeholder="Please enter your phone number" class="text-black" required>
            <h3 class="form-label">Institution</h3>
            <input type="text" name="institution" placeholder="Your current institution (university/school/company)" class="text-black" required>
            <h3 class="form-label">Social Media</h3>
            <select name="social_media_type" class="text-black" required>
                <option value="">Select platform</option>
                <option value="instagram">Instagram</option>
                <option value="github">GitHub</option>
                <option value="linkedin">LinkedIn</option>
                <option value="other">Other</option>
            </select>
            <h3 class="form-label">Social Username / Link</h3>
            <input type="text" name="social_media" placeholder="Enter your username or profile link" class="text-black" required>
            <h3 class="form-label">Username</h3>
            <input type="text" name="username" placeholder="Please enter your username" class="text-black" required>
            <h3 class="form-label">Password</h3>
            <input type="password" name="password" placeholder="Please enter your password" class="text-black" required>
            <h3 class="form-label">Confirm Password</h3>
            <input type="password" name="password_confirmation" placeholder="Please confirm your password" class="text-black" required>

            <button type="submit" class="btn-auth">Sign Up</button>

            <p class="auth-switch">
                Already have an account?
                <a href="{{ route('login') }}">Login</a>
            </p>
        </form>
    </div>
</section>
@endsection
