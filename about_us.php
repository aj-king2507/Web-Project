<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Opal Glow</title>
    <link rel="stylesheet" href="assets/css/about_us.css">
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
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
                <li><a href="about_us.php" class="active">About Us</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="contact_us.php">Contact</a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <span class="person-emoji">👤</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-text">
                <h2>Welcome to <span>Opal Glow</span></h2>
                <p>Opal Glow is Mauritius’ premier beauty salon, offering luxurious treatments for skin, hair, and body. Our mission is to help you look radiant while indulging in the ultimate spa experience.</p>
            </div>
            <div class="hero-image">
                <img src="assets/images/opalglow_spa_interior.jpg" alt="Opal Glow spa interior" />
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="mission">
            <h2>Our Mission & Vision</h2>
            <p><strong>Mission:</strong> To provide personalized beauty treatments that enhance natural radiance and overall well-being.</p>
            <p><strong>Vision:</strong> To become the most trusted and innovative spa brand in Mauritius, setting a benchmark for excellence in beauty and wellness.</p>
        </section>

        <!-- Therapists Section -->
        <section class="team">
            <h2>Meet Our Experts</h2>
            <div class="therapist-grid">
                <div class="therapist-card">
                    <img src="assets/images/Dr_Angela_Smith.jpg" alt="Dr. Angela Smith">
                    <h3>Dr. Angela Smith</h3>
                    <p>Specialty: Japanese Head Spa</p>
                    <p>Experience: 12 years in holistic scalp & relaxation therapies</p>
                    <p>Flair: Calm, meticulous, and expert in rejuvenating scalp treatments</p>
                </div>

                <div class="therapist-card">
                    <img src="assets/images/Dr_Brian_Thompson.jpg" alt="Dr. Brian Thompson">
                    <h3>Dr. Brian Thompson</h3>
                    <p>Specialty: Keratin Hair Treatment</p>
                    <p>Experience: 10 years in advanced hair care and straightening techniques</p>
                    <p>Flair: Creative, detail-focused, and highly skilled in hair smoothing & styling</p>
                </div>

                <div class="therapist-card">
                    <img src="assets/images/Dr_Klara_Martinez.jpg" alt="Dr. Clara Martinez">
                    <h3>Dr. Clara Martinez</h3>
                    <p>Specialty: Glow Revival Facial</p>
                    <p>Experience: 8 years in dermatology and facial rejuvenation</p>
                    <p>Flair: Friendly, professional, and highly skilled in revitalizing skin treatments</p>
                </div>

                <div class="therapist-card">
                    <img src="assets/images/Dr_David_Lee.jpg" alt="Dr. David Lee">
                    <h3>Dr. David Lee</h3>
                    <p>Specialty: Microdermabrasion</p>
                    <p>Experience: 15 years in advanced skin exfoliation techniques</p>
                    <p>Flair: Precise, calm, and expert in achieving smooth radiant skin</p>
                </div>

                <div class="therapist-card">
                    <img src="assets/images/Dr_Mia_Wilson.jpg" alt="Dr. Mia Wilson">
                    <h3>Dr. Mia Wilson</h3>
                    <p>Specialty: Serenity Massage</p>
                    <p>Experience: 10 years in therapeutic massage & stress relief treatments</p>
                    <p>Flair: Relaxing, strong technique, and intuitive touch for deep relaxation</p>
                </div>

                <div class="therapist-card">
                    <img src="assets/images/Dr_Isabelle_Davis.jpg" alt="Dr. Isabelle Davis">
                    <h3>Dr. Isabelle Davis</h3>
                    <p>Specialty: Body Scrub & Wrap</p>
                    <p>Experience: 12 years in body care & skin revitalization</p>
                    <p>Flair: Energetic, precise, and skilled in pampering full-body treatments</p>
                </div>

                <!-- Add more therapists here -->
            </div>
        </section>

        <!-- Spa Info Section -->
        <section class="spa-info">
            <h2>Why Choose Opal Glow?</h2>
            <div class="info-grid">
                <div class="info-card">
                    <h3>Premium Treatments</h3>
                    <p>From hair care to full-body treatments, every service is tailored for your ultimate glow.</p>
                </div>
                <div class="info-card">
                    <h3>Expert Therapists</h3>
                    <p>All our therapists are highly trained professionals with years of experience.</p>
                </div>
                <div class="info-card">
                    <h3>Relaxing Environment</h3>
                    <p>Enjoy a serene, modern spa ambiance designed for your relaxation and comfort.</p>
                </div>
                <div class="info-card">
                    <h3>Personalized Care</h3>
                    <p>Every treatment is customized to suit your unique skin, hair, and wellness needs.</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Opal Glow. All Rights Reserved.</p>
    </footer>
</body>
</html>
