<footer class="main-footer">
    <div class="container">
        <div class="row gy-5">

            <div class="col-lg-5">
                <div class="footer-brand mb-3">
                    <div class="footer-logo-box">
                        <img src="{{ asset('unib.jpg') }}" alt="Logo">
                    </div>
                    <h4 class="footer-title ms-3">DIGITAL LIBRARY <br><span>FAKULTAS TEKNIK UNIB</span></h4>
                </div>
                <p class="footer-desc">
                    Sistem pengelolaan perpustakaan digital terintegrasi untuk mendukung riset dan literasi akademik di lingkungan Fakultas Teknik Universitas Bengkulu.
                </p>
                <div class="footer-socials">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                    <a href="#"><i class="bi bi-globe"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">Navigasi</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right"></i> Beranda</a></li>
                    <li><a href="{{ route('buku.index') }}"><i class="bi bi-chevron-right"></i> Koleksi Buku</a></li>
                    <li><a href="{{ route('auth.artikel') }}"><i class="bi bi-chevron-right"></i> Artikel Terbaru</a></li>
                    <li><a href="{{ route('about') }}"><i class="bi bi-chevron-right"></i> Tentang Kami</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h5 class="footer-heading">Hubungi Kami</h5>
                <div class="contact-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <p>Fakultas Teknik, Universitas Bengkulu, Kandang Limun, Kec. Muara Bangka Hulu, Kota Bengkulu, 38119</p>
                </div>
                <div class="contact-item">
                    <i class="bi bi-envelope-fill"></i>
                    <p>perpusftunib@gmail.com</p>
                </div>
                <div class="contact-item">
                    <i class="bi bi-telephone-fill"></i>
                    <p>(0736) 344087</p>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; {{ date('Y') }} <strong>Fakultas Teknik UNIB</strong>. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <span class="footer-credit">Developed for Excellence</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.main-footer {
    background: #031c4d; /* Navy Super Gelap */
    color: white;
    padding: 80px 0 30px;
    position: relative;
    overflow: hidden;
    border-top: 4px solid var(--c-accent, #3a86ff);
}

/* Background Ornament */
.main-footer::before {
    content: '';
    position: absolute;
    top: -100px;
    right: -100px;
    width: 300px;
    height: 300px;
    background: rgba(58, 134, 255, 0.05);
    border-radius: 50%;
    filter: blur(80px);
}

/* BRAND SECTION */
.footer-brand {
    display: flex;
    align-items: center;
}
.footer-logo-box {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 10px;
    padding: 5px;
}
.footer-logo-box img { width: 100%; height: 100%; object-fit: contain; }
.footer-title {
    font-weight: 800;
    font-size: 1.2rem;
    color: white;
    margin-bottom: 0;
    line-height: 1.2;
}
.footer-title span {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.6);
    letter-spacing: 1px;
}
.footer-desc {
    color: #a0aec0;
    font-size: 0.95rem;
    line-height: 1.7;
    margin-top: 20px;
    max-width: 90%;
}

/* SOCIALS */
.footer-socials {
    display: flex;
    gap: 12px;
    margin-top: 25px;
}
.footer-socials a {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.1);
}
.footer-socials a:hover {
    background: #3a86ff;
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(58, 134, 255, 0.4);
}

/* LINKS SECTION */
.footer-heading {
    font-weight: 700;
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 10px;
}
.footer-heading::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background: #3a86ff;
}
.footer-links {
    list-style: none;
    padding: 0;
}
.footer-links li { margin-bottom: 12px; }
.footer-links li a {
    color: #a0aec0;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 0.95rem;
}
.footer-links li a:hover {
    color: white;
    padding-left: 8px;
}

/* CONTACT SECTION */
.contact-item {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}
.contact-item i {
    color: #3a86ff;
    font-size: 1.1rem;
}
.contact-item p {
    color: #a0aec0;
    font-size: 0.9rem;
    margin: 0;
    line-height: 1.5;
}

/* FOOTER BOTTOM */
.footer-bottom {
    margin-top: 60px;
    padding-top: 25px;
    border-top: 1px solid rgba(255,255,255,0.05);
    color: #718096;
    font-size: 0.85rem;
}
.footer-credit {
    font-style: italic;
    color: rgba(255,255,255,0.2);
}

@media (max-width: 991px) {
    .main-footer { text-align: center; }
    .footer-brand { justify-content: center; }
    .footer-heading::after { left: 50%; transform: translateX(-50%); }
    .footer-socials { justify-content: center; }
    .contact-item { justify-content: center; }
    .footer-desc { max-width: 100%; }
}
</style>