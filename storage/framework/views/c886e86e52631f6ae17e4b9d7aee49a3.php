<!-- ====================================================================
     MODERN DATE RANGE PICKER - BOOKING.COM STYLE
     ====================================================================
     BUG FIX: Uses Flatpickr with proper date state management
     
     KEY FIXES:
     1. No re-initialization on picker open (prevents date reset)
     2. Session storage validation (prevents stale past dates)
     3. Explicit separation: initial values ≠ user selections
     4. Proper onChange event handling
     5. Clear date preservation across navigation
     
     USAGE:
     <?php echo $__env->make('components.date-range-picker', [
         'check_in' => request('check_in', ''),
         'check_out' => request('check_out', '')
     ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     ==================================================================== -->

<?php
    // Optional DOM id prefix so multiple pickers can coexist safely.
    // Default: "srch" (search)
    $pickerId = $pickerId ?? 'srch';
    $btnId = $btnId ?? ($pickerId . '_dateRangeBtn');
    $popupId = $popupId ?? ($pickerId . '_dateRangePopup');
    $displayId = $displayId ?? ($pickerId . '_dateRangeDisplay');
    $containerId = $containerId ?? ($pickerId . '_flatpickrContainer');

    // Hidden input IDs used by other scripts (e.g. AJAX filtering).
    // Default to checkIn/checkOut for compatibility with existing code.
    $checkInInputId = $checkInInputId ?? 'checkIn';
    $checkOutInputId = $checkOutInputId ?? 'checkOut';
?>

<div id="<?php echo e($pickerId); ?>_dateRangePickerContainer" class="relative inline-block w-full">
    <!-- Display Button -->
    <button 
        type="button" 
        id="<?php echo e($btnId); ?>" 
        class="w-full px-3 py-2 lg:px-4 lg:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white h-10 lg:h-[50px] text-left font-medium flex justify-between items-center hover:border-blue-400 text-sm transition"
    >
        <span id="<?php echo e($displayId); ?>" class="truncate">Select dates</span>
        <i class="fas fa-calendar text-gray-400 flex-shrink-0 ml-1"></i>
    </button>

    <!-- Calendar Popup -->
    <div 
        id="<?php echo e($popupId); ?>" 
        class="hidden absolute top-full left-0 bg-white rounded-lg shadow-2xl border border-gray-200 z-50 mt-2 p-4"
        style="width: min(95vw, 760px);"
    >
        <!-- Calendar Container (Flatpickr will mount here) -->
        <div id="<?php echo e($containerId); ?>"></div>

        <!-- Footer Buttons -->
        <div class="flex justify-between items-center gap-2 mt-4 pt-4 border-t border-gray-200">
            <button 
                type="button" 
                id="<?php echo e($pickerId); ?>_clearDatesBtn" 
                class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition"
            >
                Clear
            </button>
            <button 
                type="button" 
                id="<?php echo e($pickerId); ?>_doneDatesBtn" 
                class="px-6 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition"
            >
                Done
            </button>
        </div>
    </div>

    <!-- Hidden Inputs for Form Submission -->
    <input 
        type="hidden" 
        name="check_in" 
        id="<?php echo e($checkInInputId); ?>" 
        value="<?php echo e($check_in ?? request('check_in', '')); ?>"
    >
    <input 
        type="hidden" 
        name="check_out" 
        id="<?php echo e($checkOutInputId); ?>" 
        value="<?php echo e($check_out ?? request('check_out', '')); ?>"
    >
</div>

<!-- Flatpickr Library -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    /* ===== FLATPICKR CUSTOMIZATION - BOOKING.COM STYLE ===== */
    
    .flatpickr-calendar {
        width: 100% !important;
        box-shadow: none !important;
        border: none !important;
        background: transparent !important;
        margin: 0 !important;
    }

    .flatpickr-months {
        display: flex;
        gap: 30px;
        padding: 0;
        margin: 0;
    }

    .flatpickr-month {
        flex: 1;
        width: auto !important;
    }

    .flatpickr-current-month {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
        padding: 0 0 12px 0;
        text-align: center;
    }

    .flatpickr-prev-month,
    .flatpickr-next-month {
        width: 32px !important;
        height: 32px !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        transition: all 0.2s;
        padding: 0 !important;
    }

    .flatpickr-prev-month:hover,
    .flatpickr-next-month:hover {
        background: #f3f4f6 !important;
        color: #111827 !important;
    }

    .flatpickr-weekday {
        font-weight: 600;
        font-size: 12px;
        color: #6b7280;
        padding: 8px 0;
    }

    .flatpickr-day {
        max-width: 36px;
        height: 36px;
        line-height: 36px;
        border-radius: 4px;
        font-size: 13px;
        color: #374151;
        padding: 0;
        margin: 2px;
        transition: all 0.15s ease;
    }

    .flatpickr-day:hover:not(.disabled):not(.flatpickr-disabled) {
        background: #e5e7eb !important;
        color: #111827 !important;
    }

    .flatpickr-day.today {
        font-weight: 700;
        color: #2563eb;
        background: transparent !important;
        border: 2px solid #2563eb;
    }

    .flatpickr-day.selected {
        background: #2563eb !important;
        color: white !important;
        font-weight: 700;
        border-radius: 4px;
    }

    .flatpickr-day.inRange {
        background: #dbeafe !important;
        color: #1e40af;
        border-radius: 0;
    }

    .flatpickr-day.disabled,
    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay {
        color: #d1d5db !important;
        background: transparent !important;
        cursor: not-allowed;
        opacity: 0.5;
    }

    .flatpickr-day.disabled,
    .flatpickr-day.flatpickr-disabled {
        text-decoration: line-through;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .flatpickr-months {
            flex-direction: column;
            gap: 20px;
        }

        #dateRangePopup {
            width: 95vw !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avoid double-initialization if the component is rendered more than once
    // with the same pickerId (e.g. partial updates).
    if (window.__bhbsDatePickers && window.__bhbsDatePickers['<?php echo e($pickerId); ?>']) return;
    window.__bhbsDatePickers = window.__bhbsDatePickers || {};
    window.__bhbsDatePickers['<?php echo e($pickerId); ?>'] = true;
    
    const dateRangeBtn = document.getElementById('<?php echo e($btnId); ?>');
    const dateRangePopup = document.getElementById('<?php echo e($popupId); ?>');
    const dateRangeDisplay = document.getElementById('<?php echo e($displayId); ?>');
    const checkInInput = document.getElementById('<?php echo e($checkInInputId); ?>');
    const checkOutInput = document.getElementById('<?php echo e($checkOutInputId); ?>');
    const clearDatesBtn = document.getElementById('<?php echo e($pickerId); ?>_clearDatesBtn');
    const doneDatesBtn = document.getElementById('<?php echo e($pickerId); ?>_doneDatesBtn');
    const flatpickrContainer = document.getElementById('<?php echo e($containerId); ?>');

    if (!dateRangeBtn || !dateRangePopup || !dateRangeDisplay || !checkInInput || !checkOutInput || !flatpickrContainer) {
        console.warn('[DatePicker] Missing required elements for pickerId=<?php echo e($pickerId); ?>');
        return;
    }

    // ===== GET INITIAL DATES (DO NOT RE-INITIALIZE PICKER ON OPEN) =====
    const today = new Date();
    const maxDate = new Date(today.getFullYear() + 2, today.getMonth(), today.getDate());
    
    // Read from hidden inputs (from Blade/request) or sessionStorage
    let initialCheckIn = checkInInput.value;
    let initialCheckOut = checkOutInput.value;

    // If not in request, try sessionStorage
    // Use "search_*" keys so it integrates with the search UX.
    if (!initialCheckIn) initialCheckIn = sessionStorage.getItem('search_check_in');
    if (!initialCheckOut) initialCheckOut = sessionStorage.getItem('search_check_out');

    // Booking.com UX: if nothing selected yet, prefill today + tomorrow (but never override real user selection)
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    if (!initialCheckIn && !initialCheckOut) {
        initialCheckIn = formatDate(today);
        initialCheckOut = formatDate(tomorrow);
        checkInInput.value = initialCheckIn;
        checkOutInput.value = initialCheckOut;
        sessionStorage.setItem('search_check_in', initialCheckIn);
        sessionStorage.setItem('search_check_out', initialCheckOut);
    }

    // Validate dates are not in past
    if (initialCheckIn) {
        const dt = new Date(initialCheckIn);
        if (dt < today) initialCheckIn = null;
    }
    if (initialCheckOut) {
        const dt = new Date(initialCheckOut);
        if (dt < today) initialCheckOut = null;
    }

    let isInitializing = true;

    // ===== INITIALIZE FLATPICKR ONCE =====
    const fp = flatpickr(flatpickrContainer, {
        mode: 'range',
        minDate: today,
        maxDate: maxDate,
        dateFormat: 'Y-m-d',
        showMonths: 2,
        enableTime: false,
        static: true,
        disableMobile: true,
        
        // BUG FIX: Only set defaultDate once from existing state (inputs/session), never force "today/tomorrow" later.
        defaultDate: (initialCheckIn && initialCheckOut) ? [new Date(initialCheckIn), new Date(initialCheckOut)] : [],
        
        // BUG FIX: Disable booked dates from backend
        disable: function(date) {
            const disabledDates = window.disabledDates || [];
            return disabledDates.some(d => {
                return new Date(d).toDateString() === date.toDateString();
            });
        },

        onDayCreate: function(dObj, dStr, fpInstance, dayElem) {
            // Optional: show price-per-night in each cell if provided as { 'YYYY-MM-DD': 1234 }
            const prices = window.datePrices || null;
            if (!prices) return;

            const y = dayElem.dateObj.getFullYear();
            const m = String(dayElem.dateObj.getMonth() + 1).padStart(2, '0');
            const d = String(dayElem.dateObj.getDate()).padStart(2, '0');
            const key = `${y}-${m}-${d}`;
            if (!Object.prototype.hasOwnProperty.call(prices, key)) return;

            const price = prices[key];
            const priceEl = document.createElement('div');
            priceEl.className = 'bhbs-day-price';
            priceEl.textContent = (price === null || price === undefined) ? '' : `Nu. ${price}`;
            dayElem.appendChild(priceEl);
        },

        onChange: function(selectedDates, dateStr, instance) {
            if (isInitializing) return;
            
            if (selectedDates.length === 2) {
                const checkIn = selectedDates[0];
                const checkOut = selectedDates[1];

                // Format dates
                const checkInStr = formatDate(checkIn);
                const checkOutStr = formatDate(checkOut);

                // BUG FIX: Update hidden inputs immediately
                checkInInput.value = checkInStr;
                checkOutInput.value = checkOutStr;

                // Update display
                updateDisplay(checkIn, checkOut);

                // BUG FIX: Save to sessionStorage for persistence
                sessionStorage.setItem('search_check_in', checkInStr);
                sessionStorage.setItem('search_check_out', checkOutStr);

                // Trigger AJAX filter if available
                if (typeof applyFilters === 'function') {
                    applyFilters();
                }
            }
        }
    });

    // ===== HELPER FUNCTIONS =====
    
    function formatDate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function updateDisplay(checkIn, checkOut) {
        const checkInText = checkIn.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        const checkOutText = checkOut.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        dateRangeDisplay.textContent = `${checkInText} — ${checkOutText}`;
    }

    function loadCalendarData() {
        // You can optionally set these globals before including the component:
        // window.calendarHotelId = <hotel_id>
        // window.calendarRoomId = <room_id>
        const params = new URLSearchParams();
        if (window.calendarHotelId) params.set('hotel_id', window.calendarHotelId);
        if (window.calendarRoomId) params.set('room_id', window.calendarRoomId);

        const url = '/api/availability/disabled-dates' + (params.toString() ? `?${params.toString()}` : '');
        fetch(url)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.disabledDates = data.data || [];
                    fp.redraw();
                }
            })
            .catch(() => {});
    }

    // ===== EVENT LISTENERS =====

    // Toggle popup (do NOT re-initialize picker)
    dateRangeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const isHidden = dateRangePopup.classList.contains('hidden');
        if (isHidden) {
            dateRangePopup.classList.remove('hidden');
            fp.open();
        } else {
            dateRangePopup.classList.add('hidden');
            fp.close();
        }
    });

    // Clear dates
    clearDatesBtn.addEventListener('click', function(e) {
        e.preventDefault();
        fp.clear();
        checkInInput.value = '';
        checkOutInput.value = '';
        dateRangeDisplay.textContent = 'Select dates';
        sessionStorage.removeItem('search_check_in');
        sessionStorage.removeItem('search_check_out');
        // Do not auto-trigger filters on clear; user can repick.
    });

    // Done button
    doneDatesBtn.addEventListener('click', function(e) {
        e.preventDefault();
        dateRangePopup.classList.add('hidden');
        fp.close();
    });

    // Close on outside click
    document.addEventListener('click', function(e) {
        if (!dateRangeBtn.contains(e.target) && !dateRangePopup.contains(e.target)) {
            dateRangePopup.classList.add('hidden');
            fp.close();
        }
    });

    // Initialize display if dates exist
    if (initialCheckIn && initialCheckOut) {
        updateDisplay(new Date(initialCheckIn), new Date(initialCheckOut));
    }

    // Load disabled dates
    loadCalendarData();

    // Store globally
    window.datePickerInstance = fp;
    isInitializing = false;
});
</script>

<style>
    /* Optional price label inside day cells */
    .bhbs-day-price {
        font-size: 10px;
        line-height: 1;
        margin-top: 2px;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 34px;
    }
</style>
<?php /**PATH C:\XAMPP\htdocs\BHBS\resources\views/components/date-range-picker.blade.php ENDPATH**/ ?>