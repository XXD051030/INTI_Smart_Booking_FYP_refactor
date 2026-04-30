<?php declare(strict_types=1); ?>
<!-- Date Selection and Controls -->
<div class="controls-card mb-4">
    <div class="row align-items-center">
        <div class="col-md-3">
            <label for="selectedDate" class="form-label fw-bold">
                <i class="fas fa-calendar me-2"></i>Select Date
            </label>
            <input type="date" class="form-control" id="selectedDate" value="<?= e((string) $today) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">
                <i class="fas fa-filter me-2"></i>Filter by Status
            </label>
            <select class="form-select" id="statusFilter">
                <option value="">All Bookings</option>
                <option value="confirmed">Confirmed Only</option>
                <option value="cancelled">Cancelled Only</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">
                <i class="fas fa-building me-2"></i>Filter by Facility
            </label>
            <select class="form-select" id="facilityFilter">
                <option value="">All Facilities</option>
                <?php foreach ($facilities as $facility): ?>
                    <option value="<?= e((string) $facility['id']) ?>">
                        <?= e((string) $facility['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary me-2" onclick="loadBookings()">
                <i class="fas fa-sync-alt me-1"></i>Refresh
            </button>
            <button class="btn btn-secondary" onclick="clearFilters()">
                <i class="fas fa-times me-1"></i>Clear
            </button>
        </div>
    </div>
</div>

<!-- Statistics Summary -->
<div class="row mb-4" id="statsContainer" style="display: none;">
    <div class="col-md-3">
        <div class="stats-card total">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted mb-1">Total Bookings</h5>
                    <h2 class="mb-0" id="totalBookings">0</h2>
                </div>
                <i class="fas fa-calendar-check fa-2x text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card confirmed">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted mb-1">Confirmed</h5>
                    <h2 class="mb-0" id="confirmedBookings">0</h2>
                </div>
                <i class="fas fa-check-circle fa-2x text-success"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card cancelled">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted mb-1">Cancelled</h5>
                    <h2 class="mb-0" id="cancelledBookings">0</h2>
                </div>
                <i class="fas fa-times-circle fa-2x text-danger"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card utilization">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted mb-1">Utilization</h5>
                    <h2 class="mb-0" id="utilizationRate">0%</h2>
                </div>
                <i class="fas fa-chart-pie fa-2x text-info"></i>
            </div>
        </div>
    </div>
</div>

<!-- Timetable -->
<div class="timetable-card">
    <div class="timetable-header">
        <h4 class="mb-0">
            <i class="fas fa-table me-2"></i>Daily Booking Schedule
            <span id="selectedDateDisplay" class="text-light opacity-75"></span>
        </h4>
    </div>

    <div class="timetable-container">
        <div class="table-responsive">
            <table class="table table-bordered timetable" id="bookingTable">
                <thead>
                    <tr>
                        <th class="time-header">Time Slot</th>
                        <?php foreach ($facilities as $facility): ?>
                            <th class="facility-header" data-facility-id="<?= e((string) $facility['id']) ?>">
                                <div class="facility-info">
                                    <strong><?= e((string) $facility['name']) ?></strong>
                                    <small class="d-block text-muted"><?= e((string) $facility['location']) ?></small>
                                    <small class="d-block">
                                        <i class="fas fa-users me-1"></i><?= e((string) $facility['capacity']) ?> capacity
                                    </small>
                                </div>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody id="timetableBody">
                    <?php foreach ($timeSlots as $time): ?>
                        <tr data-time="<?= e($time) ?>">
                            <td class="time-slot">
                                <strong><?= e($time) ?> - <?= e(date('H:i', strtotime($time . ' +1 hour'))) ?></strong>
                                <small class="d-block text-muted"><?= e(date('g:i A', strtotime($time))) ?> - <?= e(date('g:i A', strtotime($time . ' +1 hour'))) ?></small>
                            </td>
                            <?php foreach ($facilities as $facility): ?>
                                <td class="booking-cell"
                                    data-time="<?= e($time) ?>"
                                    data-facility="<?= e((string) $facility['id']) ?>">
                                    <div class="available-slot">
                                        <i class="fas fa-check-circle text-success me-1"></i>Available
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="loading-spinner" id="loadingSpinner" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Loading booking data...</p>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Booking Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingModalBody">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="cancelBookingBtn" onclick="cancelBooking()">
                    <i class="fas fa-times me-1"></i>Cancel Booking
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentBookingId = null;
    const facilities = <?= json_encode(array_map(static fn (array $f): array => ['id' => (int) $f['id'], 'name' => $f['name']], $facilities)) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedDateDisplay();
        loadBookings();
        document.getElementById('selectedDate').addEventListener('change', function() {
            updateSelectedDateDisplay();
            loadBookings();
        });
        document.getElementById('statusFilter').addEventListener('change', loadBookings);
        document.getElementById('facilityFilter').addEventListener('change', loadBookings);
    });

    function updateSelectedDateDisplay() {
        const selectedDate = document.getElementById('selectedDate').value;
        const dateObj = new Date(selectedDate);
        const formattedDate = dateObj.toLocaleDateString('en-US', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
        document.getElementById('selectedDateDisplay').textContent = ' - ' + formattedDate;
    }

    function loadBookings() {
        const selectedDate = document.getElementById('selectedDate').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const facilityFilter = document.getElementById('facilityFilter').value;

        document.getElementById('loadingSpinner').style.display = 'block';
        document.getElementById('bookingTable').style.opacity = '0.5';
        clearTimetable();

        const params = new URLSearchParams({ date: selectedDate, status: statusFilter });

        fetch(`<?= e(admin_url('get_bookings.php')) ?>?${params}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateTimetable(data.bookings, facilityFilter);
                    updateStatistics(calculateFilteredStats(data.bookings, facilityFilter));
                } else {
                    alert('Error loading bookings: ' + data.message);
                    if (data.message && data.message.includes('Unauthorized')) {
                        setTimeout(() => {
                            window.location.href = '<?= e(admin_url('index.php')) ?>';
                        }, 2000);
                    }
                }
            })
            .catch(error => alert('Error loading booking data: ' + error.message))
            .finally(() => {
                document.getElementById('loadingSpinner').style.display = 'none';
                document.getElementById('bookingTable').style.opacity = '1';
            });
    }

    function clearTimetable() {
        const cells = document.querySelectorAll('.booking-cell');
        const headers = document.querySelectorAll('.facility-header');
        const tableContainer = document.querySelector('.timetable-card');
        cells.forEach(cell => {
            cell.innerHTML = `<div class="available-slot"><i class="fas fa-check-circle text-success me-1"></i>Available</div>`;
            cell.className = 'booking-cell';
        });
        headers.forEach(header => header.classList.remove('filtered-facility'));
        if (tableContainer) tableContainer.classList.remove('facility-filter-active');
    }

    function populateTimetable(bookings, facilityFilter = '') {
        applyFacilityFilter(facilityFilter);
        bookings.forEach(booking => {
            const startTime = booking.start_time.substring(0, 5);
            const cell = document.querySelector(`[data-time="${startTime}"][data-facility="${booking.facility_id}"]`);
            if (cell) {
                const statusClass = booking.status === 'confirmed' ? 'confirmed' : 'cancelled';
                const statusIcon = booking.status === 'confirmed' ? 'check-circle' : 'times-circle';
                const statusColor = booking.status === 'confirmed' ? 'success' : 'danger';
                const isFiltered = facilityFilter && booking.facility_id != facilityFilter;
                const filterClass = isFiltered ? 'filtered-out' : '';
                cell.className = `booking-cell ${statusClass} ${filterClass}`;
                cell.innerHTML = `
                    <div class="booking-info" onclick="showBookingDetails(${booking.booking_id})">
                        <div class="booking-status">
                            <i class="fas fa-${statusIcon} text-${statusColor} me-1"></i>
                            <small class="status-text">${booking.status.toUpperCase()}</small>
                        </div>
                        <strong class="user-name">${booking.username}</strong>
                        <div class="user-email">${booking.email}</div>
                        <small class="booking-time">
                            <i class="fas fa-clock me-1"></i>
                            ${booking.start_time.substring(0, 5)} - ${booking.end_time.substring(0, 5)}
                        </small>
                    </div>
                `;
            }
        });
    }

    function applyFacilityFilter(facilityFilter) {
        const tableContainer = document.querySelector('.timetable-card');
        const facilityHeaders = document.querySelectorAll('.facility-header');
        const facilityCells = document.querySelectorAll('.booking-cell');
        facilityHeaders.forEach(header => header.classList.remove('filtered-facility'));
        facilityCells.forEach(cell => cell.classList.remove('filtered-facility'));
        if (facilityFilter) {
            tableContainer.classList.add('facility-filter-active');
            facilityHeaders.forEach(header => {
                if (header.getAttribute('data-facility-id') != facilityFilter) {
                    header.classList.add('filtered-facility');
                }
            });
            facilityCells.forEach(cell => {
                if (cell.getAttribute('data-facility') != facilityFilter) {
                    cell.classList.add('filtered-facility');
                }
            });
        } else {
            tableContainer.classList.remove('facility-filter-active');
        }
    }

    function calculateFilteredStats(bookings, facilityFilter) {
        const filteredBookings = facilityFilter ?
            bookings.filter(booking => booking.facility_id == facilityFilter) :
            bookings;
        const total = filteredBookings.length;
        const confirmed = filteredBookings.filter(b => b.status === 'confirmed').length;
        const cancelled = filteredBookings.filter(b => b.status === 'cancelled').length;
        const facilityCount = facilityFilter ? 1 : facilities.length;
        const timeSlots = 9;
        const totalSlots = facilityCount * timeSlots;
        const utilizationRate = totalSlots > 0 ? Math.round((confirmed / totalSlots) * 100 * 10) / 10 : 0;
        return { total, confirmed, cancelled, utilization_rate: utilizationRate };
    }

    function updateStatistics(stats) {
        document.getElementById('totalBookings').textContent = stats.total;
        document.getElementById('confirmedBookings').textContent = stats.confirmed;
        document.getElementById('cancelledBookings').textContent = stats.cancelled;
        document.getElementById('utilizationRate').textContent = stats.utilization_rate + '%';
        document.getElementById('statsContainer').style.display = 'flex';
    }

    function showBookingDetails(bookingId) {
        currentBookingId = bookingId;
        fetch(`<?= e(admin_url('get_bookings.php')) ?>?booking_id=${bookingId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.booking) {
                    const booking = data.booking;
                    const modalBody = document.getElementById('bookingModalBody');
                    modalBody.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="fas fa-user me-2"></i>User Information</h6>
                                <p><strong>Name:</strong> ${booking.username}</p>
                                <p><strong>Email:</strong> ${booking.email}</p>
                                <p><strong>User ID:</strong> #${booking.user_id}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="fas fa-building me-2"></i>Booking Information</h6>
                                <p><strong>Booking ID:</strong> #${booking.booking_id}</p>
                                <p><strong>Facility:</strong> ${booking.facility_name}</p>
                                <p><strong>Location:</strong> ${booking.location}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="fas fa-calendar me-2"></i>Schedule</h6>
                                <p><strong>Date:</strong> ${new Date(booking.booking_date).toLocaleDateString()}</p>
                                <p><strong>Time:</strong> ${booking.start_time} - ${booking.end_time}</p>
                                <p><strong>Status:</strong>
                                    <span class="badge bg-${booking.status === 'confirmed' ? 'success' : 'danger'}">
                                        ${booking.status.toUpperCase()}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="fas fa-info-circle me-2"></i>Additional Info</h6>
                                <p><strong>Created:</strong> ${new Date(booking.created_at).toLocaleString()}</p>
                                ${booking.cancelled_at ? `<p><strong>Cancelled:</strong> ${new Date(booking.cancelled_at).toLocaleString()}</p>` : ''}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-primary"><i class="fas fa-comment me-2"></i>Purpose</h6>
                                <div class="border rounded p-3 bg-light">
                                    ${booking.purpose}
                                </div>
                            </div>
                        </div>
                    `;
                    const cancelBtn = document.getElementById('cancelBookingBtn');
                    cancelBtn.style.display = booking.status === 'confirmed' ? 'inline-block' : 'none';
                    const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
                    modal.show();
                } else {
                    alert('Error loading booking details');
                }
            })
            .catch(error => alert('Error loading booking details'));
    }

    function cancelBooking() {
        if (!currentBookingId) return;
        if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
            fetch('<?= e(admin_url('actions.php')) ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=cancel_booking&booking_id=${currentBookingId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking cancelled successfully!');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('bookingModal'));
                    modal.hide();
                    loadBookings();
                } else {
                    alert('Error cancelling booking: ' + data.message);
                }
            })
            .catch(error => alert('Error cancelling booking'));
        }
    }

    function clearFilters() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('facilityFilter').value = '';
        loadBookings();
    }

    function exportBookings() {
        const selectedDate = document.getElementById('selectedDate').value;
        window.open(`<?= e(admin_url('get_bookings.php')) ?>?export=1&date=${selectedDate}`, '_blank');
    }
</script>
