@extends(
    strtoupper(Auth::user()->role) === 'MANAGER' ? 'manager.layouts.app' :
    (in_array(strtoupper(Auth::user()->role), ['RECEPTIONIST', 'RECEPTION']) ? 'reception.layouts.app' : 'layouts.owner-bootstrap')
)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-2">Room {{ $room->room_number }} - Availability Calendar</h3>
                    <p class="text-muted mb-0">{{ $room->hotel->name ?? 'Hotel' }}</p>
                </div>
                <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.show', $room->id) }}" class="btn btn-secondary">
                    Back to Room
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" id="monthYear">Loading...</h5>
                        <div class="d-flex gap-2">
                            <button id="prevMonth" class="btn btn-sm btn-light">Previous</button>
                            <button id="nextMonth" class="btn btn-sm btn-light">Next</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="calendarTable">
                        <thead>
                            <tr class="bg-light">
                                <th class="text-center">Sun</th>
                                <th class="text-center">Mon</th>
                                <th class="text-center">Tue</th>
                                <th class="text-center">Wed</th>
                                <th class="text-center">Thu</th>
                                <th class="text-center">Fri</th>
                                <th class="text-center">Sat</th>
                            </tr>
                        </thead>
                        <tbody id="calendarBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Bookings</h6>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <div id="bookingsList">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var roomId = {{ $room->id }};
var initialMonth = {{ $month }};
var initialYear = {{ $year }};
var currentMonth = initialMonth;
var currentYear = initialYear;
var allBookings = [];

function generateCalendar(month, year) {
    var firstDay = new Date(year, month - 1, 1).getDay();
    var daysInMonth = new Date(year, month, 0).getDate();
    var daysInPrevMonth = new Date(year, month - 1, 0).getDate();
    var calendarBody = document.getElementById('calendarBody');
    calendarBody.innerHTML = '';
    var dayCounter = 1;
    var nextMonthCounter = 1;

    for (var i = 0; i < 6; i++) {
        var row = document.createElement('tr');
        for (var j = 0; j < 7; j++) {
            var cell = document.createElement('td');
            cell.style.height = '60px';
            cell.style.padding = '8px';
            cell.style.verticalAlign = 'top';
            var dayContent = '';

            if (i === 0 && j < firstDay) {
                dayContent = daysInPrevMonth - firstDay + j + 1;
                cell.style.backgroundColor = '#fafafa';
                cell.style.color = '#bdbdbd';
            } else if (dayCounter <= daysInMonth) {
                var date = new Date(year, month - 1, dayCounter);
                var today = new Date();
                dayContent = dayCounter;

                var hasBooking = allBookings.some(function(booking) {
                    var checkIn = new Date(booking.check_in_date);
                    var checkOut = new Date(booking.check_out_date);
                    return date >= checkIn && date < checkOut;
                });

                cell.style.backgroundColor = hasBooking ? '#ffebee' : '#e8f5e9';

                if (date.toDateString() === today.toDateString()) {
                    cell.style.border = '2px solid #667eea';
                    cell.style.fontWeight = 'bold';
                }
                dayCounter++;
            } else {
                dayContent = nextMonthCounter;
                cell.style.backgroundColor = '#fafafa';
                cell.style.color = '#bdbdbd';
                nextMonthCounter++;
            }
            cell.innerHTML = '<strong>' + dayContent + '</strong>';
            row.appendChild(cell);
        }
        calendarBody.appendChild(row);
    }
    document.getElementById('monthYear').textContent = new Date(year, month - 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
}

function updateBookingsList() {
    var bookingsList = document.getElementById('bookingsList');
    if (allBookings.length === 0) {
        bookingsList.innerHTML = '<p class="text-muted text-center py-4">No bookings this month</p>';
        return;
    }
    var html = '';
    allBookings.forEach(function(booking) {
        var checkIn = new Date(booking.check_in_date);
        var checkOut = new Date(booking.check_out_date);
        html += '<div class="mb-3 pb-3 border-bottom">';
        html += '<strong>' + booking.guest_name + '</strong>';
        html += '<br><small class="text-muted">' + checkIn.toLocaleDateString() + ' - ' + checkOut.toLocaleDateString() + '</small>';
        html += '<br><span class="badge bg-info mt-2">' + booking.status + '</span></div>';
    });
    bookingsList.innerHTML = html;
}

function loadCalendar() {
    fetch('/api/rooms/' + roomId + '/availability?month=' + currentMonth + '&year=' + currentYear)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            allBookings = data.bookings || [];
            generateCalendar(currentMonth, currentYear);
            updateBookingsList();
        })
        .catch(function(e) {
            console.error('Error:', e);
            document.getElementById('bookingsList').innerHTML = '<p class="text-danger">Error loading bookings</p>';
        });
}

document.getElementById('prevMonth').addEventListener('click', function() {
    currentMonth--;
    if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
    }
    loadCalendar();
});

document.getElementById('nextMonth').addEventListener('click', function() {
    currentMonth++;
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
    }
    loadCalendar();
});

loadCalendar();
</script>
@endsection
