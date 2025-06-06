<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Government</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            font-size: 1.3rem;
            background: linear-gradient(45deg, #007bff, #6610f2);
        }
        .hero {
            background: url('gov.jpg') center/cover no-repeat;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .service-item, .news-item {
            transition: transform 0.3s;
        }
        .service-item:hover, .news-item:hover {
            transform: scale(1.05);
        }
        .footer {
            background: #222;
            color: white;
            padding: 40px 0;
        }
        .footer a {
            color: #f8d210;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Local Gov</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#news">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero"></header>

    <section id="about" class="py-5 text-center">
        <div class="container">
            <h2 class="section-title">About Us</h2>
            <img src="https://source.unsplash.com/600x400/?meeting,government" class="img-fluid" alt="About">
            <p>Our local government is dedicated to ensuring public welfare and sustainable development.</p>
        </div>
    </section>

    <section id="services" class="py-5 bg-light text-center">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <div class="row">
                <div class="col-md-4 service-item">
                    <i class="fas fa-shield-alt fa-3x"></i>
                    <h4>Public Safety</h4>
                    <p>Ensuring the security and well-being of residents.</p>
                </div>
                <div class="col-md-4 service-item">
                    <i class="fas fa-road fa-3x"></i>
                    <h4>Infrastructure</h4>
                    <p>Developing roads, bridges, and public facilities.</p>
                </div>
                <div class="col-md-4 service-item">
                    <i class="fas fa-heartbeat fa-3x"></i>
                    <h4>Health & Welfare</h4>
                    <p>Providing healthcare and social assistance programs.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="news" class="py-5 text-center">
        <div class="container">
            <h2 class="section-title">Latest News</h2>
            <div class="row">
                <div class="col-md-4 news-item">
                    <img src="https://source.unsplash.com/400x300/?cleanup,volunteer" class="img-fluid" alt="News">
                    <h5>Community Clean-Up</h5>
                    <p>Join us this weekend for a city-wide clean-up drive.</p>
                </div>
                <div class="col-md-4 news-item">
                    <img src="https://source.unsplash.com/400x300/?healthcare,clinic" class="img-fluid" alt="News">
                    <h5>New Healthcare Initiatives</h5>
                    <p>Free medical check-ups for all senior citizens.</p>
                </div>
                <div class="col-md-4 news-item">
                    <img src="https://source.unsplash.com/400x300/?construction,road" class="img-fluid" alt="News">
                    <h5>Infrastructure Development</h5>
                    <p>Upcoming road-widening projects in major areas.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer text-center">
        <div class="container">
            <h4>Contact Us</h4>
            <p><i class="fas fa-map-marker-alt"></i> Barangay 2, Enrile, Philippines</p>
            <p><i class="fas fa-phone"></i> 0917 729 9555</p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:lgu_enrile@yahoo.com">lgu_enrile@yahoo.com</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
