
<footer class="footer">
  <section class="footer-container">
    <div class="container grid grid-cols-1 md:grid-cols-3 gap-10 text-center md:text-left">
      <div class="flex flex-col items-center md:items-start gap-3">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 bg-white rounded-full">
            <img src="{{ asset('images/maskot.png') }}" alt="Maskot Stadium">
          </div>
          <span class="text-lg font-extrabold text-white">STADIUM</span>
        </div>
        <p class="text-gray-300 text-sm leading-relaxed max-w-xs">
          Stadium is an interactive learning platform that makes studying fun, engaging, and rewarding.
        </p>

        <div class="flex space-x-4 mt-2">
          <a href="#" class="social-icon" aria-label="Instagram">
            <i class="fa-brands fa-instagram text-white text-lg"></i>
          </a>
          <a href="#" class="social-icon" aria-label="Twitter">
            <i class="fa-brands fa-x-twitter text-white text-lg"></i>
          </a>
          <a href="#" class="social-icon" aria-label="LinkedIn">
            <i class="fa-brands fa-linkedin-in text-white text-lg"></i>
          </a>
        </div>
      </div>
      <div>
        <h3 class="font-semibold mb-4 text-white">Quick Links</h3>
        <ul class="space-y-2 text-gray-300 text-sm">
          <li><a href="/" class="hover:text-white">Home</a></li>
          <li><a href="/courses" class="hover:text-white">Courses</a></li>
          <li><a href="/ranks" class="hover:text-white">Leaderboard</a></li>
          <li>
              <a href="{{ route('profile.index') }}" class="hover:text-white">Profile</a>
          </li>
        </ul>
      </div>
      <div>
        <h3 class="font-semibold mb-4 text-white">Support</h3>
        <ul class="space-y-2 text-gray-300 text-sm">
          <li><a href="#" class="hover:text-white">FAQ</a></li>
          <li><a href="#" class="hover:text-white">Contact</a></li>
          <li><a href="#" class="hover:text-white">Help Center</a></li>
          <li><a href="#" class="hover:text-white">Privacy</a></li>
        </ul>
      </div>
    </div>
  </section>
  <section class="footer-bottom-container">
    <div class="footer-bottom">
      &copy; 2025 Stadium. All rights reserved. Made with love by the Stadium Team.
    </div>
  </section>
</footer>
