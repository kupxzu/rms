<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGU Enrile - Record Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        
        .hero-section {
            background: linear-gradient(rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8)), url('image.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .hero-content {
            color: white;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        
        .btn-custom {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom {
            background: #ff6b6b;
            border: none;
            color: white;
        }
        
        .btn-primary-custom:hover {
            background: #ff5252;
            transform: translateY(-2px);
        }
        
        .btn-outline-custom {
            border: 2px solid white;
            color: white;
            background: transparent;
        }
        
        .btn-outline-custom:hover {
            background: white;
            color: #667eea;
        }
        
        .auth-modal .modal-content {
            border-radius: 20px;
            border: none;
        }
        
        .modal-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            display: block;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1.5rem 1rem;
        }
        
        .auth-tabs .nav-link {
            border: none;
            color: #666;
            font-weight: 500;
        }
        
        .auth-tabs .nav-link.active {
            color: #667eea;
            border-bottom: 2px solid #667eea;
        }
        
        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            margin-bottom: 1rem;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }
        
        .features-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .feature-card {
            text-align: center;
            padding: 2rem;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="hero-content">
                        <h1 class="hero-title">LGU Enrile</h1>
                        <p class="hero-subtitle">Tuguegarao, Cagayan</p>
                        <p class="lead mb-4">Modern Record Management System for efficient government operations and citizen services.</p>
                        <div class="d-flex gap-3 flex-wrap justify-content-center">
                            <button class="btn btn-primary-custom btn-custom" data-bs-toggle="modal" data-bs-target="#authModal">
                                <i class="fas fa-sign-in-alt me-2"></i>Get Started
                            </button>
                            <button class="btn btn-outline-custom btn-custom" onclick="scrollToFeatures()">
                                <i class="fas fa-info-circle me-2"></i>Learn More
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Our Services</h2>
                <p class="lead">Streamlined government services for the community</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-file-alt feature-icon"></i>
                        <h4>Document Management</h4>
                        <p>Secure and organized document storage and retrieval system.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-users feature-icon"></i>
                        <h4>Citizen Services</h4>
                        <p>Easy access to government services and applications online.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-chart-bar feature-icon"></i>
                        <h4>Data Analytics</h4>
                        <p>Comprehensive reporting and analytics for better decision making.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Authentication Modal -->
    <div class="modal fade auth-modal" id="authModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="text-center w-100">
                        <img src="includes/www.png" alt="LGU Enrile Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Tabs -->


                    <div class="tab-content">
                        <!-- Sign In Tab -->
                        <div class="tab-pane fade show active" id="signin-tab">
                            <form action="function.php" method="POST">
                                <h3 class="text-center mb-4">Welcome Back!</h3>
                                
                                <div class="mb-3">
                                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                                </div>

                                <div class="mb-3">
                                    <div class="password-wrapper">
                                        <input type="password" name="password" id="signinPassword" class="form-control" placeholder="Password" required>
                                        <i class="fas fa-eye password-toggle" onclick="togglePassword('signinPassword', this)"></i>
                                    </div>
                                </div>

                                <?php
                                if (isset($_SESSION['error'])) {
                                    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                                    unset($_SESSION['error']);
                                }
                                ?>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary-custom btn-custom">
                                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Sign Up Tab -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Local Government Unit of Enrile</h5>
                    <p>Tuguegarao, Cagayan</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 LGU Enrile. All rights reserved.</p>
                    <a href="official.php" class="text-light">OFFICIALS MAYORS PANELS</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(inputId, icon) {
            const passwordField = document.getElementById(inputId);
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        function scrollToFeatures() {
            document.getElementById('features').scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Auto-show modal if there's an error
        <?php if (isset($_SESSION['error'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Modal(document.getElementById('authModal')).show();
        });
        <?php endif; ?>
    </script>
</body>
</html>