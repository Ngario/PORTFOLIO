<!-- 
    HOMEPAGE VIEW
    Purpose: Main landing page with all sections
    Data received from controller: $projects, $services, $blogs, $aboutPage
-->

<!-- Extend the main layout -->
<?= $this->extend('layouts/main') ?>

<!-- Set page title (overrides default in layout) -->
<?= $this->section('title') ?>
Home - Idd Mumanyi Portfolio
<?= $this->endSection() ?>

<!-- Set meta description for SEO -->
<?= $this->section('description') ?>
Welcome to my professional portfolio. Explore my projects, services, downloads, and blog posts.
<?= $this->endSection() ?>

<!-- Main content section -->
<?= $this->section('content') ?>

<!-- ============================================
     SECTION 1: HERO SECTION
     ============================================
     Purpose: First impression, call-to-action
     Design: Full-width gradient background
-->
<section class="hero-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Main Headline -->
                <h1 class="display-3 fw-bold mb-3 animate-on-scroll">
                    Welcome to My Portfolio
                </h1>
                
                <!-- Subheadline -->
                <p class="lead mb-4 animate-on-scroll">
                    Full-Stack Developer | Graphics Designer | Tech Artist
                </p>
                
                <!-- Typed effect placeholder (we'll add JS for this later) -->
                <p class="h5 mb-5 animate-on-scroll">
                    I build <span class="text-warning fw-bold">modern web applications</span>, 
                    create <span class="text-warning fw-bold">digital products</span>, and 
                    share <span class="text-warning fw-bold">valuable content</span>
                </p>
                
                <!-- Call-to-Action Buttons -->
                <div class="animate-on-scroll">
                    <a href="<?= base_url('projects') ?>" class="btn btn-light btn-lg me-2">
                        <i class="fas fa-folder-open me-2"></i>View Projects
                    </a>
                    <a href="<?= base_url('download-cv') ?>" class="btn btn-warning btn-lg me-2">
                        <i class="fas fa-file-arrow-down me-2"></i>Download CV
                    </a>
                    <a href="<?= base_url('contact') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>Contact Me
                    </a>
                </div>
                
                <!-- Scroll indicator -->
                <div class="mt-5">
                    <a href="#about" class="text-white">
                        <i class="fas fa-chevron-down fa-2x"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 2: ABOUT PREVIEW
     ============================================
     Purpose: Brief introduction
     Data: From $aboutPage (pages table)
-->
<section id="about" class="section">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left: Image -->
            <div class="col-lg-5 mb-4 mb-lg-0 animate-on-scroll">
                <!-- Replace with actual profile photo -->
                <img src="<?= base_url('images/profile/profilemine.jpg') ?>" 
                     alt="Profile Photo" 
                     class="img-fluid rounded-circle shadow"
                     style="max-width: 350px;"
                     onerror="this.src='https://via.placeholder.com/350x350?text=Your+Photo'">
            </div>
            
            <!-- Right: About Text -->
            <div class="col-lg-7 animate-on-scroll">
                <h2 class="section-title">About Me</h2>
                <p class="section-subtitle">Get to know me better</p>
                
                <!-- 
                    Display content from database if available
                    Otherwise show placeholder text
                    esc() function prevents XSS attacks
                -->
                <?php if (isset($aboutPage) && !empty($aboutPage['content'])): ?>
                    <div class="mb-4">
                        <?= $aboutPage['content'] ?>
                    </div>
                <?php else: ?>
                    <p class="lead">
                        Hi! I'm a passionate full-stack developer with expertise in building 
                        modern web applications. I love turning ideas into reality through 
                        clean, efficient code.
                    </p>
                    
                    <p>
                        With years of experience in web development, I specialize in 
                        <strong>PHP, CodeIgniter, JavaScript, MySQL, PostgresSQL</strong>, and modern 
                        frontend frameworks. I'm dedicated to creating user-friendly, 
                        responsive, and scalable applications. I'm also a graphics designer in Adobe Photoshop and Illustrator.
                    </p>
                <?php endif; ?>
                
                <!-- Skills badges -->
                <div class="mb-4">
                    <span class="badge bg-primary me-2 mb-2">PHP</span>
                    <span class="badge bg-primary me-2 mb-2">CodeIgniter 4</span>
                    <span class="badge bg-primary me-2 mb-2">JavaScript</span>
                    <span class="badge bg-primary me-2 mb-2">MySQL</span>
                    <span class="badge bg-primary me-2 mb-2">Bootstrap</span>
                    <span class="badge bg-primary me-2 mb-2">Git</span>
                    <span class="badge bg-primary me-2 mb-2">REST API</span>
                </div>
                
                <a href="<?= base_url('about') ?>" class="btn btn-primary">
                    Learn More About Me <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 3: FEATURED PROJECTS
     ============================================
     Purpose: Showcase portfolio work
     Data: $projects array from controller
-->
<section id="projects" class="section section-light">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <h2 class="section-title">Featured Projects</h2>
            <p class="section-subtitle">Check out some of my recent work</p>
        </div>
        
        <div class="row g-4">
            <?php if (isset($projects) && !empty($projects)): ?>
                <!-- Loop through projects from database -->
                <?php foreach ($projects as $project): ?>
                    <div class="col-md-6 col-lg-4 animate-on-scroll">
                        <div class="card custom-card project-card">
                            <!-- Project Image -->
                            <img src="<?= esc($project['thumbnail'] ?? 'https://via.placeholder.com/400x250?text=Project') ?>" 
                                 class="card-img-top" 
                                 alt="<?= esc($project['title']) ?>">
                            
                            <!-- Hover Overlay -->
                            <div class="project-overlay">
                                <div class="project-links">
                                    <?php if (!empty($project['demo_url'])): ?>
                                        <a href="<?= esc($project['demo_url']) ?>" target="_blank" title="View Demo">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($project['github_url'])): ?>
                                        <a href="<?= esc($project['github_url']) ?>" target="_blank" title="View Code">
                                            <i class="fab fa-github"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($project['title']) ?></h5>
                                <p class="card-text text-muted">
                                    <?= esc(substr($project['description'] ?? '', 0, 100)) ?>...
                                </p>
                                
                                <!-- Tech stack badges -->
                                <?php if (!empty($project['tech_stack'])): ?>
                                    <div class="mb-3">
                                        <?php 
                                        $techStack = is_array($project['tech_stack']) ? $project['tech_stack'] : explode(',', $project['tech_stack']);
                                        foreach ($techStack as $tech): 
                                        ?>
                                            <span class="badge badge-custom bg-secondary me-1">
                                                <?= esc(trim($tech)) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?= base_url('projects/' . $project['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Placeholder cards with your actual images -->
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="card custom-card">
                        <img src="<?= base_url('images/projects/portimage.jpg') ?>" 
                             class="card-img-top" 
                             alt="Portfolio Website">
                        <div class="card-body">
                            <h5 class="card-title">Personal Portfolio Website</h5>
                            <p class="card-text text-muted">
                                Modern portfolio website built with CodeIgniter 4, featuring responsive design and dynamic content management.
                            </p>
                            <span class="badge badge-custom bg-secondary me-1">PHP</span>
                            <span class="badge badge-custom bg-secondary me-1">CodeIgniter</span>
                            <span class="badge badge-custom bg-secondary me-1">MySQL</span>
                            <span class="badge badge-custom bg-secondary me-1">Bootstrap</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="card custom-card">
                        <img src="<?= base_url('images/projects/advnaturesarina.png') ?>" 
                             class="card-img-top" 
                             alt="E-commerce Platform">
                        <div class="card-body">
                            <h5 class="card-title">Adventure Booking Platform</h5>
                            <p class="card-text text-muted">
                                Full-featured online adveture booking platform with payment integration, inventory management, and order tracking system.
                            </p>
                            <span class="badge badge-custom bg-secondary me-1">React</span>
                            <span class="badge badge-custom bg-secondary me-1">PostgresQL</span>
                            <span class="badge badge-custom bg-secondary me-1">TypeScript</span>
                            <span class="badge badge-custom bg-secondary me-1">Figma</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="card custom-card">
                        <img src="<?= base_url('images/projects/sems.jpg') ?>" 
                             class="card-img-top" 
                             alt="Mobile App Design">
                        <div class="card-body">
                            <h5 class="card-title">School Exam Management System</h5>
                            <p class="card-text text-muted">
                                Modern school exam management system with focus on user experience and accessibility.
                            </p>
                            <span class="badge badge-custom bg-secondary me-1">UI/UX</span>
                            <span class="badge badge-custom bg-secondary me-1">Node.js</span>
                            <span class="badge badge-custom bg-secondary me-1">Typescript</span>
                            <span class="badge badge-custom bg-secondary me-1">PostgresQL</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- View All Projects Button -->
        <div class="text-center mt-5">
            <a href="<?= base_url('projects') ?>" class="btn btn-primary btn-lg">
                View All Projects <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 4: SERVICES
     ============================================
     Purpose: What you offer
     Data: $services array from controller
-->
<section id="services" class="section">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <h2 class="section-title">My Services</h2>
            <p class="section-subtitle">What I can do for you</p>
        </div>
        
        <div class="row g-4">
            <?php if (isset($services) && !empty($services)): ?>
                <!-- Loop through services from database -->
                <?php foreach ($services as $service): ?>
                    <div class="col-md-6 col-lg-4 animate-on-scroll">
                        <div class="service-card shadow">
                            <div class="service-icon">
                                <i class="<?= esc($service['icon'] ?? 'fas fa-cogs') ?>"></i>
                            </div>
                            <h4><?= esc($service['title']) ?></h4>
                            <p class="text-muted">
                                <?= esc($service['description']) ?>
                            </p>
                            <?php if (!empty($service['price'])): ?>
                                <p class="fw-bold text-primary">
                                    Starting at KSh <?= number_format($service['price'], 2) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Placeholder services -->
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="service-card shadow">
                        <div class="service-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h4>Web Development</h4>
                        <p class="text-muted">
                            Custom web applications built with modern technologies and best practices.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="service-card shadow">
                        <div class="service-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4>Responsive Design</h4>
                        <p class="text-muted">
                            Mobile-friendly designs that look great on all devices and screen sizes.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="service-card shadow">
                        <div class="service-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <h4>Database Design</h4>
                        <p class="text-muted">
                            Efficient database architecture for scalable and secure applications.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- View All Services Button -->
        <div class="text-center mt-5">
            <a href="<?= base_url('services') ?>" class="btn btn-primary btn-lg">
                View All Services <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 5: LATEST BLOG POSTS
     ============================================
     Purpose: Content marketing, SEO
     Data: $blogs array from controller
-->
<section id="blogs" class="section section-light">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <h2 class="section-title">Latest Blog Posts</h2>
            <p class="section-subtitle">Thoughts, tutorials, and insights</p>
        </div>
        
        <div class="row g-4">
            <?php if (isset($blogs) && !empty($blogs)): ?>
                <!-- Loop through blog posts from database -->
                <?php foreach ($blogs as $blog): ?>
                    <div class="col-md-6 col-lg-4 animate-on-scroll">
                        <div class="card custom-card">
                            <img src="<?= esc($blog['featured_image'] ?? 'https://via.placeholder.com/400x250?text=Blog+Post') ?>" 
                                 class="card-img-top" 
                                 alt="<?= esc($blog['title']) ?>">
                            <div class="card-body">
                                <!-- Category badge -->
                                <?php if (!empty($blog['category_name'])): ?>
                                    <span class="badge bg-success mb-2">
                                        <?= esc($blog['category_name']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <h5 class="card-title"><?= esc($blog['title']) ?></h5>
                                
                                <p class="card-text text-muted small">
                                    <i class="fas fa-calendar me-2"></i>
                                    <?= date('M d, Y', strtotime($blog['published_at'] ?? $blog['created_at'])) ?>
                                </p>
                                
                                <p class="card-text">
                                    <?= esc(substr(strip_tags($blog['content']), 0, 120)) ?>...
                                </p>
                                
                                <a href="<?= base_url('blog/' . $blog['slug']) ?>" class="btn btn-sm btn-outline-primary">
                                    Read More <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Placeholder blog posts with your actual images -->
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="card custom-card">
                        <img src="<?= base_url('images/blog/Ai.jpg') ?>" 
                             class="card-img-top" 
                             alt="AI and Machine Learning">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">Technology</span>
                            <h5 class="card-title">The Future of AI in our worplace</h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-calendar me-2"></i><?= date('M d, Y') ?>
                            </p>
                            <p class="card-text">
                                Exploring how AI is transforming the way we work and live in our workplaces.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="card custom-card">
                        <img src="<?= base_url('images/blog/financial.jpg') ?>" 
                             class="card-img-top" 
                             alt="Financial Management">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">Finance</span>
                            <h5 class="card-title">Smart Financial Planning for young Kenyans</h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-calendar me-2"></i><?= date('M d, Y') ?>
                            </p>
                            <p class="card-text">
                                Essential financial tips and strategies for young Kenyans to manage income and grow wealth.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="card custom-card">
                        <img src="<?= base_url('images/blog/food.jpg') ?>" 
                             class="card-img-top" 
                             alt="Cooking and Nutrition">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">Health & Lifestyle</span>
                            <h5 class="card-title">Healthy Eating Habits for all </h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-calendar me-2"></i><?= date('M d, Y') ?>
                            </p>
                            <p class="card-text">
                                Quick and nutritious diet ideas, recipes and exercise patterns for all classes of people.
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- View All Blog Posts Button -->
        <div class="text-center mt-5">
            <a href="<?= base_url('blog') ?>" class="btn btn-primary btn-lg">
                View All Posts <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 6: CONTACT CTA
     ============================================
     Purpose: Lead generation
-->
<section id="contact-cta" class="section bg-primary text-white text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 animate-on-scroll">
                <h2 class="display-5 fw-bold mb-3">Ready to Work Together?</h2>
                <p class="lead mb-4">
                    Let's discuss your project and bring your ideas to life. 
                    I'm always open to new opportunities and collaborations.
                </p>
                <a href="<?= base_url('contact') ?>" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope me-2"></i>Get In Touch
                </a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<!-- Additional page-specific scripts -->
<?= $this->section('scripts') ?>
<script>
    // Add any homepage-specific JavaScript here
    console.log('Homepage loaded successfully!');
</script>
<?= $this->endSection() ?>
