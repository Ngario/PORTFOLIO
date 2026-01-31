<!-- 
    HEADER COMPONENT
    Purpose: Navigation bar that appears on all pages
    Responsive: Collapses to hamburger menu on mobile
-->

<!-- 
    helper('url') loads CodeIgniter's URL helper
    This gives us functions like current_url(), base_url(), etc.
-->
<?php helper('url'); ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <!-- 
        navbar-expand-lg: Collapse on screens smaller than 992px
        navbar-dark: White text on dark background
        bg-dark: Dark background color
        sticky-top: Stays at top when scrolling
    -->
    
    <div class="container">
        <!-- 
            LOGO / BRAND 
            Clicking this always goes to homepage
        -->
        <a class="navbar-brand fw-bold" href="<?= base_url('/') ?>">
            <i class="fas fa-code me-2"></i>
            <!-- Replace "Your Name" with your actual name -->
            My<span class="text-primary">Portfolio</span>
        </a>
        
        <!-- 
            MOBILE HAMBURGER BUTTON
            Only visible on small screens
            data-bs-toggle: Bootstrap JavaScript handler
            data-bs-target: Which element to show/hide
        -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- 
            NAVIGATION LINKS
            collapse navbar-collapse: Hides on mobile, shows on desktop
        -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- 
                ms-auto: Pushes links to the right
                mb-2 mb-lg-0: Bottom margin on mobile only
            -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                
                <!-- HOME LINK -->
                <li class="nav-item">
                    <!-- 
                        current_url() gets the current page URL
                        We compare it to base_url('/') to highlight active page
                        'active' class adds visual indicator
                    -->
                    <a class="nav-link <?= current_url() === base_url('/') ? 'active' : '' ?>" 
                       href="<?= base_url('/') ?>">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>
                
                <!-- ABOUT LINK -->
                <li class="nav-item">
                    <a class="nav-link <?= current_url() === base_url('about') ? 'active' : '' ?>" 
                       href="<?= base_url('about') ?>">
                        <i class="fas fa-user me-1"></i> About
                    </a>
                </li>
                
                <!-- PROJECTS LINK -->
                <li class="nav-item">
                    <a class="nav-link <?= current_url() === base_url('projects') ? 'active' : '' ?>" 
                       href="<?= base_url('projects') ?>">
                        <i class="fas fa-folder-open me-1"></i> Projects
                    </a>
                </li>
                
                <!-- DOWNLOADS DROPDOWN -->
                <!-- 
                    dropdown: Enables hover/click submenu
                    We'll add more categories dynamically later
                -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="downloadsDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-1"></i> Downloads
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="downloadsDropdown">
                        <li><a class="dropdown-item" href="<?= base_url('downloads') ?>">All Downloads</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('downloads/software') ?>">Software</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('downloads/books') ?>">Books</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('downloads/videos') ?>">Videos</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('downloads/tutorials') ?>">Tutorials</a></li>
                    </ul>
                </li>
                
                <!-- SERVICES LINK -->
                <li class="nav-item">
                    <a class="nav-link <?= current_url() === base_url('services') ? 'active' : '' ?>" 
                       href="<?= base_url('services') ?>">
                        <i class="fas fa-briefcase me-1"></i> Services
                    </a>
                </li>
                
                <!-- BLOG LINK -->
                <li class="nav-item">
                    <a class="nav-link <?= current_url() === base_url('blog') ? 'active' : '' ?>" 
                       href="<?= base_url('blog') ?>">
                        <i class="fas fa-blog me-1"></i> Blog
                    </a>
                </li>
                
                <!-- CONTACT LINK -->
                <li class="nav-item">
                    <a class="nav-link <?= current_url() === base_url('contact') ? 'active' : '' ?>" 
                       href="<?= base_url('contact') ?>">
                        <i class="fas fa-envelope me-1"></i> Contact
                    </a>
                </li>
                
            </ul>
            
            <!-- 
                ==============================================
                USER PROFILE SECTION (SEPARATE FROM NAV LINKS)
                ==============================================
                This section is visually separated and positioned
                on the far right, away from main navigation
            -->
            <ul class="navbar-nav ms-3">
                <!-- ms-3 creates space between nav links and user section -->
                
                <?php if (session()->has('user_id')): ?>
                    <!-- 
                        ==========================================
                        LOGGED IN: Show User Profile with Photo
                        ==========================================
                    -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" 
                           href="#" 
                           id="userDropdown" 
                           role="button" 
                           data-bs-toggle="dropdown" 
                           aria-expanded="false">
                            
                            <!-- User Profile Photo -->
                            <?php 
                            // Get profile photo from session (or use default)
                            $profilePhoto = session()->get('user_photo');
                            
                            // If user has uploaded photo, use it; otherwise use default avatar
                            if (!empty($profilePhoto)): 
                            ?>
                                <!-- User's uploaded photo -->
                                <img src="<?= base_url('uploads/profiles/' . esc($profilePhoto)) ?>" 
                                     alt="Profile Photo" 
                                     class="rounded-circle me-2"
                                     style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #fff;">
                            <?php else: ?>
                                <!-- Default icon if no photo uploaded -->
                                <i class="fas fa-user-circle me-2" style="font-size: 32px;"></i>
                            <?php endif; ?>
                            
                            <!-- User Name -->
                            <span class="d-none d-md-inline">
                                <?= esc(session()->get('user_name')) ?>
                            </span>
                        </a>
                        
                        <!-- Dropdown Menu -->
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <!-- User Info Header -->
                            <li class="dropdown-header">
                                <strong><?= esc(session()->get('user_name')) ?></strong><br>
                                <small class="text-muted"><?= esc(session()->get('user_email')) ?></small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Dashboard Link -->
                            <li>
                                <a class="dropdown-item" href="<?= base_url('dashboard') ?>">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                            </li>
                            
                            <!-- Profile Link -->
                            <li>
                                <a class="dropdown-item" href="<?= base_url('profile') ?>">
                                    <i class="fas fa-user-edit me-2"></i> My Profile
                                </a>
                            </li>
                            
                            <!-- My Downloads Link -->
                            <li>
                                <a class="dropdown-item" href="<?= base_url('my-downloads') ?>">
                                    <i class="fas fa-download me-2"></i> My Downloads
                                </a>
                            </li>
                            
                            <!-- My Orders Link -->
                            <li>
                                <a class="dropdown-item" href="<?= base_url('my-orders') ?>">
                                    <i class="fas fa-shopping-cart me-2"></i> My Orders
                                </a>
                            </li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Settings Link -->
                            <li>
                                <a class="dropdown-item" href="<?= base_url('settings') ?>">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            </li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Logout Link -->
                            <li>
                                <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                
                <?php else: ?>
                    <!-- 
                        ==========================================
                        NOT LOGGED IN: Show Login/Register Buttons
                        ==========================================
                    -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('login') ?>">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ms-2" href="<?= base_url('register') ?>">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                <?php endif; ?>
                
            </ul>
        </div>
    </div>
</nav>
