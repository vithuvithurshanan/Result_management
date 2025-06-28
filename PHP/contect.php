<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact | Result Management System</title>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- External CSS -->
    <link rel="stylesheet" href="../CSS/contect.css" />
</head>
<body>
    <!-- Header -->
    <header>
        <img src="../Images/atilogo.jpg" alt="ATI Logo" class="logo" />
        <h1>Result Management System</h1>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="contact-wrapper">
        <section class="contact-content">
            <!-- Contact Info -->
            <div class="contact-info">
                <h2>Contact Us</h2>
                <p>Have questions? We’re here to help!</p>
                <div class="info-boxes">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        <span>123, ATI Campus, Mannar</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <span>atimanner@gmail.com</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone" aria-hidden="true"></i>
                        <span>0232223045</span>
                    </div>
                    <div class="info-item">
                        <i class="fab fa-facebook" aria-hidden="true"></i>
                        <a href="https://web.facebook.com/ati.mannar.7" target="_blank" rel="noopener noreferrer">Facebook</a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <form id="contactForm" class="contact-form" novalidate>
                <h3>Send a Message</h3>
                <div class="input-box">
                    <input type="text" id="name" name="name" required placeholder=" " />
                    <label for="name">Your Name</label>
                </div>
                <div class="input-box">
                    <input type="email" id="email" name="email" required placeholder=" " />
                    <label for="email">Your Email</label>
                </div>
                <div class="input-box">
                    <textarea id="message" name="message" required placeholder=" "></textarea>
                    <label for="message">Your Message</label>
                </div>
                <button type="submit">Send Message</button>
            </form>
        </section>

        <!-- Map Section -->
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps?q=8.890328889089947,79.99552972275126&hl=es;z=14&output=embed" 
                allowfullscreen 
                loading="lazy">
            </iframe>
        </div>
    </main>

    <!-- Success JS -->
    <script>
        document.getElementById("contactForm").addEventListener("submit", function(e) {
            e.preventDefault();
            alert("Message sent successfully!");
            this.reset();
        });
    </script>
</body>
</html>
