<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success - Bhutan Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .success-card {
            max-width: 550px;
            margin: 2rem auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .success-icon {
            font-size: 5rem;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-card card">
            <div class="card-body text-center p-5">
                <i class="bi bi-check-circle success-icon mb-4"></i>
                
                <h2 class="text-success mb-3">Registration Submitted Successfully!</h2>
                
                <div class="alert alert-info text-start">
                    <h5><i class="bi bi-info-circle"></i> Your Hotel ID</h5>
                    <div class="bg-white p-3 rounded border border-primary mt-2">
                        <h3 class="text-primary mb-0">{{ $hotelId }}</h3>
                    </div>
                    <small class="text-muted">Please save this Hotel ID for future login</small>
                </div>

                <div class="alert alert-warning text-start">
                    <h6 class="fw-bold"><i class="bi bi-clock-history"></i> Awaiting Admin Approval</h6>
                    <p class="mb-0">
                        Your hotel registration is currently under review by our admin team. 
                        You will be able to login once your hotel is approved.
                    </p>
                </div>

                <div class="alert alert-light text-start">
                    <h6 class="fw-bold"><i class="bi bi-list-check"></i> What's Next?</h6>
                    <ul class="mb-0">
                        <li>Admin will review your documents</li>
                        <li>You will receive approval notification</li>
                        <li>Once approved, login with your Hotel ID and credentials</li>
                    </ul>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('hotel.check-status') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-search"></i> Check Registration Status
                    </a>
                    <a href="{{ route('hotel.login') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-box-arrow-in-right"></i> Go to Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
