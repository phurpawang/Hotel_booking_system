<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BHBS - Bhutan Hotel Booking System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .feature-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 32px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .navbar-brand-custom {
            font-size: 1.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm position-fixed w-100" style="z-index: 1000;">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom" href="#" style="display: flex; align-items: center;">
                <img src="{{ asset('images/bhbs-logo.png') }}" alt="BHBS" style="height: 50px; width: 50px; border-radius: 50%; object-fit: cover; background: white; padding: 2px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link">
                                    <i class="bi bi-speedometer2 me-1"></i>Dashboard
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Login
                                </a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-custom text-white ms-2">
                                        <i class="bi bi-person-plus me-1"></i>Register
                                    </a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Welcome to BHBS</h1>
                    <h2 class="h3 mb-4">Bhutan Hotel Booking System</h2>
                    <p class="lead mb-5">
                        Experience the beauty of Bhutan with our comprehensive hotel booking platform. 
                        Manage hotels, rooms, and bookings with ease.
                    </p>
                    <div class="d-flex gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg">
                                <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Get Started
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-building" style="font-size: 15rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Role-Based Access System</h2>
                <p class="lead text-muted">Designed for different user roles with specific permissions</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-4">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-shield-check text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-bold text-center mb-3">Admin</h5>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Approve hotels</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Manage all users</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>System overview</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Full control</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-4">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-building text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-bold text-center mb-3">Owner</h5>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Manage hotels</li>
                            <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Add/edit rooms</li>
                            <li><i class="bi bi-check-circle-fill text-primary me-2"></i>View bookings</li>
                            <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Financial reports</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-4">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-person-badge text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-bold text-center mb-3">Manager</h5>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle-fill text-info me-2"></i>Manage rooms</li>
                            <li><i class="bi bi-check-circle-fill text-info me-2"></i>Handle bookings</li>
                            <li><i class="bi bi-check-circle-fill text-info me-2"></i>Update status</li>
                            <li><i class="bi bi-check-circle-fill text-info me-2"></i>Daily operations</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-4">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-person-workspace text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-bold text-center mb-3">Reception</h5>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle-fill text-warning me-2"></i>Check-in/out</li>
                            <li><i class="bi bi-check-circle-fill text-warning me-2"></i>View bookings</li>
                            <li><i class="bi bi-check-circle-fill text-warning me-2"></i>Guest info</li>
                            <li><i class="bi bi-check-circle-fill text-warning me-2"></i>Front desk ops</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container py-5 text-center text-white">
            <h2 class="display-5 fw-bold mb-4">Ready to Get Started?</h2>
            <p class="lead mb-4">Join BHBS today and experience seamless hotel management</p>
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-speedometer2 me-2"></i>Access Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-person-plus me-2"></i>Create Account Now
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        <i class="bi bi-building me-2"></i>
                        <strong>BHBS</strong> - Bhutan Hotel Booking System
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-muted">© 2024 All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
