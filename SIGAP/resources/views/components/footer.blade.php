<footer class="sigap-footer">
    <div class="container">
        <div class="row g-4">

            {{-- Brand column --}}
            <div class="col-lg-4 col-md-6">
                <div class="footer-logo mb-1">
                    <span>SI</span>GAP
                </div>

                <div class="footer-tagline mb-3">
                    Sistem Informasi Gangguan & Aduan Publik
                </div>

                <p style="font-size:.85rem; color:rgba(255,255,255,.55); line-height:1.65; max-width:280px;">
                    Platform digital untuk melaporkan dan memantau kondisi fasilitas publik secara transparan, agar pemerintah dapat menindaklanjuti dengan lebih cepat dan terukur.
                </p>

                {{-- Social --}}
                <div class="mt-3">
                    <span class="social-btn" title="Twitter/X"><i class="bi bi-twitter-x"></i></span>
                    <span class="social-btn" title="Instagram"><i class="bi bi-instagram"></i></span>
                    <span class="social-btn" title="YouTube"><i class="bi bi-youtube"></i></span>
                    <span class="social-btn" title="LinkedIn"><i class="bi bi-linkedin"></i></span>
                </div>
            </div>

            {{-- Tentang Kami --}}
            <div class="col-lg-2 col-md-6 col-6">
                <div class="footer-heading">Tentang Kami</div>
                <div class="footer-link">Profil SIGAP</div>
                <div class="footer-link">Keamanan</div>
                <div class="footer-link">Hubungi Kami</div>
                <div class="footer-link">FAQ</div>
            </div>

            {{-- Tautan Lainnya --}}
            <div class="col-lg-3 col-md-6 col-6">
                <div class="footer-heading">Tautan Lainnya</div>
                <div class="footer-link">Rekap Laporan Publik</div>
                <div class="footer-link">Monitoring Proyek</div>
                <div class="footer-link">Laporan Warga</div>
                <div class="footer-link">Buat Laporan Baru</div>
            </div>

            {{-- Kontak --}}
            <div class="col-lg-3 col-md-6">
                <div class="footer-heading">Kontak</div>

                <div class="d-flex align-items-start gap-2 mb-2"
                     style="font-size:.85rem; color:rgba(255,255,255,.60);">
                    <i class="bi bi-envelope mt-1"
                       style="color:var(--brown-accent);"></i>
                    <span>sigap@pemerintah.go.id</span>
                </div>

                <div class="d-flex align-items-start gap-2 mb-2"
                     style="font-size:.85rem; color:rgba(255,255,255,.60);">
                    <i class="bi bi-telephone mt-1"
                       style="color:var(--brown-accent);"></i>
                    <span>(0341) 123-4567</span>
                </div>

                <div class="d-flex align-items-start gap-2"
                     style="font-size:.85rem; color:rgba(255,255,255,.60);">
                    <i class="bi bi-geo-alt mt-1"
                       style="color:var(--brown-accent);"></i>
                    <span>Jl. Veteran No. 1, Malang, Jawa Timur</span>
                </div>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-center">
            <span>© {{ date('Y') }} SIGAP. All rights reserved.</span>
            <span>Sistem Informasi Gangguan & Aduan Publik</span>
        </div>
    </div>
</footer>

