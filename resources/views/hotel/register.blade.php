<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Registration - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/bhbs-logo.png') }}" alt="BHBS" style="height: 60px; width: 60px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <h1 class="text-2xl font-bold">Hotel Registration</h1>
                        <p class="text-sm text-blue-100">Join Bhutan's Leading Hotel Booking Platform</p>
                    </div>
                </div>
                <a href="{{ route('home') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Home
                </a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('hotel.register.submit') }}" method="POST" enctype="multipart/form-data" id="registrationForm" autocomplete="off">
                @csrf

                <!-- Step 1: Personal Details -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
                        <i class="fas fa-user text-blue-600 mr-2"></i> Step 1: Personal Details
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Owner Name *</label>
                            <input type="text" name="full_name" required value="{{ old('full_name') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter owner full name">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Owner Email *</label>
                            <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter owner email">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Mobile Number *</label>
                            <input type="tel" name="mobile" required value="{{ old('mobile') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter mobile number">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Create Password *</label>
                            <input type="password" name="password" required minlength="8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Minimum 8 characters">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Confirm Password *</label>
                            <input type="password" name="password_confirmation" required minlength="8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Re-enter password">
                        </div>
                    </div>
                </div>

                <!-- Step 2: Property Details -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
                        <i class="fas fa-hotel text-blue-600 mr-2"></i> Step 2: Property Details
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Hotel / Property Name *</label>
                            <input type="text" name="hotel_name" required value="{{ old('hotel_name') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Property Type *</label>
                            <select name="property_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="Hotel" selected>Hotel</option>
                                <option value="Resort">Resort</option>
                                <option value="Guest House">Guest House</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Select Dzongkhag *</label>
                            <select name="dzongkhag_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Dzongkhag</option>
                                @foreach($dzongkhags as $dzongkhag)
                                    <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag_id') == $dzongkhag->id ? 'selected' : '' }}>
                                        {{ $dzongkhag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Phone Number *</label>
                            <input type="tel" name="phone" required value="{{ old('phone') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Full Address *</label>
                            <textarea name="address" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Pin Location (map URL or coordinates)</label>
                            <input type="text" name="pin_location" value="{{ old('pin_location') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Google Maps URL or coordinates">
                            <p class="text-sm text-gray-500 mt-1">Enter a Google Maps URL or coordinates (e.g., 27.472792, 89.636421)</p>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Star Rating (Optional)</label>
                            <select name="star_rating" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Rating</option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Property Description</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Describe your property...">{{ old('description') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Property Image</label>
                            <input type="file" name="property_image" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-sm text-gray-500 mt-1">Upload a main image of your property (JPG, PNG, max 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Tourism Registration Details -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
                        <i class="fas fa-file-alt text-blue-600 mr-2"></i> Step 3: Tourism Registration Details
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Tourism License Number *</label>
                            <input type="text" name="tourism_license_number" required value="{{ old('tourism_license_number') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Issuing Authority *</label>
                            <input type="text" name="issuing_authority" required value="{{ old('issuing_authority') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">License Issue Date *</label>
                            <input type="date" name="license_issue_date" required value="{{ old('license_issue_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">License Expiry Date *</label>
                            <input type="date" name="license_expiry_date" required value="{{ old('license_expiry_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Step 4: Document Upload -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
                        <i class="fas fa-upload text-blue-600 mr-2"></i> Step 4: Required Documents
                    </h2>

                    <div class="space-y-6">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <p class="text-yellow-700">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Important:</strong> System does NOT allow submission without these documents.
                            </p>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Tourism License Document (PDF/Image) *</label>
                            <input type="file" name="tourism_license_doc" required accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-sm text-gray-600 mt-1">Accepted formats: PDF, JPG, PNG (Max: 5MB)</p>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Property Ownership or Lease Agreement (PDF/Image) *</label>
                            <input type="file" name="property_ownership_doc" required accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-sm text-gray-600 mt-1">Accepted formats: PDF, JPG, PNG (Max: 5MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Declaration -->
                <div class="mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <input type="checkbox" name="declaration" required id="declaration" class="mt-1 h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="declaration" class="ml-3 text-gray-700">
                                <strong>I confirm this property is legally registered and the information is correct.</strong>
                                <p class="text-sm text-gray-600 mt-2">
                                    By checking this box, I declare that all information provided is accurate and that my property holds valid tourism registration with the relevant authorities.
                                </p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="bg-green-600 text-white px-12 py-4 rounded-lg text-lg font-semibold hover:bg-green-700 transition shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i> Submit for Approval
                    </button>
                    <p class="text-sm text-gray-600 mt-4">
                        Your registration will be reviewed by our admin team. You'll receive your Hotel ID via email/SMS once approved.
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const passwordConfirmation = document.querySelector('input[name="password_confirmation"]').value;
            
            if (password !== passwordConfirmation) {
                e.preventDefault();
                alert('Password and Confirm Password do not match!');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
        });

        // Set minimum date for license expiry to issue date
        document.querySelector('input[name="license_issue_date"]').addEventListener('change', function() {
            document.querySelector('input[name="license_expiry_date"]').setAttribute('min', this.value);
        });
    </script>
</body>
</html>
