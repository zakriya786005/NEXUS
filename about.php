<?php
declare(strict_types=1);

$currentPage = 'about';
$pageTitle = 'About Us - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Learn about NEXUS Computer Institute - our mission, vision, values, team, and journey in IT education.';

// Swiper CSS + navbar offset — MUST be set BEFORE header.php
$extraCSS = "\n<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css\">\n<style>\n    .nx-hero{padding-top:120px !important;}\n</style>\n";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch teachers (existing functionality)
$teachers = [];
try {
    $stmt = db()->query("SELECT * FROM teachers WHERE status='active' ORDER BY name ASC LIMIT 12");
    $teachers = $stmt->fetchAll();
} catch (Exception $e) {
    $teachers = [];
}

// Helpers
$imgFallback = assetUrl('assets/images/default-teacher.png');
?>
<?= '' /* extraCSS already rendered in <head> via header.php */ ?>

<!-- About page meta/scripts must be inside <head>. header.php already renders <head>.
     Kept only inline styles here to avoid layout/DOM issues. -->

<style>
    /* Keep styles self-contained to avoid breaking existing theme */
    .nx-container{max-width:1200px;margin:0 auto;padding:0 20px;}

    /* SECTION 1: HERO */
    .nx-hero{position:relative;overflow:hidden;padding:100px 0 60px;background:linear-gradient(180deg, rgba(10,25,47,0.9), rgba(10,25,47,0.65));}
    .nx-hero-bg{
        position:absolute;inset:0;pointer-events:none;z-index:0;
        background:
            radial-gradient(1200px 600px at 20% 20%, rgba(0,212,255,0.18), transparent 60%),
            radial-gradient(900px 500px at 80% 30%, rgba(100,255,218,0.14), transparent 55%);
        filter:saturate(1.05);
    }
    .nx-hero::after{content:'';position:absolute;inset:0;pointer-events:none;z-index:1;background:linear-gradient(90deg, rgba(0,212,255,0.10), rgba(100,255,218,0.05));}
    #nx-particles{position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:2;opacity:.95;}

    .nx-hero-content{position:relative;z-index:3;}
    .nx-breadcrumb{
        display:inline-flex;gap:10px;align-items:center;
        padding:10px 14px;border-radius:999px;
        background:rgba(255,255,255,0.06);
        border:1px solid rgba(0,212,255,0.25);
        backdrop-filter:blur(10px);
        -webkit-backdrop-filter:blur(10px);
        box-shadow:0 12px 40px rgba(0,0,0,0.25);
        color:var(--text-muted);
    }
    .nx-neon{color:var(--accent);text-shadow:0 0 22px rgba(0,212,255,0.35);}
    .nx-hero-title{font-size:clamp(2rem,4vw,3.6rem);line-height:1.1;letter-spacing:-0.02em;margin:14px 0 12px;}
    .nx-typing{display:inline-block;border-right:2px solid rgba(0,212,255,0.6);padding-right:8px;white-space:nowrap;overflow:hidden;vertical-align:bottom;}

    .nx-float-icons{position:absolute;top:28px;right:10px;width:280px;height:280px;z-index:3;pointer-events:none;opacity:.95;}
    .nx-float-icons i{position:absolute;font-size:1.4rem;padding:10px;border-radius:14px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);color:rgba(255,255,255,0.92);box-shadow:0 16px 50px rgba(0,0,0,0.25);animation:nx-float 6s ease-in-out infinite;}
    .nx-float-icons i:nth-child(1){left:20px;top:30px;animation-delay:0s;}
    .nx-float-icons i:nth-child(2){left:180px;top:55px;animation-delay:1s;}
    .nx-float-icons i:nth-child(3){left:130px;top:140px;animation-delay:2s;}
    .nx-float-icons i:nth-child(4){left:30px;top:180px;animation-delay:3s;}
    .nx-float-icons i:nth-child(5){left:160px;top:210px;animation-delay:4s;}
    .nx-float-icons i:nth-child(6){left:90px;top:80px;animation-delay:5s;}
    @keyframes nx-float{0%,100%{transform:translateY(0) translateX(0);}50%{transform:translateY(-14px) translateX(8px);}}

    /* SECTION base */
    .nx-section{padding:64px 0;}
    .nx-section-header .nx-badge{display:inline-flex;gap:10px;align-items:center;padding:8px 12px;border-radius:999px;border:1px solid rgba(0,212,255,0.22);background:rgba(0,212,255,0.08);color:var(--accent);font-weight:700;}
    .nx-section-title{font-size:clamp(1.6rem,3vw,2.2rem);margin:12px 0 8px;}
    .nx-section-desc{color:var(--text-muted);line-height:1.8;margin:0;max-width:800px;}

    /* Glass */
    .nx-glass{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);box-shadow:0 10px 30px rgba(0,0,0,0.25);border-radius:18px;}

    /* SECTION 2: Overview */
    .nx-overview{display:grid;grid-template-columns:1.1fr 0.9fr;gap:30px;align-items:start;}
    .nx-overview-image{position:relative;overflow:hidden;min-height:340px;border-radius:18px;border:1px solid rgba(255,255,255,0.10);background:radial-gradient(800px 400px at 30% 30%, rgba(0,212,255,0.22), transparent 60%),linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));}
    .nx-overview-image img{width:100%;height:100%;object-fit:cover;display:block;transform:scale(1.02);opacity:.92;filter:saturate(1.05) contrast(1.03);}
    .nx-overview-image::after{content:'';position:absolute;inset:0;background:linear-gradient(120deg, rgba(0,212,255,0.12), rgba(100,255,218,0.04));pointer-events:none;}
    .nx-stat-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;}
    .nx-stat{padding:18px;border-radius:16px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.10);transition:transform .25s ease,border-color .25s ease;}
    .nx-stat:hover{transform:translateY(-4px);border-color:rgba(0,212,255,0.35);}
    .nx-stat-value{font-size:1.75rem;font-weight:900;color:var(--accent);}
    .nx-stat-label{margin-top:6px;color:var(--text-muted);font-size:.95rem;}

    /* SECTION 3: Flip Cards — fixed height + modern CSS */
    .nx-flip-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;}
    .nx-flip{perspective:1000px;min-height:240px;}
    .nx-flip-inner{position:relative;width:100%;height:100%;min-height:240px;transform-style:preserve-3d;transition:transform .65s cubic-bezier(.2,.8,.2,1);border-radius:18px;}
    .nx-flip:hover .nx-flip-inner{transform:rotateY(180deg);}
    .nx-flip-front,.nx-flip-back{backface-visibility:hidden;-webkit-backface-visibility:hidden;position:absolute;inset:0;border-radius:18px;padding:24px;display:flex;flex-direction:column;overflow:hidden;}
    .nx-flip-front{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);box-shadow:0 10px 30px rgba(0,0,0,0.25);}
    .nx-flip-back{transform:rotateY(180deg);background:rgba(255,255,255,0.05);border:1px solid rgba(100,255,218,0.22);box-shadow:0 10px 30px rgba(0,0,0,0.25);}
    .nx-flip-icon{width:46px;height:46px;display:grid;place-items:center;border-radius:14px;background:rgba(0,212,255,0.12);border:1px solid rgba(0,212,255,0.22);color:var(--accent);margin-bottom:12px;flex-shrink:0;}
    .nx-flip-title{margin:0 0 8px;font-size:1.05rem;}
    .nx-flip-desc{margin:0;color:var(--text-muted);line-height:1.65;font-size:.92rem;}

    /* SECTION 4: Timeline */
    .nx-timeline{position:relative;padding-left:36px;}
    .nx-timeline::before{content:'';position:absolute;left:14px;top:0;bottom:0;width:2px;background:linear-gradient(to bottom, rgba(0,212,255,0.65), rgba(100,255,218,0.20));}
    .nx-milestone{position:relative;margin:0 0 22px;padding:20px 22px;border-radius:18px;border:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.04);overflow:hidden;transition:border-color .25s ease,transform .25s ease;box-shadow:0 10px 30px rgba(0,0,0,0.25);}
    .nx-milestone:hover{border-color:rgba(0,212,255,0.3);transform:translateX(4px);}
    .nx-milestone::after{content:'';position:absolute;inset:-50% -30%;background:radial-gradient(circle at 30% 30%, rgba(0,212,255,0.08), transparent 50%);pointer-events:none;}
    .nx-milestone::before{content:'';position:absolute;left:-29px;top:24px;width:16px;height:16px;border-radius:50%;background:rgba(0,212,255,0.3);border:2px solid rgba(0,212,255,0.65);box-shadow:0 0 0 5px rgba(0,212,255,0.10);z-index:1;}
    .nx-milestone-year{color:var(--accent);font-weight:900;font-size:1.05rem;}
    .nx-milestone h4{margin:6px 0 8px;font-size:1.1rem;}
    .nx-milestone p{margin:0;color:var(--text-muted);line-height:1.7;}

    /* SECTION 5: Director */
    .nx-director{display:grid;grid-template-columns:0.9fr 1.1fr;gap:26px;align-items:start;}
    .nx-director-img{border-radius:18px;border:1px solid rgba(255,255,255,0.10);overflow:hidden;background:rgba(255,255,255,0.04);}
    .nx-director-img img{width:100%;height:100%;min-height:320px;object-fit:cover;display:block;opacity:.95;}
    .nx-signature{font-family: cursive; font-size:1.1rem; color:rgba(0,212,255,.9);}
    .nx-quote{width:44px;height:44px;border-radius:14px;display:grid;place-items:center;background:rgba(0,212,255,0.12);border:1px solid rgba(0,212,255,0.22);color:var(--accent);margin-bottom:10px;}

    /* SECTION 6: Achievements */
    .nx-ach-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px;}
    .nx-circle{padding:20px;border-radius:18px;border:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.04);text-align:center;transition:border-color .25s ease,transform .25s ease;}
    .nx-circle:hover{border-color:rgba(0,212,255,0.3);transform:translateY(-4px);}
    .nx-circle-title{margin-top:10px;color:rgba(255,255,255,.92);font-weight:800;font-size:.95rem;}

    /* SECTION 7: Instructors */
    .nx-teach-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;}
    .nx-teach-card{padding:18px;border-radius:18px;border:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.04);transition:transform .25s ease,border-color .25s ease,box-shadow .25s ease;box-shadow:0 10px 30px rgba(0,0,0,0.25);}
    .nx-teach-card:hover{transform:translateY(-6px);border-color:rgba(0,212,255,0.35);box-shadow:0 16px 40px rgba(0,0,0,0.3);}
    .nx-teach-top{display:flex;gap:12px;align-items:center;}
    .nx-teach-avatar{width:56px;height:56px;border-radius:16px;border:1px solid rgba(255,255,255,0.12);overflow:hidden;background:rgba(0,212,255,0.08);flex:0 0 auto;}
    .nx-teach-avatar img{width:100%;height:100%;object-fit:cover;display:block;}
    .nx-teach-name{font-weight:900;margin:0;font-size:1rem;}
    .nx-teach-dept{margin-top:3px;color:var(--text-muted);font-size:.9rem;}
    .nx-skill{margin-top:12px;}
    .nx-skill-row{display:flex;justify-content:space-between;align-items:center;color:var(--text-muted);font-size:.85rem;margin-bottom:6px;}
    .nx-bar{height:8px;border-radius:999px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.10);overflow:hidden;}
    .nx-bar > span{display:block;height:100%;border-radius:999px;background:linear-gradient(90deg, rgba(0,212,255,0.6), rgba(100,255,218,0.6));}
    .nx-social{margin-top:12px;display:flex;gap:10px;}

    /* SECTION 8: Testimonials */
    .nx-testimonial-card{padding:24px;border-radius:18px;border:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.05);height:100%;box-shadow:0 10px 30px rgba(0,0,0,0.25);}

    /* SECTION 9: Why Choose */
    .nx-feature-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px;}
    .nx-feature{padding:20px;border-radius:18px;border:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.04);transition:transform .25s ease,border-color .25s ease,box-shadow .25s ease;box-shadow:0 10px 30px rgba(0,0,0,0.25);}
    .nx-feature:hover{transform:translateY(-6px);border-color:rgba(0,212,255,0.35);box-shadow:0 16px 40px rgba(0,0,0,0.3);}
    .nx-feature i{width:44px;height:44px;display:grid;place-items:center;border-radius:14px;background:rgba(0,212,255,0.12);border:1px solid rgba(0,212,255,0.22);color:var(--accent);margin-bottom:12px;}

    /* SECTION 10: CTA */
    .nx-cta{padding:30px;border-radius:24px;border:1px solid rgba(0,212,255,0.25);background:linear-gradient(135deg, rgba(0,212,255,0.14), rgba(100,255,218,0.08));box-shadow:0 20px 80px rgba(0,0,0,0.35);}

    /* Swiper pagination fix */
    .nx-testimonials .swiper-pagination{margin-top:20px;position:relative;}
    .nx-testimonials .swiper-pagination-bullet{background:rgba(0,212,255,0.3);opacity:1;}
    .nx-testimonials .swiper-pagination-bullet-active{background:var(--accent);box-shadow:0 0 8px rgba(0,212,255,0.5);}

    /* SECTION 11/12: responsive */
    @media (max-width: 1024px){
        .nx-overview{grid-template-columns:1fr;}
        .nx-director{grid-template-columns:1fr;}
        .nx-float-icons{display:none;}
    }
    @media (max-width: 768px){
        .nx-section{padding:48px 0;}
        .nx-container{padding:0 16px;}
        .nx-flip-grid{grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;}
        .nx-teach-grid{grid-template-columns:repeat(auto-fit,minmax(240px,1fr));}
        .nx-feature-grid{grid-template-columns:1fr;}
        .nx-ach-grid{grid-template-columns:repeat(auto-fit,minmax(150px,1fr));}
        .nx-cta{padding:22px;}
        .nx-cta h2{font-size:1.5rem !important;}
    }
    @media (max-width: 480px){
        .nx-section{padding:36px 0;}
        .nx-flip-grid{grid-template-columns:1fr;}
        .nx-teach-grid{grid-template-columns:1fr;}
        .nx-ach-grid{grid-template-columns:repeat(2,1fr);}
        .nx-overview{gap:20px;}
        .nx-director{gap:18px;}
        .nx-milestone{padding:16px;}
        .nx-hero{padding:80px 0 40px;}
    }
</style>

<!-- SECTION 1: PAGE HEADER -->
<section class="nx-hero">
    <div class="nx-hero-bg" aria-hidden="true"></div>
    <canvas id="nx-particles" aria-hidden="true"></canvas>

    <div class="nx-container nx-hero-content">
        <div class="nx-breadcrumb" data-aos="fade-up" data-aos-delay="80">
            <i class="fas fa-graduation-cap" style="color: var(--accent);"></i>
            <span>About Us</span>
            <span style="opacity:.5;">/</span>
            <span style="font-weight:700;">NEXUS COMPUTER INSTITUTE</span>
        </div>

        <h1 class="nx-hero-title" data-aos="fade-up" data-aos-delay="220">
            Build Your <span class="nx-neon">Future</span>
            <br>
            with <span>NEXUS</span> <span class="nx-neon">Tech Education</span>
        </h1>

        <p style="color:var(--text-muted);font-size:1.05rem;line-height:1.8;" data-aos="fade-up" data-aos-delay="320">
            Premium IT training with industry-relevant curriculum, hands-on projects, and career guidance.
            <span class="nx-typing" id="nx-typing" aria-hidden="true"></span>
        </p>

        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:22px;" data-aos="fade-up" data-aos-delay="500">
            <a href="<?= assetUrl('admission.php') ?>" class="btn btn-primary">
                <i class="fas fa-rocket"></i> Apply Now
            </a>
            <a href="<?= assetUrl('courses.php') ?>" class="btn btn-outline">
                <i class="fas fa-book-open"></i> View Courses
            </a>
        </div>

        <div style="margin-top:16px;display:flex;gap:12px;flex-wrap:wrap;" data-aos="fade-up" data-aos-delay="650">
            <span class="section-badge" style="background: rgba(0,212,255,0.08); border: 1px solid rgba(0,212,255,0.22);">
                <i class="fas fa-award" style="color: var(--accent);"></i> Certified Programs
            </span>
            <span class="section-badge" style="background: rgba(100,255,218,0.06); border: 1px solid rgba(100,255,218,0.22);">
                <i class="fas fa-laptop-code" style="color: var(--accent);"></i> Hands-on Projects
            </span>
        </div>
    </div>

    <div class="nx-float-icons" aria-hidden="true">
        <i class="fab fa-html5"></i>
        <i class="fab fa-css3-alt"></i>
        <i class="fab fa-js"></i>
        <i class="fab fa-python"></i>
        <i class="fab fa-react"></i>
        <i class="fab fa-php"></i>
    </div>
</section>

<!-- SECTION 2: INSTITUTE OVERVIEW -->
<section class="nx-section" id="nx-overview">
    <div class="nx-container">
        <div class="nx-section-header" data-aos="fade-up">
            <div class="nx-badge"><i class="fas fa-building"></i> Who We Are</div>
            <h2 class="nx-section-title">NEXUS <span style="color:var(--accent);">Computer Institute</span></h2>
            <p class="nx-section-desc">Training students for real careers in web development, AI, Python, design, and digital marketing.</p>
        </div>

        <div class="nx-overview" style="margin-top:22px;">
            <div class="nx-glass" style="padding:22px;" data-aos="fade-right">
                <h3 style="margin:0 0 10px;">A modern learning experience</h3>
                <p style="color:var(--text-muted);line-height:1.8;margin:0;">
                    NEXUS COMPUTER INSTITUTE is dedicated to providing high-quality IT education and professional skills training.
                    Since our founding, we have empowered thousands of students with practical, industry-relevant skills.
                </p>

                <div style="display:grid;grid-template-columns:1fr;gap:12px;margin-top:16px;">
                    <div class="nx-stat" style="margin:0;">
                        <div style="display:flex;gap:10px;align-items:center;">
                            <i class="fas fa-certificate" style="color:var(--accent);"></i>
                            <div>
                                <div style="font-weight:800;">Industry-Recognized Certificates</div>
                                <div class="nx-stat-label" style="margin-top:2px;">Earn credentials with QR verification support</div>
                            </div>
                        </div>
                    </div>
                    <div class="nx-stat" style="margin:0;">
                        <div style="display:flex;gap:10px;align-items:center;">
                            <i class="fas fa-briefcase" style="color:var(--accent);"></i>
                            <div>
                                <div style="font-weight:800;">Job Placement Support</div>
                                <div class="nx-stat-label" style="margin-top:2px;">Career counseling and interview preparation</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="nx-overview-image" data-aos="fade-left">
                    <img src="<?= assetUrl('screenshot-courses.png') ?>" alt="NEXUS Institute" loading="lazy" onerror="this.onerror=null;this.style.display='none';">
                </div>

                <div class="nx-glass" style="padding:18px;margin-top:16px;" data-aos="fade-up" data-aos-delay="120">
                    <div class="nx-stat-grid">
                        <div class="nx-stat" style="padding:0;background:transparent;border:none;">
                            <div class="nx-stat-value" data-counter="2018">0</div>
                            <div class="nx-stat-label">Founded Year</div>
                        </div>
                        <div class="nx-stat" style="padding:0;background:transparent;border:none;">
                            <div class="nx-stat-value" data-counter="2500">0</div>
                            <div class="nx-stat-label">Graduates</div>
                        </div>
                        <div class="nx-stat" style="padding:0;background:transparent;border:none;">
                            <div class="nx-stat-value" data-counter="25">0</div>
                            <div class="nx-stat-label">Courses</div>
                        </div>
                        <div class="nx-stat" style="padding:0;background:transparent;border:none;">
                            <div class="nx-stat-value" data-counter="95">0%</div>
                            <div class="nx-stat-label">Success Rate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 3: MISSION VISION VALUES -->
<section class="nx-section" id="nx-values">
    <div class="nx-container">
        <div class="nx-section-header" data-aos="fade-up">
            <div class="nx-badge"><i class="fas fa-compass"></i> Our Direction</div>
            <h2 class="nx-section-title">Mission, Vision &amp; <span style="color:var(--accent);">Values</span></h2>
            <p class="nx-section-desc">Hover to flip • Premium glassmorphism • Neon border glow</p>
        </div>

        <div class="nx-flip-grid" style="margin-top:20px;">
            <?php
            $flipCards = [
                ['icon' => 'fa-bullseye', 'title' => 'Our Mission', 'front' => 'What drives us every day', 'back' => 'Provide quality technology education accessible to everyone, empowering individuals with skills for the digital age and preparing them for successful careers in the ever-evolving tech industry.'],
                ['icon' => 'fa-eye', 'title' => 'Our Vision', 'front' => "Where we're headed", 'back' => "Become Pakistan's leading IT training institute, recognized globally for excellence in technology education, innovation, and producing industry-ready professionals."],
                ['icon' => 'fa-gem', 'title' => 'Our Promise', 'front' => 'What we guarantee', 'back' => 'Deliver practical, job-ready skills through hands-on training, mentorship, and continuous support for every student. Quality education at affordable prices.'],

                ['icon' => 'fa-lightbulb', 'title' => 'Innovation', 'front' => 'Cutting-edge teaching methods', 'back' => 'Embracing modern technologies and learning approaches to keep curriculum relevant and future-ready.'],
                ['icon' => 'fa-star', 'title' => 'Excellence', 'front' => 'High standards in education', 'back' => 'Striving for the highest quality in training, mentorship, and real-world outcomes.'],
                ['icon' => 'fa-shield-alt', 'title' => 'Integrity', 'front' => 'Trust through transparency', 'back' => 'Building long-term trust with honesty, responsibility, and student-first guidance.'],

                ['icon' => 'fa-chart-line', 'title' => 'Growth', 'front' => 'Continuous learning mindset', 'back' => 'Committed to helping learners grow through projects, feedback, and career development support.'],
            ];

            foreach ($flipCards as $i => $c):
            ?>
                <div
                    class="nx-flip"
                    data-aos="fade-up"
                    data-aos-delay="<?= (string)($i * 80) ?>"
                    style="animation-delay: <?= (int)($i * 80) ?>ms;"
                >
                    <div class="nx-flip-inner">
                        <div class="nx-flip-front">
                            <div class="nx-flip-icon"><i class="fas <?= $c['icon'] ?>"></i></div>
                            <h3 class="nx-flip-title"><?= sanitize($c['title']) ?></h3>
                            <p class="nx-flip-desc"><?= sanitize($c['front']) ?></p>
                        </div>
                        <div class="nx-flip-back">
                            <div class="nx-flip-icon" style="background: rgba(100,255,218,0.10); border-color: rgba(100,255,218,0.22);">
                                <i class="fas <?= $c['icon'] ?>"></i>
                            </div>
                            <h3 class="nx-flip-title"><?= sanitize($c['title']) ?></h3>
                            <p class="nx-flip-desc"><?= sanitize($c['back']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SECTION 4: INTERACTIVE TIMELINE -->
<section class="nx-section" id="nx-timeline">
    <div class="nx-container">
        <div class="nx-section-header" data-aos="fade-up">
            <div class="nx-badge"><i class="fas fa-road"></i> Our Journey</div>
            <h2 class="nx-section-title">Institute <span style="color:var(--accent);">Timeline</span></h2>
            <p class="nx-section-desc">Vertical animated timeline with scroll reveal</p>
        </div>

        <div class="nx-timeline" style="margin-top:18px;" data-aos="fade-up">
            <?php
            $milestones = [
                ['year' => '2018', 'title' => 'Institute Founded', 'text' => 'NEXUS Computer Institute was established with a vision to transform IT education in Pakistan.'],
                ['year' => '2019', 'title' => 'First 500 Students', 'text' => 'Reached our first milestone of 500 enrolled students across multiple courses.'],
                ['year' => '2020', 'title' => 'Online Learning Launch', 'text' => 'Expanded to online learning platforms, making education accessible during the pandemic.'],
                ['year' => '2022', 'title' => 'AI & ML Department', 'text' => 'Launched dedicated AI and Machine Learning department with advanced curriculum.'],
                ['year' => '2024', 'title' => '2500+ Graduates', 'text' => 'Celebrated 2500+ successful graduates working in top tech companies worldwide.'],
                ['year' => '2026', 'title' => 'New Campus & Expansion', 'text' => 'Expanding with a new modern campus and launching 10+ new advanced courses.'],
            ];

            foreach ($milestones as $m):
            ?>
                <div class="nx-milestone" data-aos="fade-up">
                    <div class="nx-milestone-year"><?= sanitize($m['year']) ?></div>
                    <h4><?= sanitize($m['title']) ?></h4>
                    <p><?= sanitize($m['text']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SECTION 5: DIRECTOR MESSAGE -->
<section class="nx-section" id="nx-director">
    <div class="nx-container">
        <div class="nx-section-header" data-aos="fade-up">
            <div class="nx-badge"><i class="fas fa-user-tie"></i> Director Message</div>
            <h2 class="nx-section-title">Leadership with <span style="color:var(--accent);">Purpose</span></h2>
            <p class="nx-section-desc">A message from our director—guiding students toward real-world success.</p>
        </div>

        <div class="nx-director" style="margin-top:22px;">
            <div class="nx-director-img" data-aos="fade-right">
                <img src="<?= assetUrl('images/teacher-placeholder.jpg') ?>" alt="Director" loading="lazy" onerror="this.onerror=null;this.src='<?= $imgFallback ?>';">
            </div>

            <div class="nx-glass" style="padding:22px;" data-aos="fade-left">
                <div class="nx-quote"><i class="fas fa-quote-left"></i></div>
                <div class="nx-signature">Signature of Director</div>

                <h3 style="margin:10px 0 8px;">Experience that shapes future tech leaders</h3>
                <p style="color:var(--text-muted);line-height:1.8;margin:0;">
                    At NEXUS, we believe education is not just about learning—it's about building confidence, skills, and career-ready expertise.
                    We are committed to empowering every student through mentorship, hands-on training, and continuous guidance.
                </p>

                <div style="display:grid;grid-template-columns:1fr;gap:10px;margin-top:16px;">
                    <div class="nx-stat" style="margin:0;">
                        <div style="font-weight:900;">Experience</div>
                        <div class="nx-stat-label">10+ years in IT education & curriculum development</div>
                    </div>
                    <div class="nx-stat" style="margin:0;">
                        <div style="font-weight:900;">Qualifications</div>
                        <div class="nx-stat-label">Industry certifications and academic expertise</div>
                    </div>
                    <div class="nx-stat" style="margin:0;">
                        <div style="font-weight:900;">Message</div>
                        <div class="nx-stat-label">Start now—your future is built in the classroom.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 6: ACHIEVEMENTS -->
<section class="nx-section" id="nx-achievements">
    <div class="nx-container">
        <div class="nx-section-header" data-aos="fade-up">
            <div class="nx-badge"><i class="fas fa-award"></i> Achievements</div>
            <h2 class="nx-section-title">Progress that <span style="color:var(--accent);">Proves It</span></h2>
            <p class="nx-section-desc">Animated counters, circular progress, and interactive cards.</p>
        </div>

        <div class="nx-ach-grid" style="margin-top:22px;" data-aos="fade-up">
            <?php
            $ach = [
                ['value'=>90,'label'=>'Placements'],
                ['value'=>75,'label'=>'Partners'],
                ['value'=>82,'label'=>'Certifications'],
                ['value'=>88,'label'=>'Awards'],
                ['value'=>70,'label'=>'Training Wins'],
            ];
            foreach ($ach as $a):
            ?>
                <div class="nx-circle nx-glass" style="padding:18px;" data-aos="fade-up">
                    <div style="display:flex;gap:14px;align-items:center;justify-content:center;">
                        <svg width="92" height="92" viewBox="0 0 100 100">
                            <defs>
                                <linearGradient id="g-<?= sanitize($a['label']) ?>" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0" stop-color="rgba(0,212,255,.85)" />
                                    <stop offset="1" stop-color="rgba(100,255,218,.85)" />
                                </linearGradient>
                            </defs>
                            <circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.10)" stroke-width="10" fill="none" />
                            <circle class="nx-circle-progress" cx="50" cy="50" r="40" stroke="url(#g-<?= sanitize($a['label']) ?>)" stroke-width="10" fill="none" stroke-linecap="round"
                                stroke-dasharray="251.2" stroke-dashoffset="251.2" style="transform:rotate(-90deg); transform-origin:50% 50%;" data-progress="<?= (int)$a['value'] ?>" />
                        </svg>
                    </div>
                    <div class="nx-circle-title"><?= sanitize($a['label']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SECTION 7: INSTRUCTORS -->
<section class="nx-section" id="nx-instructors">
    <div class="nx-container">
        <div class="nx-section-header" data-aos="fade-up">
            <div class="nx-badge"><i class="fas fa-chalkboard-teacher"></i> Instructors</div>
            <h2 class="nx-section-title">Learn from <span style="color:var(--accent);">Experts</span></h2>
            <p class="nx-section-desc">Glassmorphism cards with 3D hover, lazy loading, and department badges.</p>
        </div>

        <div class="nx-teach-grid" style="margin-top:22px;" data-aos="fade-up">
            <?php if (!empty($teachers)): ?>
                <?php foreach ($teachers as $t):
                    $dept = $t['department'] ?? 'IT';
                    $img = $t['image'] ?? '';
                    $imgUrl = $img ? assetUrl('uploads/teachers/' . $img) : $imgFallback;
                    $defaultSkill = isset($t['skill_level']) ? (int)$t['skill_level'] : 65;
                    $skillWidth = max(5, min(95, $defaultSkill));
                ?>
                    <div class="nx-teach-card" data-aos="fade-up" data-aos-delay="20">
                        <div class="nx-teach-top">
                            <div class="nx-teach-avatar">
                                <img src="<?= sanitize($imgUrl) ?>" alt="<?= sanitize($t['name'] ?? 'Teacher') ?>" loading="lazy" onerror="this.onerror=null;this.src='<?= $imgFallback ?>';">
                            </div>
                            <div>
                                <h4 class="nx-teach-name"><?= sanitize($t['name'] ?? 'Unknown') ?></h4>
                                <div class="nx-teach-dept"><?= sanitize($dept) ?></div>
                            </div>
                        </div>

                        <div class="nx-skill">
                            <div class="nx-skill-row">
                                <span>Skills</span>
                                <span><?= (int)$skillWidth ?>%</span>
                            </div>
                            <div class="nx-bar" aria-hidden="true"><span style="width:<?= (int)$skillWidth ?>%"></span></div>
                        </div>

                        <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap;">
                            <span class="section-badge" style="padding:6px 10px;border-radius:999px;background:rgba(0,212,255,0.08);border:1px solid rgba(0,212,255,0.22);color:var(--accent);font-weight:800;font-size:.85rem;">
                                <?= sanitize($dept) ?>
                            </span>
                        </div>

                        <div class="nx-social">
                            <a href="#" aria-label="Facebook" style="color:rgba(255,255,255,.85)"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Instagram" style="color:rgba(255,255,255,.85)"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="Twitter" style="color:rgba(255,255,255,.85)"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="nx-glass" style="padding:26px;text-align:center;margin-top:16px;" data-aos="fade-up">
                    <i class="fas fa-users" style="font-size:2rem;color:var(--accent);opacity:.7;"></i>
                    <h3 style="margin:10px 0 6px;">Teachers Coming Soon</h3>
                    <p style="color:var(--text-muted);margin:0;">Our instructor profiles are being updated. Please check back soon.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- SECTION 8: STUDENT TESTIMONIALS (Swiper) -->
<section class="nx-section nx-testimonials" id="nx-testimonials">
    <div class="nx-container">
        <div class="nx-section-header" data-aos="fade-up">
            <div class="nx-badge"><i class="fas fa-star"></i> Student Testimonials</div>
            <h2 class="nx-section-title">What Our <span style="color:var(--accent);">Learners</span> Say</h2>
            <p class="nx-section-desc">Auto-play Swiper slider with infinite loop.</p>
        </div>



        <div class="swiper" style="margin-top:22px;" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php
                $testimonials = [
                    ['name'=>'Ali Raza','review'=>'The course content is modern and the guidance is excellent. I gained real skills and confidence.','rating'=>5],
                    ['name'=>'Hira Khan','review'=>'NEXUS made learning easy and practical. Projects helped me understand concepts deeply.','rating'=>5],
                    ['name'=>'Usman Ali','review'=>'Instructors are professional and supportive. The job placement support helped a lot.','rating'=>5],
                    ['name'=>'Sara Ahmed','review'=>'Great learning environment with structured lessons and friendly staff.','rating'=>4],
                ];
                foreach ($testimonials as $ts):
                ?>
                    <div class="swiper-slide">
                        <div class="nx-testimonial-card">
                            <div class="stars" style="display:flex;gap:6px;align-items:center;">
                                <?php for ($i=1;$i<=5;$i++): ?>
                                    <i class="fas fa-star" style="color:<?= $i<= (int)$ts['rating'] ? '#FFD700' : 'rgba(255,255,255,0.25)' ?>;"></i>
                                <?php endfor; ?>
                            </div>
                            <p style="color:var(--text-muted);line-height:1.75;margin:12px 0 0;">“<?= sanitize($ts['review']) ?>”</p>
                            <div style="margin-top:14px;font-weight:900;color:rgba(255,255,255,.92);">— <?= sanitize($ts['name']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            (function(){
                function initSwiper(){
                    if (typeof Swiper !== 'undefined') {
                        new Swiper('#nx-testimonials .swiper', {
                            loop: true,
                            autoplay: { delay: 3500, disableOnInteraction: false },
                            speed: 900,
                            slidesPerView: 1,
                            spaceBetween: 20,
                            pagination: { el: '#nx-testimonials .swiper-pagination', clickable: true },
                            breakpoints: {
                                768: { slidesPerView: 2 }
                            }
                        });
                    }
                }
                if (document.readyState === 'complete') { initSwiper(); }
                else { window.addEventListener('load', initSwiper); }
            })();
        </script>
    </div>
</section>

<!-- SECTION 9: WHY CHOOSE NEXUS -->
<section class="nx-section" id="nx-why">
    <div class="nx-container">
        <div class="nx-section-header" data-aos="fade-up">
            <div class="nx-badge"><i class="fas fa-bolt"></i> Why Choose NEXUS</div>
            <h2 class="nx-section-title">Built for <span style="color:var(--accent);">Career Results</span></h2>
            <p class="nx-section-desc">Modern labs, expert trainers, international curriculum, and real industry projects.</p>
        </div>

        <div class="nx-feature-grid" style="margin-top:22px;">
            <?php
            $features = [
                ['icon'=>'fas fa-microchip','title'=>'Modern Labs','desc'=>'Hands-on learning with updated systems and tools.'],
                ['icon'=>'fas fa-user-check','title'=>'Expert Trainers','desc'=>'Mentors who guide, review, and help you grow faster.'],
                ['icon'=>'fas fa-globe','title'=>'International Curriculum','desc'=>'Structured courses aligned with modern industry standards.'],
                ['icon'=>'fas fa-briefcase','title'=>'Job Placement','desc'=>'Support for career preparation and interview readiness.'],
                ['icon'=>'fas fa-project-diagram','title'=>'Industry Projects','desc'=>'Real projects that improve your portfolio and confidence.'],
                ['icon'=>'fas fa-handshake','title'=>'Internship Support','desc'=>'Guidance toward internships and practical experience.'],
            ];
            foreach ($features as $f):
            ?>
                <div class="nx-feature" data-aos="fade-up">
                    <i class="<?= $f['icon'] ?>"></i>
                    <h3 style="margin:0 0 8px;font-size:1.15rem;"><?= sanitize($f['title']) ?></h3>
                    <p style="color:var(--text-muted);line-height:1.7;margin:0;"><?= sanitize($f['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SECTION 10: CALL TO ACTION -->
<section class="nx-section" id="nx-cta" style="padding-top:40px;">
    <div class="nx-container">
        <div class="nx-cta" data-aos="fade-up">
            <div style="display:flex;gap:20px;align-items:center;justify-content:space-between;flex-wrap:wrap;">
                <div style="flex:1;min-width:260px;">
                    <h2 style="margin:0;font-size:clamp(1.4rem,3vw,2rem);">Start Your Technology Journey Today</h2>
                    <p style="color:var(--text-muted);margin:8px 0 0;line-height:1.8;">Apply now and unlock industry-ready skills with NEXUS.</p>
                </div>
                <div class="nx-cta-buttons" style="display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="<?= assetUrl('admission.php') ?>" class="btn btn-primary" style="padding:12px 22px;">
                        <i class="fas fa-rocket"></i> Apply Now
                    </a>
                    <a href="<?= assetUrl('courses.php') ?>" class="btn btn-outline" style="padding:12px 22px;">
                        <i class="fas fa-book-open"></i> View Courses
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Performance + Animations JS -->
<script>
    // Counter-up with IntersectionObserver (better performance)
    (function(){
        const els = document.querySelectorAll('[data-counter]');
        if (!els.length) return;

        function animateAll(){
            els.forEach(el => {
                const target = Number(el.getAttribute('data-counter'));
                const isPct = (el.textContent || '').includes('%');
                const duration = 1200;
                const startTime = performance.now();
                function easeOut(t){ return 1 - Math.pow(1 - t, 3); }
                function tick(now){
                    const t = Math.min(1, (now - startTime) / duration);
                    const val = Math.floor(target * easeOut(t));
                    el.textContent = isPct ? (val + '%') : val.toLocaleString();
                    if (t < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
            });
        }

        if ('IntersectionObserver' in window) {
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateAll();
                        obs.disconnect();
                    }
                });
            }, { threshold: 0.3 });
            const section = document.getElementById('nx-overview');
            if (section) obs.observe(section);
        } else {
            animateAll();
        }
    })();

    // Neon typing effect
    (function(){
        const el = document.getElementById('nx-typing');
        if (!el) return;
        const words = ['Web Development','AI & Machine Learning','Python Programming','Graphic Design','Digital Marketing'];
        let i = 0, j = 0, deleting = false;
        const speed = 75;
        const delSpeed = 45;
        function loop(){
            const current = words[i];
            if (!deleting) {
                j++;
                el.textContent = current.slice(0, j);
                if (j >= current.length) { deleting = true; setTimeout(loop, 900); return; }
                setTimeout(loop, speed);
            } else {
                j--;
                el.textContent = current.slice(0, j);
                if (j <= 0) { deleting = false; i = (i + 1) % words.length; setTimeout(loop, 250); return; }
                setTimeout(loop, delSpeed);
            }
        }
        loop();
    })();

    // Canvas particles
    (function(){
        const canvas = document.getElementById('nx-particles');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReduced) return;

        let w, h, particles;
        const resize = () => {
            w = canvas.width = canvas.clientWidth;
            h = canvas.height = canvas.clientHeight;
            const count = Math.min(90, Math.floor((w*h)/22000));
            particles = Array.from({length: count}).map(() => ({
                x: Math.random()*w,
                y: Math.random()*h,
                vx: (Math.random()*0.6 - 0.3),
                vy: (Math.random()*0.6 - 0.3),
                r: Math.random()*2.1 + 0.8,
                a: Math.random()*0.7 + 0.2
            }));
        };
        const draw = () => {
            ctx.clearRect(0,0,w,h);
            for (let p of particles){
                p.x += p.vx; p.y += p.vy;
                if (p.x<0) p.x=w; if (p.x>w) p.x=0;
                if (p.y<0) p.y=h; if (p.y>h) p.y=0;

                ctx.beginPath();
                ctx.fillStyle = `rgba(0,212,255,${p.a})`;
                ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
                ctx.fill();
            }
            // connections
            for (let i=0;i<particles.length;i++){
                for (let k=i+1;k<particles.length;k++){
                    const p1=particles[i], p2=particles[k];
                    const dx=p1.x-p2.x, dy=p1.y-p2.y;
                    const dist=Math.hypot(dx,dy);
                    if (dist<110){
                        ctx.strokeStyle = `rgba(100,255,218,${0.25*(1-dist/110)})`;
                        ctx.lineWidth=1;
                        ctx.beginPath();
                        ctx.moveTo(p1.x,p1.y);
                        ctx.lineTo(p2.x,p2.y);
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(draw);
        };

        resize();
        window.addEventListener('resize', resize);
        draw();
    })();

    // Circular progress animate with IntersectionObserver
    (function(){
        const els = document.querySelectorAll('.nx-circle-progress');
        if (!els.length) return;

        function animateCircles(){
            els.forEach(el => {
                const prog = Number(el.getAttribute('data-progress') || 0);
                const r = 40;
                const circ = 2 * Math.PI * r;
                el.style.strokeDasharray = String(circ);
                el.style.strokeDashoffset = String(circ);
                const targetOffset = circ * (1 - prog/100);

                el.animate([
                    { strokeDashoffset: circ },
                    { strokeDashoffset: targetOffset }
                ], { duration: 1200, easing: 'ease-out', fill: 'forwards' });
            });
        }

        if ('IntersectionObserver' in window) {
            const section = document.getElementById('nx-achievements');
            if (section) {
                const obs = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateCircles();
                            obs.disconnect();
                        }
                    });
                }, { threshold: 0.3 });
                obs.observe(section);
            }
        } else {
            animateCircles();
        }
    })();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

