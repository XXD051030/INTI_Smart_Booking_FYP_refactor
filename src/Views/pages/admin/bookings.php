<?php declare(strict_types=1); ?>
<!-- Date Selection and Controls -->
<div class="controls-card mb-4">
    <div class="row align-items-center">
        <div class="col-md-3">
            <label for="selectedDate" class="form-label fw-bold">
                <i class="fas fa-calendar me-2"></i><?= e(__('admin_bk_select_date')) ?>
            </label>
            <input type="date" class="form-control" id="selectedDate" value="<?= e((string) $today) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">
                <i class="fas fa-filter me-2"></i><?= e(__('admin_bk_filter_status')) ?>
            </label>
            <select class="form-select" id="statusFilter">
                <option value=""><?= e(__('admin_bk_all_bookings')) ?></option>
                <option value="confirmed"><?= e(__('admin_bk_confirmed_only')) ?></option>
                <option value="cancelled"><?= e(__('admin_bk_cancelled_only')) ?></option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">
                <i class="fas fa-building me-2"></i><?= e(__('admin_bk_filter_facility')) ?>
            </label>
            <select class="form-select" id="facilityFilter">
                <option value=""><?= e(__('admin_bk_all_facilities')) ?></option>
                <?php foreach ($facilities as $facility): ?>
                    <option value="<?= e((string) $facility['id']) ?>">
                        <?= e((string) $facility['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary me-2" onclick="loadBookings()">
                <i class="fas fa-sync-alt me-1"></i><?= e(__('admin_bk_refresh')) ?>
            </button>
            <button class="btn btn-secondary" onclick="clearFilters()">
                <i class="fas fa-times me-1"></i><?= e(__('admin_bk_clear')) ?>
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
                    <h5 class="text-muted mb-1"><?= e(__('admin_bk_total')) ?></h5>
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
                    <h5 class="text-muted mb-1"><?= e(__('admin_bk_confirmed')) ?></h5>
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
                    <h5 class="text-muted mb-1"><?= e(__('admin_bk_cancelled')) ?></h5>
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
                    <h5 class="text-muted mb-1"><?= e(__('admin_bk_utilization')) ?></h5>
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
            <i class="fas fa-table me-2"></i><?= e(__('admin_bk_schedule')) ?>
            <span id="selectedDateDisplay" class="text-light opacity-75"></span>
        </h4>
    </div>

    <div class="timetable-container">
        <div class="table-responsive">
            <table class="table table-bordered timetable" id="bookingTable">
                <thead>
                    <tr>
                        <th class="time-header"><?= e(__('admin_bk_time_slot')) ?></th>
                        <?php foreach ($facilities as $facility): ?>
                            <th class="facility-header" data-facility-id="<?= e((string) $facility['id']) ?>">
                                <div class="facility-info">
                                    <strong><?= e((string) $facility['name']) ?></strong>
                                    <small class="d-block text-muted"><?= e((string) $facility['location']) ?></small>
                                    <small class="d-block">
                                        <i class="fas fa-users me-1"></i><?= e((string) $facility['capacity']) ?> <?= e(__('admin_bk_capacity_suffix')) ?>
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
                                        <i class="fas fa-check-circle text-success me-1"></i><?= e(__('admin_bk_available')) ?>
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
            <span class="visually-hidden"><?= e(__('admin_bk_loading_short')) ?></span>
        </div>
        <p class="mt-2"><?= e(__('admin_bk_loading')) ?></p>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i><?= e(__('admin_bk_modal_title')) ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingModalBody">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= e(__('admin_bk_modal_close')) ?></button>
                <button type="button" class="btn btn-danger" id="cancelBookingBtn" onclick="cancelBooking()">
                    <i class="fas fa-times me-1"></i><?= e(__('admin_bk_modal_cancel')) ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.ADMIN_BK_LABELS = {
        available: <?= json_encode(__('admin_bk_available'), JSON_UNESCAPED_UNICODE) ?>,
        user_info: <?= json_encode(__('admin_bk_user_info'), JSON_UNESCAPED_UNICODE) ?>,
        booking_info: <?= json_encode(__('admin_bk_booking_info'), JSON_UNESCAPED_UNICODE) ?>,
        schedule_section: <?= json_encode(__('admin_bk_schedule_section'), JSON_UNESCAPED_UNICODE) ?>,
        additional_info: <?= json_encode(__('admin_bk_additional_info'), JSON_UNESCAPED_UNICODE) ?>,
        purpose: <?= json_encode(__('admin_bk_purpose'), JSON_UNESCAPED_UNICODE) ?>,
        field_name: <?= json_encode(__('admin_bk_field_name'), JSON_UNESCAPED_UNICODE) ?>,
        field_email: <?= json_encode(__('admin_bk_field_email'), JSON_UNESCAPED_UNICODE) ?>,
        field_user_id: <?= json_encode(__('admin_bk_field_user_id'), JSON_UNESCAPED_UNICODE) ?>,
        field_booking_id: <?= json_encode(__('admin_bk_field_booking_id'), JSON_UNESCAPED_UNICODE) ?>,
        field_facility: <?= json_encode(__('admin_bk_field_facility'), JSON_UNESCAPED_UNICODE) ?>,
        field_location: <?= json_encode(__('admin_bk_field_location'), JSON_UNESCAPED_UNICODE) ?>,
        field_date: <?= json_encode(__('admin_bk_field_date'), JSON_UNESCAPED_UNICODE) ?>,
        field_time: <?= json_encode(__('admin_bk_field_time'), JSON_UNESCAPED_UNICODE) ?>,
        field_status: <?= json_encode(__('admin_bk_field_status'), JSON_UNESCAPED_UNICODE) ?>,
        field_created: <?= json_encode(__('admin_bk_field_created'), JSON_UNESCAPED_UNICODE) ?>,
        field_cancelled: <?= json_encode(__('admin_bk_field_cancelled'), JSON_UNESCAPED_UNICODE) ?>,
        status_confirmed: <?= json_encode(__('admin_bk_status_confirmed'), JSON_UNESCAPED_UNICODE) ?>,
        status_cancelled: <?= json_encode(__('admin_bk_status_cancelled'), JSON_UNESCAPED_UNICODE) ?>,
        error_load_bookings: <?= json_encode(__('admin_bk_js_error_load_bookings'), JSON_UNESCAPED_UNICODE) ?>,
        error_load_data: <?= json_encode(__('admin_bk_js_error_load_data'), JSON_UNESCAPED_UNICODE) ?>,
        error_load_details: <?= json_encode(__('admin_bk_js_error_load_details'), JSON_UNESCAPED_UNICODE) ?>,
        error_cancel: <?= json_encode(__('admin_bk_js_error_cancel'), JSON_UNESCAPED_UNICODE) ?>,
        confirm_cancel: <?= json_encode(__('admin_bk_js_confirm_cancel'), JSON_UNESCAPED_UNICODE) ?>,
        cancel_success: <?= json_encode(__('admin_bk_js_cancel_success'), JSON_UNESCAPED_UNICODE) ?>
    };
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
                    alert(window.ADMIN_BK_LABELS.error_load_bookings + data.message);
                    if (data.message && data.message.includes('Unauthorized')) {
                        setTimeout(() => {
                            window.location.href = '<?= e(admin_url('index.php')) ?>';
                        }, 2000);
                    }
                }
            })
            .catch(error => alert(window.ADMIN_BK_LABELS.error_load_data + error.message))
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
            cell.innerHTML = `<div class="available-slot"><i class="fas fa-check-circle text-success me-1"></i>${window.ADMIN_BK_LABELS.available}</div>`;
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
                const statusLabel = booking.status === 'confirmed'
                    ? window.ADMIN_BK_LABELS.status_confirmed
                    : window.ADMIN_BK_LABELS.status_cancelled;
                const isFiltered = facilityFilter && booking.facility_id != facilityFilter;
                const filterClass = isFiltered ? 'filtered-out' : '';
                cell.className = `booking-cell ${statusClass} ${filterClass}`;
                cell.innerHTML = `
                    <div class="booking-info" onclick="showBookingDetails(${booking.booking_id})">
                        <div class="booking-status">
                            <i class="fas fa-${statusIcon} text-${statusColor} me-1"></i>
                            <small class="status-text">${statusLabel}</small>
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
                    const L = window.ADMIN_BK_LABELS;
                    const statusBadgeLabel = booking.status === 'confirmed' ? L.status_confirmed : L.status_cancelled;
                    modalBody.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="fas fa-user me-2"></i>${L.user_info}</h6>
                                <p><strong>${L.field_name}</strong> ${booking.username}</p>
                                <p><strong>${L.field_email}</strong> ${booking.email}</p>
                                <p><strong>${L.field_user_id}</strong> #${booking.user_id}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="fas fa-building me-2"></i>${L.booking_info}</h6>
                                <p><strong>${L.field_booking_id}</strong> #${booking.booking_id}</p>
                                <p><strong>${L.field_facility}</strong> ${booking.facility_name}</p>
                                <p><strong>${L.field_location}</strong> ${booking.location}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="fas fa-calendar me-2"></i>${L.schedule_section}</h6>
                                <p><strong>${L.field_date}</strong> ${new Date(booking.booking_date).toLocaleDateString()}</p>
                                <p><strong>${L.field_time}</strong> ${booking.start_time} - ${booking.end_time}</p>
                                <p><strong>${L.field_status}</strong>
                                    <span class="badge bg-${booking.status === 'confirmed' ? 'success' : 'danger'}">
                                        ${statusBadgeLabel}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="fas fa-info-circle me-2"></i>${L.additional_info}</h6>
                                <p><strong>${L.field_created}</strong> ${new Date(booking.created_at).toLocaleString()}</p>
                                ${booking.cancelled_at ? `<p><strong>${L.field_cancelled}</strong> ${new Date(booking.cancelled_at).toLocaleString()}</p>` : ''}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-primary"><i class="fas fa-comment me-2"></i>${L.purpose}</h6>
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
                    alert(window.ADMIN_BK_LABELS.error_load_details);
                }
            })
            .catch(error => alert(window.ADMIN_BK_LABELS.error_load_details));
    }

    function cancelBooking() {
        if (!currentBookingId) return;
        if (confirm(window.ADMIN_BK_LABELS.confirm_cancel)) {
            const csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
            fetch('<?= e(admin_url('actions.php')) ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-Token': csrfToken },
                body: `action=cancel_booking&booking_id=${encodeURIComponent(currentBookingId)}&_token=${encodeURIComponent(csrfToken)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(window.ADMIN_BK_LABELS.cancel_success);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('bookingModal'));
                    modal.hide();
                    loadBookings();
                } else {
                    alert(window.ADMIN_BK_LABELS.error_cancel + data.message);
                }
            })
            .catch(error => alert(window.ADMIN_BK_LABELS.error_cancel));
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
