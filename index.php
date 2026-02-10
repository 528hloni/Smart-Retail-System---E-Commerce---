<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Landing Page</title>
    <link rel="stylesheet" href="css/index.css">
     <script src="js/index.js"></script>

</head>
<body>
    <nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="customer_dashboard.php">Wheels of Fortune</a>
        </div>
        <ul class="nav-links">
            <li><a href="login.php">Login</a></li>
        </ul>
    </div>
</nav>



    <section class="hero">
       <video class="hero-video" autoplay muted loop playsinline>
    <source src="landingpage/herovideo.mp4" type="video/mp4">
      </video>
       
        
        <div class="hero-content">
            <div class="hero-text">
                <h1>Premium <span>Car Wheels</span> That Transform Your Ride</h1>
            </div>
            
            
            <div class="hero-description">
                <p>Experience the perfect blend of style, performance, and innovation.</p> 
                <p>Welcome to <span>Wheels Of Fortune</span>, Where Every Wheel Is A Win.</p>
            </div>  

            <div class="hero-buttons">
                    <a href="login.php" class="cta-button">Browse Catalog</a>
                    
                </div> 
</div>  

</section>

            <section class="features" id="features">
        <h2>Why Choose Wheels of Fortune</h2>
        <p>We're revolutionizing the car wheel industry by sourcing the best and unmatched quality</p>
        <div class="features-grid">
            <div class="feature-card">
                <h3>OEM & Aftermarket</h3>
                <p>Both factory-spec replacements and custom upgrade options available.</p>
            </div>
            <div class="feature-card">
                <h3>Premium Quality</h3>
                <p>Only the finest materials and manufacturing processes. Each rim is built to last and perform.</p>
            </div>
            <div class="feature-card">
                <h3>Warranty Protected</h3>
                <p>All our wheels come with a comprehensive warranty for peace of mind.</p>
            </div>
            <div class="feature-card">
                <h3>Certified Authentic</h3>
                <p> No knockoffs, only genuine branded rims from trusted manufacturers.</p>
            </div>
        </div>
    </section>


    <section class="products" id="products">
    <h2>A Glimpse Of Our Catalog</h2>
    
    <div class="video-carousel">
        
        <button class="carousel-arrow prev-arrow" onclick="changeVideo(-1)">
            <span>&#8249;</span>
        </button>
        
        
        <div class="carousel-video-container">
            <video id="carouselVideo" class="carousel-video" autoplay muted loop playsinline>
                <source id="videoSource" src="landingpage/wheel1.mp4" type="video/mp4">
            </video>
        </div>
        
    
        <button class="carousel-arrow next-arrow" onclick="changeVideo(1)">
            <span>&#8250;</span>
        </button>
    </div>
</section>



<section class="why-choose" id="about">
    <h2>Why Thousands Choose Us</h2>
    <div class="why-grid">
        
        <div class="why-content">
            <h3>Built on Excellence</h3>
            <ul class="why-list">
                <li>Over 10,000 satisfied customers nationwide</li>
                <li>Partnerships with leading wheel manufacturers</li>
                <li>Industry-leading warranty on all products</li>
                <li>Transparent pricing with no hidden fees</li>
                <li>30-days satisfaction guarantee</li>
            </ul>
        </div>
        
        
        <div class="why-video-container">
            <video class="why-video" autoplay muted loop playsinline>
                <source src="landingpage/whyvideo.mp4" type="video/mp4">
            </video>
        </div>
    </div>
</section>


    <section class="final-cta" id="contact">git
        <div class="final-cta-content">
            <h2>Ready to Transform Your Ride?</h2>
            <p>Browse our catalog and find the perfect wheels for your vehicle today</p>
            <a href="login.php" class="cta-button-white">Get Started Now</a>
        </div>
    </section>


    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Wheels of Fortune</h4>
                <p>Your trusted partner for premium car wheels. Transforming vehicles with style and performance since 2025.</p>
            </div>

            <div class="footer-bottom">
            <p>&copy; 2025 Wheels of Fortune. All rights reserved. | Terms of Service | Privacy Policy</p>
            <p> Web Development by 528 Group</p>
        </div>
    </footer>



</body>
</html>