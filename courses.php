<?php
$currentPage = 'courses';
$pageTitle = 'Courses - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Explore our premium IT courses including Web Development, AI, Python, Graphic Design, Digital Marketing and more.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch courses
$courses = [];
$categories = [];
try {
    $stmt = db()->query("SELECT * FROM courses WHERE status = 'active' ORDER BY title ASC");
    $courses = $stmt->fetchAll();

    $stmt = db()->query("SELECT DISTINCT category FROM courses WHERE status = 'active' AND category IS NOT NULL ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {}

// Course icon map
$iconMap = [
    'Web Development' => 'fa-code',
    'Graphic Designing' => 'fa-palette',
    'Digital Marketing' => 'fa-bullhorn',
    'Python Programming' => 'fa-python fab',
    'AI & Machine Learning' => 'fa-robot',
    'Office Management' => 'fa-file-word',
];

// Normalization helper so filter buttons & course items always match
function normalizeCategoryToken(string $value): string {
    $value = trim(mb_strtolower($value));
    // collapse whitespace to single space
    $value = preg_replace('/\s+/u', ' ', $value);
    return $value;
}
?>

<!-- Page Header -->
<section class="page-header">
    <h1 data-aos="fade-up">Our <span>Courses</span></h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="<?= assetUrl('index.php') ?>">Home</a>
        <span>/</span>
        <span>Courses</span>
    </div>
</section>

<!-- Search & Filter -->
<section style="padding: 30px 0 0;">
    <div class="container">
        <div style="display:flex; gap:16px; flex-wrap:wrap; align-items:center; justify-content:space-between;" data-aos="fade-up">
            <div style="flex:1; min-width:280px; position:relative;">
                <i class="fas fa-search" style="position:absolute; left:16px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:0.9rem; pointer-events:none;"></i>
                <input type="text" id="course-search" class="form-control" placeholder="Search courses by name or keyword..." style="padding-left: 44px;">
            </div>
        </div>
        <div class="filter-buttons" style="margin-top:16px;" data-aos="fade-up" data-aos-delay="100">
            <button class="filter-btn active" data-filter="all"><i class="fas fa-th-large" style="margin-right:6px;"></i>All</button>
            <?php foreach ($categories as $cat): ?>
                <button class="filter-btn" data-filter="<?= sanitize(normalizeCategoryToken((string)$cat)) ?>"><?= sanitize($cat) ?></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Course Catalog -->
<section class="section" style="padding-top:30px;">
    <div class="container">
        <div class="grid-3" id="course-grid">
            <div id="course-no-results" style="display:none; grid-column:1 / -1; text-align:center; padding:40px 0;">
                <div style="width:90px; height:90px; background:rgba(0,212,255,0.08); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                    <i class="fas fa-search" style="font-size:2rem; color:var(--accent); opacity:0.6;"></i>
                </div>
                <h3 style="color:var(--white); margin-bottom:6px;">No courses found</h3>
                <p style="color:var(--text-muted); margin:0;">Try a different keyword or category.</p>
            </div>
            <?php foreach ($courses as $course):
                $icon = $iconMap[$course['title']] ?? 'fa-laptop-code';
            ?>
                <div class="grid-item" data-category="<?= sanitize(normalizeCategoryToken((string)($course['category'] ?? ''))) ?>" data-aos="fade-up">
                    <div class="course-card">
                        <div class="card-image">
                            <?php if ($course['image']): ?>
                                <img src="<?= assetUrl('uploads/courses/' . $course['image']) ?>" alt="<?= sanitize($course['title']) ?>" loading="lazy">
                            <?php else: ?>
                                <i class="fas <?= $icon ?> placeholder-icon"></i>
                            <?php endif; ?>
                            <span class="card-badge"><?= sanitize(ucfirst($course['level'] ?? 'Beginner')) ?></span>
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
        </div>

        <?php if (empty($courses)): ?>
            <div style="text-align:center; padding:60px 0;" data-aos="fade-up">
                <div style="width:100px; height:100px; background:rgba(0,212,255,0.08); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 24px;">
                    <i class="fas fa-book-open" style="font-size:2.5rem; color:var(--accent); opacity:0.5;"></i>
                </div>
                <h3 style="color:var(--white); margin-bottom:8px;">Courses Coming Soon</h3>
                <p style="color:var(--text-muted);">Our course catalog is being updated. Please check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container" style="position:relative; z-index:1;">
        <div data-aos="fade-up">
            <h2 class="section-title">Can't Decide? <span>We Can Help!</span></h2>
            <p class="section-desc" style="margin-bottom:32px;">Contact our advisors to find the perfect course for your career goals</p>
            <div style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
                <a href="<?= assetUrl('contact.php') ?>" class="btn btn-primary"><i class="fas fa-phone"></i> Contact Advisor</a>
                <a href="<?= assetUrl('admission.php') ?>" class="btn btn-outline"><i class="fas fa-file-alt"></i> Apply Now</a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var search = document.getElementById('course-search');
    var items = document.querySelectorAll('#course-grid .grid-item');
    var filterBtns = document.querySelectorAll('.filter-btn');
    var activeFilter = 'all';

    function filterCourses() {
        var q = search ? search.value.toLowerCase().trim() : '';
        var anyVisible = false;

        items.forEach(function(item) {
            var catVal = (item.getAttribute('data-category') || '').toLowerCase();
            var catMatch = activeFilter === 'all' || catVal === activeFilter;
            var title = item.querySelector('.card-title');
            var desc = item.querySelector('.card-desc');
            var text = ((title ? title.textContent : '') + ' ' + (desc ? desc.textContent : '')).toLowerCase();
            var searchMatch = !q || text.includes(q);

            if (catMatch && searchMatch) {
                item.style.display = '';
                item.style.animation = 'zoomIn 0.3s ease forwards';
                anyVisible = true;
            } else {
                item.style.display = 'none';
            }
        });

        var noResults = document.getElementById('course-no-results');
        if (noResults) {
            noResults.style.display = anyVisible ? 'none' : '';
        }

        // If you want to hide animations when empty, you can extend here.
    }

    if (search) {
        search.addEventListener('input', filterCourses);
    }

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            activeFilter = (this.getAttribute('data-filter') || 'all').toLowerCase();
            filterCourses();
        });
    });

    // First run to ensure any visible state is correct on load
    filterCourses();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
