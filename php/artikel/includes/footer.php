    <footer id="footer" class="bg-dark text-white pt-5 pb-3 mt-auto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <a class="d-flex align-items-center gap-2 fw-bold fs-4 text-white text-decoration-none mb-3" href="index.php">
                        <span class="logo-icon text-primary"><i class="bi bi-broadcast"></i></span>
                        <span class="logo-text">Tech<span class="text-primary">News</span></span>
                    </a>
                    <p class="text-secondary">
                        TechNews adalah portal berita profesional yang menyajikan informasi terkini seputar dunia teknologi, pemrograman, startup, dan inovasi digital.
                    </p>
                </div>
                
                <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <h5 class="mb-3 text-uppercase fs-6 fw-bold">Navigasi</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2"><a href="index.php" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right small me-2"></i>Home</a></li>
                        <li class="mb-2"><a href="artikel.php" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right small me-2"></i>Semua Artikel</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right small me-2"></i>Tentang Kami</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right small me-2"></i>Kontak</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <h5 class="mb-3 text-uppercase fs-6 fw-bold">Kategori</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2"><a href="artikel.php?kategori=Teknologi" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right small me-2"></i>Teknologi</a></li>
                        <li class="mb-2"><a href="artikel.php?kategori=Pemrograman" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right small me-2"></i>Pemrograman</a></li>
                        <li class="mb-2"><a href="artikel.php?kategori=Desain" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right small me-2"></i>Desain UI/UX</a></li>
                        <li class="mb-2"><a href="artikel.php?kategori=Keamanan" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right small me-2"></i>Keamanan Siber</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <h5 class="mb-3 text-uppercase fs-6 fw-bold">Ikuti Kami</h5>
                    <p class="text-secondary mb-3">Dapatkan update terbaru dari sosial media kami.</p>
                    <div class="d-flex gap-2 mb-4">
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle"><i class="bi bi-youtube"></i></a>
                    </div>
                    
                    <h5 class="mb-2 text-uppercase fs-6 fw-bold">Berlangganan</h5>
                    <form>
                        <div class="input-group input-group-sm">
                            <input type="email" class="form-control bg-dark border-secondary text-white" placeholder="Email Anda">
                            <button class="btn btn-primary" type="button"><i class="bi bi-send"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            
            <hr class="border-secondary mt-4 mb-3">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-secondary small">&copy; <?= date('Y') ?> TechNews. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <ul class="list-inline mb-0 small">
                        <li class="list-inline-item"><a href="#" class="text-secondary text-decoration-none">Privacy Policy</a></li>
                        <li class="list-inline-item text-secondary">&bull;</li>
                        <li class="list-inline-item"><a href="#" class="text-secondary text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom Init -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize AOS
            AOS.init({
                once: true,
                offset: 50,
                duration: 800,
                easing: 'ease-in-out'
            });
            
            // Navbar shrink on scroll
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    document.getElementById('mainNavbar').classList.add('py-1');
                    document.getElementById('mainNavbar').classList.remove('py-2');
                } else {
                    document.getElementById('mainNavbar').classList.add('py-2');
                    document.getElementById('mainNavbar').classList.remove('py-1');
                }
            });
        });
    </script>
</body>
</html>
