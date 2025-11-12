@extends('layouts.main')

@section('title', 'Stadium - Learn. Play. Grow.')

@section('content')
<section class="section-hero">

    <div class="hero-bg">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 800" preserveAspectRatio="none">
            <path fill="#002872" d="M0,160 Q240,280 480,160 T960,160 T1440,160 L1440,800 L0,800 Z" />
            <path fill="#001E5C" opacity="0.9" d="M0,600 Q360,400 720,600 T1440,600 L1440,800 L0,800 Z" />
        </svg>
    </div>
    <div class="hero-navbar">
        @include('components.navbar')
    </div>
    <div class="hero-tagline">
        <p>Let's make learning your favorite daily habit!</p>
    </div>
    <h1 class="hero-heading">
        Learn. <span class="txt-accent">Play.</span> Grow.
    </h1>
    <p class="hero-subtxt">
        Study smarter and have fun with <strong>Stadium</strong>, the interactive way to learn and grow.
    </p>
    <div class="hero-pill">
        <span>What do you want to learn today?</span>
        <button class="btn-primary">Get Started</button>
    </div>
</section>

<section class="section-base">
  <div class="container">
    <h2 class="section-title">
      Everything You Need to <br class="hidden md:block" />
      Make Learning Fun.
    </h2>
    <p class="pill-txt">
      Discover interactive features that help you stay consistent and excited every day.
    </p>
  </div>
</section>

<section class="section-feature">
  <div class="container grid grid-cols-1 md:grid-cols-2 items-center gap-10 md:gap-16">
    <div class="feature-txt">
      <h2>Interactive Courses</h2>
      <p>Explore engaging lessons, quizzes, and activities designed to make learning exciting.</p>
      <a href="/courses" class="btn-cta">Explore Courses</a>
    </div>
    <div class="feature-img">
      <img src="{{ asset('images/interactive-courses.png') }}" alt="Interactive Courses Illustration">
    </div>
  </div>
</section>

<section class="section-feature">
  <div class="container grid grid-cols-1 md:grid-cols-2 items-center gap-10 md:gap-16">
    <div class="feature-txt">
      <h2>Leaderboard</h2>
      <p>Earn XP, climb ranks, and compete with friends to stay motivated!</p>
    </div>
    <div class="feature-img">
      <img src="{{ asset('images/leaderboard.png') }}" alt="Leaderboard Illustration">
    </div>
  </div>
</section>

<section class="section-feature">
  <div class="container grid grid-cols-1 md:grid-cols-2 items-center gap-10 md:gap-16">
    <div class="feature-txt">
      <h2>Grow Your Plant</h2>
      <p>Stay consistent! Each correct answer helps your virtual plant grow stronger every day.</p>
    </div>
    <div class="feature-img">
      <img src="{{ asset('images/plant.png') }}" alt="Plant Illustration">
    </div>
  </div>
</section>

<section class="section-feature">
  <div class="container grid grid-cols-1 md:grid-cols-2 items-center gap-10 md:gap-16">
    <div class="feature-txt">
      <h2>Community</h2>
      <p>Join a friendly space to share ideas, ask questions, and grow together with other learners</p>
    </div>
    <div class="feature-img">
      <img src="{{ asset('images/community.png') }}" alt="Community-feature Illustration">
    </div>
  </div>
</section>

<section class="section-feature">
  <div class="container grid grid-cols-1 md:grid-cols-2 items-center gap-10 md:gap-16">
    <div class="feature-txt">
      <h2>Daily Lives System</h2>
      <p>You start each day with 5 lives — use them wisely to complete quizzes and keep your streak alive!</p>
    </div>
    <div class="feature-img">
      <img src="{{ asset('images/heart.png') }}" alt=" Illustration">
    </div>
  </div>
</section>

@include('components.cta')
@endsection
