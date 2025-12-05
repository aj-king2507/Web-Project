<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Services - Opal Glow</title>
    <link rel="stylesheet" href="assets/css/services.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>
<body>
<header>
        <div class="logo">
            <h1>Opal Glow</h1>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="services.php"class="active">Services</a></li>
                <li><a href="contact_us.php">Contact</a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <span class="person-emoji">👤</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

<main class="services-section">
    <h2 class="section-title">Our Premium Services</h2>
    <div class="services-grid">

        <div class="service-card">
            <div class="service-image" data-service="japanese-head-spa">
                <img src="assets/images/japanese_head_spa.jpg" alt="Japanese Head Spa">
            </div>
            <div class="service-info">
                <h3>Japanese Head Spa</h3>
                <p>Relax your scalp and relieve tension</p>
            </div>
        </div>

        <div class="service-card">
            <div class="service-image" data-service="keratin-treatment">
                <img src="assets/images/keratine_hair_treatment.jpg" alt="Keratin Hair Treatment">
            </div>
            <div class="service-info">
                <h3>Keratin Hair Treatment</h3>
                <p>Smooth and frizz-free hair treatment</p>
            </div>
        </div>

        <div class="service-card">
            <div class="service-image" data-service="glow-facial">
                <img src="assets/images/glow_revival_facial.jpg" alt="Glow Revival Facial">
            </div>
            <div class="service-info">
                <h3>Glow Revival Facial</h3>
                <p>Hydrate and rejuvenate your skin</p>
            </div>
        </div>

        <div class="service-card">
            <div class="service-image" data-service="microdermabrasion">
                <img src="assets/images/microdermabrasion.jpg" alt="Microdermabrasion">
            </div>
            <div class="service-info">
                <h3>Microdermabrasion</h3>
                <p>Exfoliate and remove dead skin cells</p>
            </div>
        </div>

        <div class="service-card">
            <div class="service-image" data-service="serenity-massage">
                <img src="assets/images/serenity_massage.jpg" alt="Serenity Massage">
            </div>
            <div class="service-info">
                <h3>Serenity Massage</h3>
                <p>Relaxing full-body massage</p>
            </div>
        </div>

        <div class="service-card">
            <div class="service-image" data-service="body-scrub-wrap">
                <img src="assets/images/body_scrub_and_wrap.jpg" alt="Body Scrub & Wrap">
            </div>
            <div class="service-info">
                <h3>Body Scrub & Wrap</h3>
                <p>Exfoliation and hydrating wrap for smooth skin</p>
            </div>
        </div>

    </div>
</main>

<!-- Modal -->
<div class="modal-overlay" id="service-modal">
    <div class="modal-content">
        <span class="modal-close" id="modal-close">&times;</span>
        <h3 id="modal-title">Service Name</h3>
        <p><strong>Procedure:</strong> <span id="modal-procedure"></span></p>
        <p><strong>Duration:</strong> <span id="modal-duration"></span></p>
        <p><strong>Price:</strong> <span id="modal-price"></span></p>
        <p><strong>Therapist:</strong> <span id="modal-therapist"></span></p>
        <p><strong>Therapist Qualities:</strong> <span id="modal-qualities"></span></p>
    </div>
</div>

<script>
const serviceData = {
    "japanese-head-spa": {
        title: "Japanese Head Spa",
        procedure: "Relax your scalp and relieve tension",
        duration: "45 minutes",
        price: "USD 50",
        therapist: "Dr. Angela Smith",
        qualities: "Calm, meticulous, expert in rejuvenating scalp treatments"
    },
    "keratin-treatment": {
        title: "Keratin Hair Treatment",
        procedure: "Smooth and frizz-free hair treatment",
        duration: "90 minutes",
        price: "USD 120",
        therapist: "Dr. Brian Thompson",
        qualities: "Creative, detail-focused, highly skilled in hair smoothing & styling"
    },
    "glow-facial": {
        title: "Glow Revival Facial",
        procedure: "Hydrate and rejuvenate your skin",
        duration: "60 minutes",
        price: "USD 80",
        therapist: "Dr. Clara Martinez",
        qualities: "Friendly, professional, highly skilled in revitalizing skin treatments"
    },
    "microdermabrasion": {
        title: "Microdermabrasion",
        procedure: "Exfoliate and remove dead skin cells",
        duration: "50 minutes",
        price: "USD 70",
        therapist: "Dr. David Lee",
        qualities: "Precise, calm, expert in achieving smooth radiant skin"
    },
    "serenity-massage": {
        title: "Serenity Massage",
        procedure: "Relaxing full-body massage",
        duration: "60 minutes",
        price: "USD 65",
        therapist: "Dr. Mia Wilson",
        qualities: "Relaxing, strong technique, intuitive touch for deep relaxation"
    },
    "body-scrub-wrap": {
        title: "Body Scrub & Wrap",
        procedure: "Exfoliation and hydrating wrap for smooth skin",
        duration: "75 minutes",
        price: "USD 90",
        therapist: "Dr. Isabelle Davis",
        qualities: "Energetic, precise, skilled in pampering full-body treatments"
    }
};

const modal = document.getElementById('service-modal');
const modalClose = document.getElementById('modal-close');

// Only image triggers modal
document.querySelectorAll('.service-image').forEach(imgDiv => {
    imgDiv.addEventListener('click', () => {
        const key = imgDiv.dataset.service;
        const data = serviceData[key];

        document.getElementById('modal-title').textContent = data.title;
        document.getElementById('modal-procedure').textContent = data.procedure;
        document.getElementById('modal-duration').textContent = data.duration;
        document.getElementById('modal-price').textContent = data.price;
        document.getElementById('modal-therapist').textContent = data.therapist;
        document.getElementById('modal-qualities').textContent = data.qualities;

        modal.classList.add('active');
    });
});

modalClose.addEventListener('click', () => modal.classList.remove('active'));
modal.addEventListener('click', e => { if(e.target === modal) modal.classList.remove('active'); });
</script>
<footer>
        <p>&copy; <?php echo date("Y"); ?> Opal Glow. All Rights Reserved.</p>
</footer>
</body>
</html>
