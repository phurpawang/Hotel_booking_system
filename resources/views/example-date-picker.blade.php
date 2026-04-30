<!-- COMPLETE WORKING EXAMPLE - Hotel Search Page with Date Picker
     Copy this entire page to test the date picker bug fix
     No need for external integration - this is a standalone example
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking - Date Picker Example</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Search Section -->
        <section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-5xl mx-auto">
                    <h1 class="text-3xl font-bold mb-6">Find Your Perfect Hotel</h1>
                    
                    <form id="searchForm" class="bg-white rounded-lg shadow-lg p-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Location -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-map-marker-alt mr-1 text-blue-600"></i>Location
                            </label>
                            <select name="location" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Any Location</option>
                                <option value="thimphu">Thimphu</option>
                                <option value="paro">Paro</option>
                                <option value="punakha">Punakha</option>
                            </select>
                        </div>

                        <!-- Date Picker Component -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-calendar-alt mr-1 text-blue-600"></i>Check-in — Check-out
                            </label>
                            @include('components.date-range-picker')
                        </div>

                        <!-- Guests -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-users mr-1 text-blue-600"></i>Guests
                            </label>
                            <input type="number" name="guests" min="1" value="1" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Results Section -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <div id="loadingSpinner" class="hidden text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-600"></i>
                    <p class="mt-3 text-gray-600">Searching hotels...</p>
                </div>

                <div id="alertsContainer"></div>

                <div id="resultsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Select dates and search to see hotels</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Test/Debug Section -->
    <section class="bg-gray-100 py-8 border-t border-gray-300">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 class="text-2xl font-bold mb-4">
                <i class="fas fa-bug mr-2 text-red-600"></i>Date Picker Debug Info
            </h2>
            <div class="bg-white rounded-lg p-6 font-mono text-sm">
                <p class="mb-2"><strong>Check-in Input Value:</strong> <span id="debugCheckIn" class="text-blue-600">—</span></p>
                <p class="mb-2"><strong>Check-out Input Value:</strong> <span id="debugCheckOut" class="text-blue-600">—</span></p>
                <p class="mb-2"><strong>Session Storage Check-in:</strong> <span id="debugSessionCheckIn" class="text-green-600">—</span></p>
                <p class="mb-2"><strong>Session Storage Check-out:</strong> <span id="debugSessionCheckOut" class="text-green-600">—</span></p>
                <p class="mb-2"><strong>Today's Date:</strong> <span id="debugToday" class="text-gray-600">—</span></p>
                <hr class="my-3">
                <p class="text-xs text-gray-600">Console: Open DevTools (F12) to see detailed logs</p>
            </div>
        </div>
    </section>

    <script>
        // ===== DEBUG MONITORING =====
        function updateDebugInfo() {
            document.getElementById('debugCheckIn').textContent = 
                document.getElementById('checkInInput').value || '(empty)';
            document.getElementById('debugCheckOut').textContent = 
                document.getElementById('checkOutInput').value || '(empty)';
            document.getElementById('debugSessionCheckIn').textContent = 
                sessionStorage.getItem('datepicker_check_in') || '(empty)';
            document.getElementById('debugSessionCheckOut').textContent = 
                sessionStorage.getItem('datepicker_check_out') || '(empty)';
            document.getElementById('debugToday').textContent = new Date().toISOString().split('T')[0];
        }

        setInterval(updateDebugInfo, 500);
        updateDebugInfo();

        // ===== FORM SUBMISSION =====
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const checkIn = document.getElementById('checkInInput').value;
            const checkOut = document.getElementById('checkOutInput').value;
            const location = document.querySelector('input[name="location"]').value;
            const guests = document.querySelector('input[name="guests"]').value;

            console.log('=== FORM SUBMITTED ===');
            console.log('Check-in:', checkIn);
            console.log('Check-out:', checkOut);
            console.log('Location:', location);
            console.log('Guests:', guests);

            if (!checkIn || !checkOut) {
                showAlert('Please select check-in and check-out dates', 'error');
                return;
            }

            if (new Date(checkOut) <= new Date(checkIn)) {
                showAlert('Check-out date must be after check-in date', 'error');
                return;
            }

            // Simulate search
            showAlert('Searching hotels for ' + checkIn + ' to ' + checkOut + '...', 'info');
            document.getElementById('loadingSpinner').classList.remove('hidden');

            setTimeout(() => {
                document.getElementById('loadingSpinner').classList.add('hidden');
                showAlert('Search results would load here (API integration)', 'success');
                displayMockResults();
            }, 1500);
        });

        function showAlert(message, type) {
            const container = document.getElementById('alertsContainer');
            const classes = {
                'success': 'bg-green-100 border-green-400 text-green-800',
                'error': 'bg-red-100 border-red-400 text-red-800',
                'info': 'bg-blue-100 border-blue-400 text-blue-800'
            };
            
            const div = document.createElement('div');
            div.className = `border p-4 rounded-lg mb-4 ${classes[type] || classes.info}`;
            div.innerHTML = message;
            container.insertBefore(div, container.firstChild);
            
            setTimeout(() => div.remove(), 4000);
        }

        function displayMockResults() {
            const results = document.getElementById('resultsContainer');
            results.innerHTML = `
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="h-40 bg-gradient-to-br from-blue-400 to-blue-600"></div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1">Luxury Hotel</h3>
                        <p class="text-sm text-gray-600 mb-3">5-star accommodation</p>
                        <p class="text-xl font-bold text-gray-900">₹5,000<span class="text-sm text-gray-600">/night</span></p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="h-40 bg-gradient-to-br from-green-400 to-green-600"></div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1">Budget Hotel</h3>
                        <p class="text-sm text-gray-600 mb-3">3-star accommodation</p>
                        <p class="text-xl font-bold text-gray-900">₹2,500<span class="text-sm text-gray-600">/night</span></p>
                    </div>
                </div>
            `;
        }

        console.log('%c=== DATE PICKER INITIALIZED ===', 'color: green; font-size: 14px; font-weight: bold;');
        console.log('Select dates using the date picker above');
        console.log('Watch the debug values update in real-time');
        console.log('Submit the form to test integration');
    </script>
</body>
</html>
