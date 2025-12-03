<nav class="navbar" aria-label="Navigasi utama">
  <div class="navbar-brand">
    <div class="navbar-logo">
      <img src="{{ asset('images/maskot.png') }}" alt="Maskot Stadium">
    </div>
    <span>STADIUM</span>
  </div>

  <div class="navbar-actions">
    <a href="{{ route('login') }}" class="btn-login">Login</a>
    <a href="{{ route('register') }}" class="btn-signup">Sign Up</a>
  </div>
</nav>
