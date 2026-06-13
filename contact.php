<?php
$currentPage = 'contact';
$pageTitle = 'Contact Us - NEXUS COMPUTER INSTITUTE';
$pageDesc = 'Get in touch with NEXUS Computer Institute. Contact us for admissions, course inquiries, or any questions.';

$extraCSS = '<style>
/* Contact page layout fixes */
.contact-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}
.contact-info-card {
    min-height: 90px;
    overflow: visible;
}
.contact-info-card .icon {
    flex-shrink: 0;
}
.contact-info-card h4 {
    word-break: break-word;
}
.contact-info-card p {
    word-break: break-word;
    line-height: 1.5;
}
.contact-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
}
@media (max-width: 980px) {
    .contact-form-grid {
        grid-template-columns: 1fr !important;
    }
}
@media (max-width: 600px) {
    .contact-cards-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    .contact-info-card {
        padding: 16px;
        gap: 12px;
    }
    .contact-info-card .icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    .contact-info-card h4 {
        font-size: 0.85rem;
    }
    .contact-info-card p {
        font-size: 0.8rem;
    }
}
@media (max-width: 400px) {
    .contact-cards-grid {
        grid-template-columns: 1fr;
    }
}
</style>';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch settings
$settings = [];
try {
    $stmt = db()->query("SELECT setting_key, setting_value FROM settings");
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'] ?? '';
    }
} catch (Exception $e) {}
?>

<!-- Page Header -->
<section class="page-header">
    <h1 data-aos="fade-up">Contact <span>Us</span></h1>
    <div class="breadcrumb" data-aos="fade-up" data-aos-delay="200">
        <a href="<?= assetUrl('index.php') ?>">Home</a>
        <span>/</span>
        <span>Contact</span>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="section" style="padding-top: 40px;">
    <div class="container">
        <div class="contact-cards-grid" data-aos="fade-up">
            <div class="contact-info-card">
                <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <h4>Our Address</h4>
                    <p><?= sanitize($settings['address'] ?? 'Main Boulevard, Lahore, Pakistan') ?></p>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="icon"><i class="fas fa-phone-alt"></i></div>
                <div>
                    <h4>Phone Number</h4>
                    <p><?= sanitize($settings['phone'] ?? '0300-1234567') ?></p>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <h4>Email Address</h4>
                    <p><?= sanitize($settings['email'] ?? 'info@nexusinstitute.pk') ?></p>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="icon"><i class="fas fa-clock"></i></div>
                <div>
                    <h4>Working Hours</h4>
                    <p>Mon - Sat: 8AM - 7PM</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form & Map -->
<section class="section" style="padding-top: 20px;">
    <div class="container">
        <div class="contact-form-grid">
            <!-- Form -->
            <div class="form-section" data-aos="fade-right">
                <h2 style="margin-bottom:8px; font-size:1.4rem;"><i class="fas fa-paper-plane" style="color:var(--accent);"></i> Send Message</h2>
                <p style="color:var(--text-muted); margin-bottom:24px; font-size:0.9rem;">Have a question? Fill the form below and we'll get back to you within 24 hours.</p>

                <div id="contact-alert"></div>

                <form id="contactForm">
                    <?= csrfField() ?>

                    <div class="form-row">
                        <div class="form-group floating">
                            <input type="text" name="name" class="form-control" placeholder=" " required>
                            <label>Your Name *</label>
                            <div class="form-error"></div>
                        </div>
                        <div class="form-group floating">
                            <input type="email" name="email" class="form-control" placeholder=" " required>
                            <label>Email Address *</label>
                            <div class="form-error"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group floating">
                            <input type="tel" name="phone" class="form-control" placeholder=" ">
                            <label>Phone Number</label>
                        </div>
                        <div class="form-group floating">
                            <input type="text" name="subject" class="form-control" placeholder=" " required>
                            <label>Subject *</label>
                            <div class="form-error"></div>
                        </div>
                    </div>

                    <div class="form-group floating">
                        <textarea name="message" class="form-control" rows="5" placeholder=" " required style="min-height:140px;"></textarea>
                        <label>Your Message *</label>
                        <div class="form-error"></div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>

            <!-- Map & Social -->
            <div data-aos="fade-left">
                <!-- Google Map -->
                <div class="map-container" style="margin-top:0; height:300px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d217898.02907498!2d74.1942772!3d31.4503563!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391907e1edcffe05%3A0xb97f9086d0a28235!2sLahore%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>

                <!-- Social Links -->
                <div class="form-section" style="margin-top:24px; padding:28px;">
                    <h3 style="margin-bottom:20px; font-size:1.1rem;"><i class="fas fa-share-alt" style="color:var(--accent);"></i> Connect With Us</h3>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <a href="<?= sanitize($settings['facebook'] ?? '#') ?>" target="_blank" class="contact-info-card" style="text-align:center; padding:20px; flex-direction:column; align-items:center; gap:8px;">
                            <i class="fab fa-facebook-f" style="font-size:1.5rem; color:#4267B2;"></i>
                            <span style="font-size:0.8rem; color:var(--text-muted);">Facebook</span>
                        </a>
                        <a href="<?= sanitize($settings['instagram'] ?? '#') ?>" target="_blank" class="contact-info-card" style="text-align:center; padding:20px; flex-direction:column; align-items:center; gap:8px;">
                            <i class="fab fa-instagram" style="font-size:1.5rem; color:#E1306C;"></i>
                            <span style="font-size:0.8rem; color:var(--text-muted);">Instagram</span>
                        </a>
                        <a href="<?= sanitize($settings['youtube'] ?? '#') ?>" target="_blank" class="contact-info-card" style="text-align:center; padding:20px; flex-direction:column; align-items:center; gap:8px;">
                            <i class="fab fa-youtube" style="font-size:1.5rem; color:#FF0000;"></i>
                            <span style="font-size:0.8rem; color:var(--text-muted);">YouTube</span>
                        </a>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? '03001234567') ?>" target="_blank" class="contact-info-card" style="text-align:center; padding:20px; flex-direction:column; align-items:center; gap:8px;">
                            <i class="fab fa-whatsapp" style="font-size:1.5rem; color:#25D366;"></i>
                            <span style="font-size:0.8rem; color:var(--text-muted);">WhatsApp</span>
                        </a>
                    </div>
                </div>

                <!-- Quick Contact -->
                <div class="form-section" style="margin-top:24px; text-align:center; padding:28px;">
                    <h3 style="margin-bottom:12px; font-size:1.1rem;">Call Us Directly</h3>
                    <a href="tel:<?= sanitize($settings['phone'] ?? '03001234567') ?>" class="btn btn-primary" style="font-size:1.1rem;">
                        <i class="fas fa-phone-alt"></i> <?= sanitize($settings['phone'] ?? '0300-1234567') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section" style="background: rgba(17, 34, 64, 0.3);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-badge"><i class="fas fa-question-circle"></i> FAQ</div>
            <h2 class="section-title">Frequently Asked <span>Questions</span></h2>
            <p class="section-desc">Find answers to common questions about our institute</p>
        </div>

        <div style="max-width: 750px; margin: 0 auto;">
            <?php
            $faqs = [
                ['Q' => 'What are the admission requirements?', 'A' => 'You need a valid CNIC/B-Form, 2 passport photos, and last educational certificate. Fill the online form and visit the institute to complete the admission process.'],
                ['Q' => 'Do you offer installment plans?', 'A' => 'Yes, we offer flexible installment plans for most courses. You can pay in 2-3 easy installments. Contact our admission office for specific details on your chosen course.'],
                ['Q' => 'Are the certificates recognized?', 'A' => 'Yes, our certificates are industry-recognized and include QR code verification for authenticity. Employers can verify your certificate online through our website.'],
                ['Q' => 'Do you provide job placement assistance?', 'A' => 'Yes, we provide career counseling, resume building workshops, and job placement assistance to all our graduates through our network of 50+ industry partners.'],
                ['Q' => 'Can I take online classes?', 'A' => 'Yes, we offer both on-campus and online learning options for most courses. Online students get access to recorded lectures, live sessions, and the same certification.'],
                ['Q' => 'What is the class schedule?', 'A' => 'We offer morning (9AM-12PM), afternoon (2PM-5PM), and evening (6PM-9PM) batches. Weekend-only batches are also available for working professionals.'],
            ];
            foreach ($faqs as $i => $faq):
            ?>
            <div class="faq-item" data-aos="fade-up" data-aos-delay="<?= ($i + 1) * 80 ?>">
                <div class="faq-question">
                    <span><?= $faq['Q'] ?></span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p><?= $faq['A'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container" style="position:relative; z-index:1;">
        <div data-aos="fade-up">
            <h2 class="section-title">Still Have <span>Questions?</span></h2>
            <p class="section-desc" style="margin-bottom:32px;">Our admission team is ready to help you choose the right course</p>
            <div style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
                <a href="<?= assetUrl('admission.php') ?>" class="btn btn-primary"><i class="fas fa-file-alt"></i> Apply Now</a>
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp'] ?? '03001234567') ?>" target="_blank" class="btn btn-outline" style="border-color:rgba(37,211,102,0.4); color:#25D366;">
                    <i class="fab fa-whatsapp"></i> WhatsApp Us
                </a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contact form submission
    var form = document.getElementById('contactForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!NexusForm.validate(form)) return;

            var formData = new FormData(form);
            var btn = form.querySelector('button[type="submit"]');
            var originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            fetch('<?= assetUrl('api/contact.php') ?>', { method: 'POST', body: formData })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                btn.disabled = false;
                btn.innerHTML = originalText;
                var alertDiv = document.getElementById('contact-alert');
                if (data.success) {
                    alertDiv.innerHTML = '<div class="alert alert-success fade-in-up"><i class="fas fa-check-circle"></i> ' + data.message + '</div>';
                    form.reset();
                } else {
                    alertDiv.innerHTML = '<div class="alert alert-danger fade-in-up"><i class="fas fa-exclamation-circle"></i> ' + (data.message || 'Failed to send.') + '</div>';
                }
                setTimeout(function() { alertDiv.innerHTML = ''; }, 5000);
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = originalText;
                NexusForm.showAlert('Network error. Please try again.', 'danger');
            });
        });
    }

    // FAQ accordion is handled by main.js
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
