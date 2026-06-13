<?php
declare(strict_types=1);

$currentPage = 'faq';
$pageTitle = 'FAQ - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Frequently Asked Questions about NEXUS Computer Institute.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="page-header">
    <h1 data-aos="fade-up">Frequently Asked <span>Questions</span></h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="<?= assetUrl('index.php') ?>">Home</a>
        <span>/</span>
        <span>FAQ</span>
    </div>
</section>

<section class="section" style="padding-top:30px; background: rgba(17, 34, 64, 0.3);">
    <div class="container">
        <div style="max-width: 820px; margin: 0 auto;">
            <?php
            // Static FAQ per your preference (fast, matches existing contact.php FAQ style)
            $faqs = [
                ['q'=>'How can I apply for admission?', 'a'=>'Apply online via admission.php. Select your course, fill the form, upload required documents (if applicable), and submit. Our counselor will contact you to confirm the batch.'],
                ['q'=>'What are the admission requirements?', 'a'=>'Typically you need CNIC/B-Form, phone number, email, and basic student information. Additional documents may be required—our admissions team will guide you.'],
                ['q'=>'Do you offer installment plans?', 'a'=>'Yes. Many courses can be paid in flexible installments. Contact our admissions team for course-specific options.'],
                ['q'=>'Are certificates recognized?', 'a'=>'Yes. Certificates are issued after successful completion of the course and include QR verification for authenticity.'],
                ['q'=>'Do you provide job placement support?', 'a'=>'Yes. We offer career guidance, resume support, and placement assistance for eligible graduates.'],
                ['q'=>'Do you provide online classes?', 'a'=>'Yes, for many courses we offer on-campus and online learning options. You’ll receive access to learning resources and guidance.'],
            ];

            foreach ($faqs as $i => $f): ?>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="<?= ($i + 1) * 60 ?>" style="margin-bottom:12px;">
                    <div class="faq-question" style="cursor:pointer;">
                        <span><?= htmlspecialchars($f['q'], ENT_QUOTES, 'UTF-8') ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" style="display:none; padding-top:10px;">
                        <p style="color:var(--text-muted); line-height:1.8; margin:0;">
                            <?= htmlspecialchars($f['a'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
// Simple FAQ toggle (if main.js doesn’t handle this page)
(function(){
  document.querySelectorAll('.faq-item .faq-question').forEach(function(q){
    q.addEventListener('click', function(){
      var ans = q.parentElement.querySelector('.faq-answer');
      if(!ans) return;
      ans.style.display = (ans.style.display === 'block') ? 'none' : 'block';
    });
  });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

