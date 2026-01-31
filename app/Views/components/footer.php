<!-- 
    FOOTER COMPONENT
    Purpose: Site footer with links, social media, copyright
    Appears on all pages
-->

<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <!-- 
        bg-dark: Dark background
        text-white: White text
        pt-5: Padding top
        pb-3: Padding bottom
        mt-5: Margin top (separates from content)
    -->
    
    <div class="container">
        <!-- 
            FOOTER TOP SECTION
            4 columns on desktop, stack on mobile
        -->
        <div class="row">
            
            <!-- COLUMN 1: About -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3">
                    <i class="fas fa-code me-2"></i>Idd Mumanyi
                </h5>
                <p class="small">
                    Single site showcasing my projects, services, and digital downloads. 
                    Empowering developers and creators with quality resources.
                </p>
                
                <!-- Social Media Icons -->
                <div class="social-links mt-3">
                    <!-- Replace # with your actual social media URLs -->
                    <a href="#" class="text-white me-3" title="Facebook">
                        <i class="fab fa-facebook-f fa-lg"></i>
                    </a>
                    <a href="#" class="text-white me-3" title="Twitter">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/mrambaji-tech-526303375/" class="text-white me-3" title="LinkedIn">
                        <i class="fab fa-linkedin-in fa-lg"></i>
                    </a>
                    <a href="https://github.com/Ngario" class="text-white me-3" title="GitHub">
                        <i class="fab fa-github fa-lg"></i>
                    </a>
                    <a href="#" class="text-white" title="YouTube">
                        <i class="fab fa-youtube fa-lg"></i>
                    </a>
                </div>
            </div>
            
            <!-- COLUMN 2: Quick Links -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <!-- list-unstyled removes bullet points -->
                    <li class="mb-2">
                        <a href="<?= base_url('/') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('about') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>About
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('projects') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Projects
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('services') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Services
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('blog') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Blog
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- COLUMN 3: Resources -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3">Resources</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?= base_url('downloads') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Downloads
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('downloads/software') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Software
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('downloads/books') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Books
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('downloads/tutorials') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Tutorials
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('downloads/videos') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Videos
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- COLUMN 4: Legal & Contact -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3">Legal</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?= base_url('terms') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Terms of Service
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('privacy') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Privacy Policy
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('contact') ?>" class="text-white-50 text-decoration-none">
                            <i class="fas fa-chevron-right me-2"></i>Contact Me
                        </a>
                    </li>
                </ul>
                
                <!-- Contact Info -->
                <div class="mt-4">
                    <h6 class="text-uppercase">Contact</h6>
                    <p class="small mb-1">
                        <i class="fas fa-envelope me-2"></i>
                        <a href="mailto:ngariomumanyi@gmail.com" class="text-white-50 text-decoration-none">
                            ngariomumanyi@gmail.com
                        </a>
                    </p>
                    <p class="small mb-1">
                        <i class="fas fa-phone me-2"></i>
                        <a href="tel:+254712345678" class="text-white-50 text-decoration-none">
                            +254 796349982
                        </a>
                    </p>
                </div>
            </div>
            
        </div>
        
        <!-- FOOTER BOTTOM: Copyright -->
        <hr class="bg-secondary">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="small mb-0">
                    &copy; <?= date('Y') ?> Idd Mumanyi. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="small mb-0">
                    Built with <i class="fas fa-heart text-danger"></i> Mrambajitech
                </p>
            </div>
        </div>
    </div>
</footer>
