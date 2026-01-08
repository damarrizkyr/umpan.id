{{-- Footer --}}
<footer class="footer static">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-7 col-md-4 mb-4 footer-section">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-12 w-auto fill-current text-gray-800" />
                    </a>

                    <span class="text-white font-semibold text-lg">
                        {{ config('app.name') }}
                    </span>
                </div>
                <br>
                <p> Platform booking lapangan futsal dan mini soccer terpercaya di Indonesia.</p>
                <br>
                <div class="social-links">
                    <a href="https://www.instagram.com/" target="_blank">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/" target="_blank">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" target="_blank">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" target="_blank">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 mb-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('dashboard') }}">
                            Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('dashboard') }}#venue">Venue</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('dashboard') }}#faq">FAQ</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-4 mb-4">
                <h5>Hubungi Kami</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2"></i>info@Umpan.id
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i>+62 8xx-xxxx-xxxx
                    </li>
                </ul>
            </div>
        </div>
        <hr class="bg-white opacity-25">
        <div class="text-center py-3">
            <p class="mb-0">&copy; 2025 Umpan. All rights reserved.</p>
        </div>
    </div>
</footer>
