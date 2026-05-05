<?php declare(strict_types=1); ?>
<?php
$today = date('Y-m-d');
$upcomingCount = 0;
$completedCount = 0;
$cancelledCount = 0;
foreach ($bookings as $b) {
    if (($b['status'] ?? '') === 'cancelled') {
        $cancelledCount++;
    } elseif ($b['booking_date'] >= $today) {
        $upcomingCount++;
    } else {
        $completedCount++;
    }
}

$canCancelBooking = static function (string $bookingDate, string $startTime): bool {
    $bookingTimestamp = strtotime($bookingDate . ' ' . $startTime);
    return (($bookingTimestamp - time()) / 60) > 30;
};
$formatTime = static fn (string $t): string => date('g:i A', strtotime($t));
$formatDate = static fn (string $d): string => date('l, F j, Y', strtotime($d));
?>

<!-- Alert for messages -->
<div id="alert-container"></div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-calendar-check text-primary fs-2 mb-2"></i>
                <h5 class="card-title"><?= e(__('Totalbk')) ?></h5>
                <h3 class="text-primary"><?= e((string) $totalBookings) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-clock text-warning fs-2 mb-2"></i>
                <h5 class="card-title"><?= e(__('upcom')) ?></h5>
                <h3 class="text-warning"><?= e((string) $upcomingCount) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-check-circle text-success fs-2 mb-2"></i>
                <h5 class="card-title"><?= e(__('com')) ?></h5>
                <h3 class="text-success"><?= e((string) $completedCount) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-times-circle text-danger fs-2 mb-2"></i>
                <h5 class="card-title"><?= e(__('can')) ?></h5>
                <h3 class="text-danger"><?= e((string) $cancelledCount) ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">
            <i class="fas fa-filter me-2"></i><?= e(__('filter_bookings')) ?>
        </h5>
        <form method="GET" action="" class="row g-3">
            <div class="col-md-4">
                <label for="status" class="form-label"><?= e(__('Status1')) ?></label>
                <select class="form-select" id="status" name="status">
                    <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>><?= e(__('allsta')) ?></option>
                    <option value="confirmed" <?= $statusFilter === 'confirmed' ? 'selected' : '' ?>><?= e(__('con')) ?></option>
                    <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>><?= e(__('can')) ?></option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="date" class="form-label"><?= e(__('data')) ?></label>
                <select class="form-select" id="date" name="date">
                    <option value="all" <?= $dateFilter === 'all' ? 'selected' : '' ?>><?= e(__('alldate')) ?></option>
                    <option value="today" <?= $dateFilter === 'today' ? 'selected' : '' ?>><?= e(__('today')) ?></option>
                    <option value="upcoming" <?= $dateFilter === 'upcoming' ? 'selected' : '' ?>><?= e(__('upcom')) ?></option>
                    <option value="past" <?= $dateFilter === 'past' ? 'selected' : '' ?>><?= e(__('past')) ?></option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> <?= e(__('apply')) ?>
                </button>
                <a href="<?= e(app_url('my_bookings.php')) ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-undo"></i> <?= e(__('reset')) ?>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Quick Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>
        <i class="fas fa-list me-2"></i><?= e(__('yourbk')) ?>
        <span class="badge bg-primary ms-2"><?= e((string) $totalBookings) ?></span>
    </h4>
    <a href="<?= e(app_url('booking.php')) ?>" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> <?= e(__('new_booking')) ?>
    </a>
</div>

<!-- Bookings List -->
<?php if (empty($bookings)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-times text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3"><?= e(__('no_bookings_found')) ?></h4>
            <p class="text-muted"><?= e(__('nobkfound1')) ?></p>
            <a href="<?= e(app_url('booking.php')) ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> <?= e(__('makebk')) ?>
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($bookings as $booking): ?>
            <?php
            $canCancel = $canCancelBooking((string) $booking['booking_date'], (string) $booking['start_time']);
            $isPast = $booking['booking_date'] < $today;
            $isToday = $booking['booking_date'] === $today;
            $statusClass = ($booking['status'] ?? '') === 'confirmed' ? 'success' : 'danger';
            ?>
            <div class="col-lg-6 mb-4">
                <div class="card h-100 booking-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <img src="<?= e(asset_url((string) $booking['image_path'])) ?>"
                                     alt="<?= e((string) $booking['facility_name']) ?>"
                                     class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h5 class="card-title mb-1"><?= e((string) $booking['facility_name']) ?></h5>
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?= e((string) $booking['location']) ?>
                                    </p>
                                </div>
                            </div>
                            <span class="badge bg-<?= e($statusClass) ?>">
                                <?= e(ucfirst((string) ($booking['status'] ?? ''))) ?>
                            </span>
                        </div>

                        <div class="booking-details">
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-2">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        <strong><?= e(__('date1')) ?></strong><br>
                                        <small><?= e($formatDate((string) $booking['booking_date'])) ?></small>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <strong><?= e(__('time1')) ?></strong><br>
                                        <small>
                                            <?= e($formatTime((string) $booking['start_time'])) ?> -
                                            <?= e($formatTime((string) $booking['end_time'])) ?>
                                        </small>
                                    </p>
                                </div>
                            </div>

                            <p class="mb-3">
                                <i class="fas fa-edit text-primary me-2"></i>
                                <strong><?= e(__('pur')) ?></strong><br>
                                <small class="text-muted"><?= e((string) $booking['purpose']) ?></small>
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <?= e(__('bkid')) ?><?= e((string) $booking['booking_id']) ?>
                                </small>

                                <?php if (($booking['status'] ?? '') === 'confirmed' && $canCancel && !$isPast): ?>
                                    <button class="btn btn-outline-danger btn-sm cancel-booking"
                                            data-booking-id="<?= e((string) $booking['booking_id']) ?>"
                                            data-facility-name="<?= e((string) $booking['facility_name']) ?>"
                                            data-booking-date="<?= e($formatDate((string) $booking['booking_date'])) ?>"
                                            data-booking-time="<?= e($formatTime((string) $booking['start_time'])) ?>">
                                        <i class="fas fa-times me-1"></i> <?= e(__('cancel')) ?>
                                    </button>
                                <?php elseif (($booking['status'] ?? '') === 'confirmed' && !$canCancel && !$isPast): ?>
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <?= e(__('cancelx2')) ?>
                                    </small>
                                <?php elseif ($isPast && ($booking['status'] ?? '') === 'confirmed'): ?>
                                    <small class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        <?= e(__('com')) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($isToday && ($booking['status'] ?? '') === 'confirmed'): ?>
                        <div class="card-footer bg-warning text-dark">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong><?= e(__('tobk')) ?></strong> <?= e(__('dontfor')) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Bookings pagination">
            <ul class="pagination justify-content-center">
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= e((string) ($currentPage - 1)) ?>&status=<?= e($statusFilter) ?>&date=<?= e($dateFilter) ?>">
                            <i class="fas fa-chevron-left"></i> <?= e(__('previous')) ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= e((string) $i) ?>&status=<?= e($statusFilter) ?>&date=<?= e($dateFilter) ?>">
                            <?= e((string) $i) ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= e((string) ($currentPage + 1)) ?>&status=<?= e($statusFilter) ?>&date=<?= e($dateFilter) ?>">
                            <?= e(__('next')) ?> <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    <?= e(__('cancelbk')) ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?= e(__('cancelbk1')) ?></p>
                <div class="cancel-booking-details">
                    <!-- Details will be populated by JavaScript -->
                </div>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong><?= e(__('note')) ?></strong> <?= e(__('action')) ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= e(__('keep_booking')) ?></button>
                <button type="button" class="btn btn-danger" id="confirmCancel">
                    <i class="fas fa-times me-1"></i> <?= e(__('yes_cancel_booking')) ?>
                </button>
            </div>
        </div>
    </div>
</div>
