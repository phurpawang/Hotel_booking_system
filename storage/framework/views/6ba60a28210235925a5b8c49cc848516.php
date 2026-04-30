<!-- Reusable Search Bar Component -->
<section class="<?php if($sticky ?? false): ?> sticky top-0 <?php endif; ?> bg-blue-600 text-white shadow-lg z-40">
    <div class="container mx-auto px-4 py-4">
        <!-- Search Form - Booking.com Style -->
        <div class="bg-white rounded-xl shadow-2xl p-3 max-w-6xl mx-auto">
            <form action="<?php echo e(route('guest.search')); ?>" method="GET" class="flex flex-wrap items-end gap-2 lg:gap-3">
                <!-- Dzongkhag -->
                <div class="flex-1 min-w-[160px]">
                    <label class="block text-gray-900 font-semibold mb-1 text-xs lg:text-sm">Location</label>
                    <select name="dzongkhag_id" class="w-full px-3 py-2 lg:px-4 lg:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white h-10 lg:h-[50px] text-sm">
                        <option value="">Select Dzongkhag</option>
                        <?php $__currentLoopData = $dzongkhags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dzongkhag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dzongkhag->id); ?>" <?php echo e(($dzongkhag_id ?? request('dzongkhag_id')) == $dzongkhag->id ? 'selected' : ''); ?>><?php echo e($dzongkhag->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Check-in & Check-out Date Range -->
                <div class="flex-1 min-w-[220px] relative">
                    <label class="block text-gray-900 font-semibold mb-1 text-xs lg:text-sm">Check-in — Check-out</label>
                    <?php echo $__env->make('components.date-range-picker', [
                        'pickerId' => 'srch',
                        'check_in' => $check_in ?? request('check_in', ''),
                        'check_out' => $check_out ?? request('check_out', ''),
                        'checkInInputId' => 'checkIn',
                        'checkOutInputId' => 'checkOut',
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <!-- Guests & Rooms - Interactive Picker -->
                <div class="flex-1 min-w-[160px] relative">
                    <label class="block text-gray-900 font-semibold mb-1 text-xs lg:text-sm">Guests & rooms</label>
                    <button type="button" id="guestPickerBtn" class="w-full px-3 py-2 lg:px-4 lg:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white h-10 lg:h-[50px] text-left font-medium flex justify-between items-center hover:border-blue-400 text-xs lg:text-base">
                        <span id="guestPickerDisplay" class="truncate">1 adult • 0 children • 1 room</span>
                        <i class="fas fa-chevron-down text-gray-400 flex-shrink-0 ml-1"></i>
                    </button>

                    <!-- Guest Picker Popup -->
                    <div id="guestPickerPopup" class="hidden absolute top-full right-0 bg-white rounded-lg shadow-2xl border border-gray-200 z-50 w-80 p-4 lg:p-6 mt-1">
                        <!-- Adults -->
                        <div class="mb-4 lg:mb-6">
                            <h3 class="text-gray-900 font-semibold text-xs lg:text-sm mb-2 lg:mb-3">Adults</h3>
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-2 lg:p-3">
                                <button type="button" class="decrement-adults w-8 h-8 lg:w-10 lg:h-10 rounded-lg hover:bg-gray-200 font-bold text-gray-600 text-sm lg:text-base">−</button>
                                <input type="number" name="adults" id="adultsInput" min="1" value="<?php echo e($adults ?? request('adults', 1)); ?>" class="guest-input w-14 text-center text-base lg:text-lg font-bold border-0 bg-transparent" readonly>
                                <button type="button" class="increment-adults w-8 h-8 lg:w-10 lg:h-10 rounded-lg hover:bg-gray-200 font-bold text-gray-600 text-sm lg:text-base">+</button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 lg:mt-2">Age 18+</p>
                        </div>

                        <!-- Children -->
                        <div class="mb-4 lg:mb-6">
                            <h3 class="text-gray-900 font-semibold text-xs lg:text-sm mb-2 lg:mb-3">Children</h3>
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-2 lg:p-3">
                                <button type="button" class="decrement-children w-8 h-8 lg:w-10 lg:h-10 rounded-lg hover:bg-gray-200 font-bold text-gray-600 text-sm lg:text-base">−</button>
                                <input type="number" name="children" id="childrenInput" min="0" value="<?php echo e($children ?? request('children', 0)); ?>" class="guest-input w-14 text-center text-base lg:text-lg font-bold border-0 bg-transparent" readonly>
                                <button type="button" class="increment-children w-8 h-8 lg:w-10 lg:h-10 rounded-lg hover:bg-gray-200 font-bold text-gray-600 text-sm lg:text-base">+</button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 lg:mt-2">Age 0-17</p>
                        </div>

                        <!-- Rooms -->
                        <div class="mb-4 lg:mb-6">
                            <h3 class="text-gray-900 font-semibold text-xs lg:text-sm mb-2 lg:mb-3">Rooms</h3>
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-2 lg:p-3">
                                <button type="button" class="decrement-rooms w-8 h-8 lg:w-10 lg:h-10 rounded-lg hover:bg-gray-200 font-bold text-gray-600 text-sm lg:text-base">−</button>
                                <input type="number" name="rooms" id="roomsInput" min="1" value="<?php echo e($rooms ?? request('rooms', 1)); ?>" class="guest-input w-14 text-center text-base lg:text-lg font-bold border-0 bg-transparent" readonly>
                                <button type="button" class="increment-rooms w-8 h-8 lg:w-10 lg:h-10 rounded-lg hover:bg-gray-200 font-bold text-gray-600 text-sm lg:text-base">+</button>
                            </div>
                        </div>

                        <!-- Done Button -->
                        <button type="button" id="guestPickerDone" class="w-full bg-blue-600 text-white py-2 lg:py-3 rounded-lg font-semibold hover:bg-blue-700 transition text-sm lg:text-base">
                            Done
                        </button>
                    </div>

                    <!-- Hidden Inputs for Form -->
                    <input type="hidden" name="adults" value="<?php echo e($adults ?? request('adults', 1)); ?>">
                    <input type="hidden" name="children" value="<?php echo e($children ?? request('children', 0)); ?>">
                    <input type="hidden" name="rooms" value="<?php echo e($rooms ?? request('rooms', 1)); ?>">
                </div>

                <!-- Search Button -->
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 lg:px-8 lg:py-3 rounded-lg text-sm lg:text-base font-semibold hover:bg-blue-700 transition shadow-lg whitespace-nowrap h-10 lg:h-[50px] flex items-center justify-center">
                    <i class="fas fa-search mr-1 lg:mr-2"></i> <span class="hidden lg:inline">Search</span>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Styles for Search Bar -->
<style>
    input[type="number"],
    select {
        color: #1f2937 !important;
        background-color: white !important;
    }
    
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        opacity: 1;
    }
    
    input[type="number"] {
        text-align: center;
        font-weight: 600;
    }
    
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    
    .guest-input::-webkit-inner-spin-button,
    .guest-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    .guest-input {
        appearance: textfield;
        -moz-appearance: textfield;
    }

    /* Date picker styling is provided by `components/date-range-picker` */
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const guestPickerBtn = document.getElementById('guestPickerBtn');
    const guestPickerPopup = document.getElementById('guestPickerPopup');
    const guestPickerDone = document.getElementById('guestPickerDone');
    const guestPickerDisplay = document.getElementById('guestPickerDisplay');
    
    const adultsInput = document.getElementById('adultsInput');
    const childrenInput = document.getElementById('childrenInput');
    const roomsInput = document.getElementById('roomsInput');
    
    const formAdultsInputs = document.querySelectorAll('input[name="adults"]');
    const formChildrenInputs = document.querySelectorAll('input[name="children"]');
    const formRoomsInputs = document.querySelectorAll('input[name="rooms"]');
    const dzongkhagSelect = document.querySelector('select[name="dzongkhag_id"]');
    // Date selection is handled by `components.date-range-picker` (Flatpickr).
    
    // Close guest picker when clicking outside
    document.addEventListener('click', function(e) {
        if (!guestPickerBtn.contains(e.target) && !guestPickerPopup.contains(e.target)) {
            guestPickerPopup.classList.add('hidden');
        }
    });
    
    // ============================================================
    // Search Parameters (URL + SessionStorage)
    // ============================================================
    function isPageRefreshed() {
        if (window.performance && window.performance.getEntriesByType) {
            const navEntries = window.performance.getEntriesByType('navigation');
            if (navEntries.length > 0) {
                return navEntries[0].type === 'reload';
            }
        }
        return performance.navigation.type === 1;
    }
    
    function loadSearchParameters() {
        let dzongkhag = dzongkhagSelect.value || '';
        let adults = adultsInput.value || '1';
        let children = childrenInput.value || '0';
        let rooms = roomsInput.value || '1';

        if (!dzongkhag) {
            dzongkhag = sessionStorage.getItem('search_dzongkhag_id') || '';
        }
        
        adultsInput.value = parseInt(adults);
        childrenInput.value = parseInt(children);
        roomsInput.value = parseInt(rooms);
        
        if (dzongkhag) dzongkhagSelect.value = dzongkhag;
    }
    
    function saveSearchParameters() {
        sessionStorage.setItem('search_adults', adultsInput.value);
        sessionStorage.setItem('search_children', childrenInput.value);
        sessionStorage.setItem('search_rooms', roomsInput.value);
        sessionStorage.setItem('search_dzongkhag_id', dzongkhagSelect.value);
    }
    
    loadSearchParameters();
    
    // ============================================================
    // Guest Picker
    // ============================================================
    guestPickerBtn.addEventListener('click', function(e) {
        e.preventDefault();
        guestPickerPopup.classList.toggle('hidden');
    });
    
    guestPickerDone.addEventListener('click', function() {
        guestPickerPopup.classList.add('hidden');
        saveSearchParameters();
    });
    
    function updateDisplay() {
        const adults = parseInt(adultsInput.value) || 0;
        const children = parseInt(childrenInput.value) || 0;
        const rooms = parseInt(roomsInput.value) || 0;
        
        const adultText = adults === 1 ? 'adult' : 'adults';
        const childrenText = children === 1 ? 'child' : 'children';
        const roomsText = rooms === 1 ? 'room' : 'rooms';
        
        guestPickerDisplay.textContent = `${adults} ${adultText} • ${children} ${childrenText} • ${rooms} ${roomsText}`;
        
        formAdultsInputs.forEach(input => input.value = adults);
        formChildrenInputs.forEach(input => input.value = children);
        formRoomsInputs.forEach(input => input.value = rooms);
    }
    
    document.querySelector('.increment-adults').addEventListener('click', function(e) {
        e.preventDefault();
        adultsInput.value = Math.max(1, parseInt(adultsInput.value) + 1);
        updateDisplay();
        saveSearchParameters();
    });
    
    document.querySelector('.decrement-adults').addEventListener('click', function(e) {
        e.preventDefault();
        adultsInput.value = Math.max(1, parseInt(adultsInput.value) - 1);
        updateDisplay();
        saveSearchParameters();
    });
    
    document.querySelector('.increment-children').addEventListener('click', function(e) {
        e.preventDefault();
        childrenInput.value = Math.max(0, parseInt(childrenInput.value) + 1);
        updateDisplay();
        saveSearchParameters();
    });
    
    document.querySelector('.decrement-children').addEventListener('click', function(e) {
        e.preventDefault();
        childrenInput.value = Math.max(0, parseInt(childrenInput.value) - 1);
        updateDisplay();
        saveSearchParameters();
    });
    
    document.querySelector('.increment-rooms').addEventListener('click', function(e) {
        e.preventDefault();
        roomsInput.value = Math.max(1, parseInt(roomsInput.value) + 1);
        updateDisplay();
        saveSearchParameters();
    });
    
    document.querySelector('.decrement-rooms').addEventListener('click', function(e) {
        e.preventDefault();
        roomsInput.value = Math.max(1, parseInt(roomsInput.value) - 1);
        updateDisplay();
        saveSearchParameters();
    });
    
    updateDisplay();
    
    document.querySelector('form').addEventListener('submit', saveSearchParameters);
});
</script>
<?php /**PATH C:\XAMPP\htdocs\BHBS\resources\views/components/search-bar.blade.php ENDPATH**/ ?>