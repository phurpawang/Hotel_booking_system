<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Submitted - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-green-400 to-blue-500 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl p-12 text-center">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="bg-green-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-check-circle text-green-600 text-5xl"></i>
                </div>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                Registration Submitted Successfully!
            </h1>
            
            <p class="text-xl text-gray-700 mb-8">
                Thank you for registering your hotel with us!
            </p>

            <!-- What Happens Next -->
            <div class="bg-blue-50 rounded-lg p-6 mb-8 text-left">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i> What happens next?
                </h2>
                <ol class="space-y-3">
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm mr-3 mt-0.5 flex-shrink-0">1</span>
                        <span class="text-gray-700">Our admin team will review your registration details and documents</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm mr-3 mt-0.5 flex-shrink-0">2</span>
                        <span class="text-gray-700">We'll verify your tourism license and property documentation</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm mr-3 mt-0.5 flex-shrink-0">3</span>
                        <span class="text-gray-700">Upon approval, you'll receive your unique <strong>Hotel ID</strong> via email and SMS</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm mr-3 mt-0.5 flex-shrink-0">4</span>
                        <span class="text-gray-700">Use your Hotel ID and password to login and start managing your property</span>
                    </li>
                </ol>
            </div>

            <!-- Important Note -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8 text-left">
                <p class="text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Important:</strong> Please save your password safely. You'll need it along with your Hotel ID to access your dashboard.
                </p>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition shadow-lg">
                    <i class="fas fa-home mr-2"></i> Go to Homepage
                </a>
                
                <p class="text-gray-600">
                    Review typically takes 1-2 business days. You'll be notified via email and SMS.
                </p>
            </div>

            <!-- Support -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-gray-600 mb-2">Need help or have questions?</p>
                <p class="text-gray-800">
                    <i class="fas fa-envelope mr-2 text-blue-600"></i> support@bhutanhotels.com
                    <span class="mx-3">•</span>
                    <i class="fas fa-phone mr-2 text-blue-600"></i> +975-77397178
                </p>
            </div>
        </div>
    </div>
</body>
</html>
