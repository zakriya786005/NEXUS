<?php
// Safety net: ensure homepage never renders blank due to an uncaught runtime error.
// In production, error display should remain OFF.
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

$currentPage = 'home';
$pageTitle = 'NEXUS COMPUTER INSTITUTE - Empowering Future Technology Leaders';
$pageDesc = 'Premium IT education and professional skills training. Web Development, AI, Python, Graphic Design, Digital Marketing courses in Lahore, Pakistan.';


require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch data
$courses = [];
$notices = [];
$teachers = [];
$gallery = [];
$stats = ['students' => 0, 'courses' => 0, 'certified' => 0, 'teachers' => 0];

try {
    $stmt = db()->query("SELECT * FROM courses WHERE status = 'active' ORDER BY enrolled_count DESC LIMIT 6");
    $courses = $stmt->fetchAll();

    $stmt = db()->query("SELECT * FROM notices WHERE status = 'active' ORDER BY created_at DESC LIMIT 4");
    $notices = $stmt->fetchAll();

    $stmt = db()->query("SELECT * FROM teachers WHERE status = 'active' ORDER BY name ASC LIMIT 6");
    $teachers = $stmt->fetchAll();

    $stmt = db()->query("SELECT * FROM gallery WHERE status = 'active' ORDER BY created_at DESC LIMIT 9");
    $gallery = $stmt->fetchAll();

    $stats['students'] = (int) db()->query("SELECT COUNT(*) FROM students WHERE status = 'active'")->fetchColumn();
    $stats['courses'] = (int) db()->query("SELECT COUNT(*) FROM courses WHERE status = 'active'")->fetchColumn();
    $stats['certified'] = (int) db()->query("SELECT COUNT(*) FROM certificates WHERE status = 'active'")->fetchColumn();
    $stats['teachers'] = (int) db()->query("SELECT COUNT(*) FROM teachers WHERE status = 'active'")->fetchColumn();
} catch (Exception $e) {
    // Log error for debugging; don't break page rendering
    error_log('Homepage DB Error: ' . $e->getMessage());
}
?>

<!-- ============================================
     HERO SECTION
     ============================================ -->
<section class="hero">
    <div class="hero-bg"></div>
    <canvas id="particles-canvas"></canvas>

    <!-- Floating Tech Icons -->
    <div class="floating-icons">
        <i class="fab fa-html5 floating-icon"></i>
        <i class="fab fa-css3-alt floating-icon"></i>
        <i class="fab fa-js floating-icon"></i>
        <i class="fab fa-python floating-icon"></i>
        <i class="fab fa-react floating-icon"></i>
        <i class="fab fa-php floating-icon"></i>
        <i class="fab fa-node-js floating-icon"></i>
        <i class="fab fa-git-alt floating-icon"></i>
    </div>

    <div class="hero-content">
        <div class="hero-badge" data-aos="fade-down" data-aos-delay="200">
            <i class="fas fa-star"></i>
            <span>Pakistan's Premier IT Training Institute</span>
        </div>

        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="400">
            NEXUS COMPUTER<br>INSTITUTE
        </h1>

        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="600">
            <span class="typing-text" data-words='["Empowering Future Technology Leaders","Master Web Development & AI","Build Your Career in Tech","Learn From Industry Experts"]'></span>
        </p>

        <div class="hero-buttons" data-aos="fade-up" data-aos-delay="800">
            <a href="<?= assetUrl('admission.php') ?>" class="btn btn-primary">
                <i class="fas fa-rocket"></i> Apply Now
            </a>
            <a href="<?= assetUrl('courses.php') ?>" class="btn btn-outline">
                <i class="fas fa-book-open"></i> View Courses
            </a>
        </div>
    </div>
</section>

<!-- ============================================
     STATISTICS SECTION
     ============================================ -->
<section class="section" style="padding-top: 40px;">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-number" data-count="<?= max($stats['students'], 2500) ?>" data-suffix="+">0</div>
                <div class="stat-label">Students Enrolled</div>
            </div>
            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-icon"><i class="fas fa-laptop-code"></i></div>
                <div class="stat-number" data-count="<?= max($stats['courses'], 25) ?>" data-suffix="+">0</div>
                <div class="stat-label">Courses Available</div>
            </div>
            <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-icon"><i class="fas fa-certificate"></i></div>
                <div class="stat-number" data-count="<?= max($stats['certified'], 1800) ?>" data-suffix="+">0</div>
                <div class="stat-label">Certified Students</div>
            </div>
            <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-number" data-count="<?= max($stats['teachers'], 50) ?>" data-suffix="+">0</div>
                <div class="stat-label">Expert Teachers</div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     FEATURED COURSES
     ============================================ -->
<section class="section" id="courses">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-badge"><i class="fas fa-graduation-cap"></i> Our Programs</div>
            <h2 class="section-title">Featured <span>Courses</span></h2>
            <p class="section-desc">Explore our industry-leading courses designed to kickstart your tech career</p>
        </div>

        <div class="grid-3">
            <?php foreach ($courses as $course): ?>
                <div data-aos="fade-up">
                    <div class="course-card">
                        <div class="card-image">
                            <?php if ($course['image']): ?>
                                <img src="<?= assetUrl('uploads/courses/' . $course['image']) ?>" alt="<?= sanitize($course['title']) ?>" loading="lazy">
                            <?php else: ?>
                                <i class="fas fa-laptop-code placeholder-icon"></i>
                            <?php endif; ?>
                            <span class="card-badge"><?= sanitize($course['level'] ?? 'Beginner') ?></span>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?= sanitize($course['title']) ?></h3>
                            <p class="card-desc"><?= sanitize($course['description'] ?? '') ?></p>
                            <div class="card-meta">
                                <span><i class="fas fa-clock"></i> <?= sanitize($course['duration'] ?? '') ?></span>
                                <span><i class="fas fa-user"></i> <?= sanitize($course['instructor'] ?? '') ?></span>
                            </div>
                            <div class="card-fee">PKR <?= number_format((float) $course['fee'], 0) ?></div>
                            <a href="<?= assetUrl('admission.php?course=' . $course['id']) ?>" class="card-btn">
                                <i class="fas fa-arrow-right"></i> Enroll Now
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($courses)): ?>
                <?php
                $sampleCourses = [
                    ['title' => 'Web Development', 'duration' => '6 Months', 'fee' => '25,000', 'icon' => 'fa-code', 'level' => 'Beginner', 'desc' => 'Master HTML, CSS, JavaScript, PHP and modern web frameworks.'],
                    ['title' => 'Graphic Designing', 'duration' => '4 Months', 'fee' => '18,000', 'icon' => 'fa-palette', 'level' => 'Beginner', 'desc' => 'Learn Photoshop, Illustrator, branding and visual design.'],
                    ['title' => 'AI & Machine Learning', 'duration' => '8 Months', 'fee' => '45,000', 'icon' => 'fa-robot', 'level' => 'Advanced', 'desc' => 'Deep dive into neural networks, NLP and computer vision.'],
                    ['title' => 'Python Programming', 'duration' => '5 Months', 'fee' => '22,000', 'icon' => 'fa-python fab', 'level' => 'Beginner', 'desc' => 'From basics to Django, Flask and data science with Python.'],
                    ['title' => 'Digital Marketing', 'duration' => '3 Months', 'fee' => '15,000', 'icon' => 'fa-bullhorn', 'level' => 'Beginner', 'desc' => 'SEO, social media, Google Ads and content marketing mastery.'],
                    ['title' => 'Office Management', 'duration' => '3 Months', 'fee' => '12,000', 'icon' => 'fa-file-word', 'level' => 'Beginner', 'desc' => 'Complete MS Office suite and professional productivity.'],
                ];
                foreach ($sampleCourses as $sc):
                ?>
                <div data-aos="fade-up">
                    <div class="course-card">
                        <div class="card-image">
                            <i class="fas <?= $sc['icon'] ?> placeholder-icon"></i>
                            <span class="card-badge"><?= $sc['level'] ?></span>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?= $sc['title'] ?></h3>
                            <p class="card-desc"><?= $sc['desc'] ?></p>
                            <div class="card-meta">
                                <span><i class="fas fa-clock"></i> <?= $sc['duration'] ?></span>
                                <span><i class="fas fa-user"></i> Expert Instructor</span>
                            </div>
                            <div class="card-fee">PKR <?= $sc['fee'] ?></div>
                            <a href="<?= assetUrl('admission.php') ?>" class="card-btn"><i class="fas fa-arrow-right"></i> Enroll Now</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 48px;" data-aos="fade-up">
            <a href="<?= assetUrl('courses.php') ?>" class="btn btn-outline">
                <i class="fas fa-th-large"></i> View All Courses
            </a>
        </div>
    </div>
</section>

<!-- ============================================
     WHY CHOOSE US
     ============================================ -->
<section class="section" style="background: rgba(17, 34, 64, 0.3);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-badge"><i class="fas fa-award"></i> Why Us</div>
            <h2 class="section-title">Why Choose <span>NEXUS</span></h2>
            <p class="section-desc">We provide world-class education with industry-focused training</p>
        </div>

        <div class="grid-3">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <h3>Expert Instructors</h3>
                <p>Learn from industry professionals with years of real-world experience</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon"><i class="fas fa-laptop-code"></i></div>
                <h3>Hands-on Projects</h3>
                <p>Build real projects and portfolios that impress employers</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon"><i class="fas fa-certificate"></i></div>
                <h3>Certified Programs</h3>
                <p>Earn industry-recognized certificates with QR verification</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon"><i class="fas fa-briefcase"></i></div>
                <h3>Job Placement</h3>
                <p>Career counseling and job placement assistance for graduates</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-icon"><i class="fas fa-wifi"></i></div>
                <h3>Modern Lab</h3>
                <p>State-of-the-art computer labs with latest software and tools</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                <div class="feature-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <h3>Affordable Fees</h3>
                <p>Quality education at competitive prices with installment options</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     TEACHER SECTION
     ============================================ -->
<?php if (!empty($teachers)): ?>
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-badge"><i class="fas fa-chalkboard-teacher"></i> Our Team</div>
            <h2 class="section-title">Expert <span>Instructors</span></h2>
            <p class="section-desc">Meet our passionate educators who bring industry expertise to the classroom</p>
        </div>

        <div class="grid-3">
            <?php foreach ($teachers as $t): ?>
            <div data-aos="fade-up">
                <div class="teacher-card">
                    <div class="teacher-image">
                        <?php if (!empty($t['image'])): ?>
                            <img src="<?= assetUrl('uploads/teachers/' . $t['image']) ?>" alt="<?= sanitize($t['name']) ?>" loading="lazy" onerror="this.parentElement.innerHTML='<i class=\'fas fa-user placeholder-icon\'></i>'">
                        <?php else: ?>
                            <i class="fas fa-user placeholder-icon"></i>
                        <?php endif; ?>
                        <div class="teacher-overlay">
                            <div class="teacher-social">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="teacher-info">
                        <h3><?= sanitize($t['name']) ?></h3>
                        <div class="designation"><?= sanitize($t['designation'] ?? 'Instructor') ?></div>
                        <div class="skills"><?= sanitize($t['qualification'] ?? '') ?> | <?= sanitize($t['experience'] ?? '5+ Years') ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================
     GALLERY SECTION
     ============================================ -->
<?php if (!empty($gallery)): ?>
<section class="section" style="background: rgba(17, 34, 64, 0.3);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-badge"><i class="fas fa-images"></i> Gallery</div>
            <h2 class="section-title">Campus <span>Gallery</span></h2>
            <p class="section-desc">Take a glimpse at our modern facilities, events, and student activities</p>
        </div>

        <div class="filter-buttons" data-aos="fade-up">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="campus">Campus</button>
            <button class="filter-btn" data-filter="events">Events</button>
            <button class="filter-btn" data-filter="labs">Labs</button>
        </div>

        <div class="gallery-grid">
            <?php foreach ($gallery as $g): ?>
            <div class="gallery-item" data-category="<?= sanitize($g['category'] ?? 'campus') ?>" data-aos="fade-up">
                <img src="<?= assetUrl('uploads/gallery/' . $g['image']) ?>" alt="<?= sanitize($g['title'] ?? 'NEXUS Gallery') ?>" loading="lazy">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================
     STUDENT SUCCESS STORIES
     ============================================ -->
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-badge"><i class="fas fa-trophy"></i> Success Stories</div>
            <h2 class="section-title">Student <span>Achievements</span></h2>
            <p class="section-desc">Our graduates are working at top companies and running successful businesses</p>
        </div>
    </div>

    <div class="success-carousel" data-aos="fade-up">
        <div class="success-track">
            <?php
            $successStories = [
                ['name' => 'Ahmed Khan', 'initials' => 'AK', 'course' => 'Web Development', 'text' => 'Landed a senior developer role at a top tech company within 3 months of graduating.', 'achievement' => 'Senior Developer at TechVentures'],
                ['name' => 'Fatima Zahra', 'initials' => 'FZ', 'course' => 'AI & Machine Learning', 'text' => 'Now leading an ML team and building AI solutions for healthcare.', 'achievement' => 'ML Lead at HealthAI Solutions'],
                ['name' => 'Usman Ali', 'initials' => 'UA', 'course' => 'Graphic Designing', 'text' => 'Started my own design agency and now serve clients worldwide.', 'achievement' => 'Founder - CreativeStudio PK'],
                ['name' => 'Ayesha Siddiqui', 'initials' => 'AS', 'course' => 'Digital Marketing', 'text' => 'Built a 6-figure marketing agency helping businesses grow online.', 'achievement' => 'CEO at DigitalGrowth Agency'],
                ['name' => 'Hassan Raza', 'initials' => 'HR', 'course' => 'Python Programming', 'text' => 'Working as a data scientist analyzing millions of data points daily.', 'achievement' => 'Data Scientist at AnalyticsPro'],
                ['name' => 'Zainab Malik', 'initials' => 'ZM', 'course' => 'Web Development', 'text' => 'Freelancing on Upwork with over $50K earned in the first year.', 'achievement' => 'Top Rated Plus on Upwork'],
            ];
            // Duplicate for infinite scroll effect
            $allStories = array_merge($successStories, $successStories);
            foreach ($allStories as $s):
            ?>
            <div class="success-card">
                <div class="success-header">
                    <div class="success-avatar"><?= $s['initials'] ?></div>
                    <div>
                        <h4><?= $s['name'] ?></h4>
                        <div class="success-course"><?= $s['course'] ?></div>
                    </div>
                </div>
                <p class="success-text">"<?= $s['text'] ?>"</p>
                <div class="success-achievement">
                    <i class="fas fa-trophy"></i> <?= $s['achievement'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================
     TESTIMONIALS
     ============================================ -->
<section class="section" style="background: rgba(17, 34, 64, 0.3);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-badge"><i class="fas fa-quote-left"></i> Testimonials</div>
            <h2 class="section-title">What Our <span>Students Say</span></h2>
            <p class="section-desc">Hear from our successful graduates about their experience</p>
        </div>

        <div class="grid-3">
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
                <div class="star-rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                <p class="quote-text">"NEXUS transformed my career. The web development course gave me practical skills that landed me a job within 2 months of graduation."</p>
                <div class="quote-author">
                    <div class="avatar">AH</div>
                    <div class="author-info">
                        <h4>Ali Hassan</h4>
                        <span>Web Developer at TechSoft</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                <div class="star-rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                <p class="quote-text">"The AI & Machine Learning course was exceptional. The instructors are true experts and the curriculum is up-to-date with industry standards."</p>
                <div class="quote-author">
                    <div class="avatar">SF</div>
                    <div class="author-info">
                        <h4>Sara Fatima</h4>
                        <span>ML Engineer at DataCorp</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="300">
                <div class="star-rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                <p class="quote-text">"Best investment I made in my career. The graphic design course helped me start my own freelance business successfully."</p>
                <div class="quote-author">
                    <div class="avatar">UR</div>
                    <div class="author-info">
                        <h4>Usman Raza</h4>
                        <span>Freelance Designer</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     LATEST NOTICES
     ============================================ -->
<?php if (!empty($notices)): ?>
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-badge"><i class="fas fa-bullhorn"></i> Announcements</div>
            <h2 class="section-title">Latest <span>Notices</span></h2>
        </div>

        <div style="max-width: 700px; margin: 0 auto;">
            <?php foreach ($notices as $notice): ?>
                <div class="notice-card notice-priority-<?= sanitize($notice['priority'] ?? 'medium') ?>" data-aos="fade-up">
                    <div class="notice-icon">
                        <i class="fas fa-<?= ($notice['priority'] === 'urgent') ? 'exclamation-triangle' : 'bullhorn' ?>"></i>
                    </div>
                    <div>
                        <h4><?= sanitize($notice['title']) ?></h4>
                        <p><?= sanitize(mb_substr($notice['content'] ?? '', 0, 120)) ?>...</p>
                        <div class="notice-date"><i class="fas fa-calendar-alt"></i> <?= timeAgo($notice['created_at']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================
     GOOGLE MAP
     ============================================ -->
<section class="section" style="background: rgba(17, 34, 64, 0.3);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-badge"><i class="fas fa-map-marker-alt"></i> Find Us</div>
            <h2 class="section-title">Our <span>Location</span></h2>
            <p class="section-desc">Visit us at Main Boulevard, Lahore, Pakistan</p>
        </div>
        <div class="map-container" data-aos="fade-up">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3401.7!2d74.3587!3d31.5204!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzHCsDMxJzEzLjQiTiA3NMKwMjEnMzEuMyJF!5e0!3m2!1sen!2spk!4v1700000000000!5m2!1sen!2spk" width="100%" height="400" style="border:0; border-radius: var(--radius);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="NEXUS Computer Institute Location"></iframe>
        </div>
    </div>
</section>

<!-- ============================================
     CTA SECTION
     ============================================ -->
<section class="cta-section">
    <div class="container" style="position:relative; z-index:1;">
        <div data-aos="fade-up">
            <h2 class="section-title">Ready to Start Your <span>Tech Journey?</span></h2>
            <p class="section-desc" style="margin-bottom: 40px;">Join thousands of successful graduates who transformed their careers at NEXUS</p>
            <div class="hero-buttons">
                <a href="<?= assetUrl('admission.php') ?>" class="btn btn-primary">
                    <i class="fas fa-rocket"></i> Apply Now
                </a>
                <a href="<?= assetUrl('contact.php') ?>" class="btn btn-outline">
                    <i class="fas fa-phone"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
