<?php declare(strict_types=1); ?>
<!-- Booking Alert -->
<div id="booking-alert" class="alert alert-info d-none">
    <i class="fas fa-info-circle me-2"></i>
    <span id="alert-message"></span>
</div>

<div class="row">
    <!-- Left Panel: Facility Selection -->
    <div class="col-lg-4">
        <div class="facility-section">
            <h4 class="section-title">
                <i class="fas fa-building me-2"></i>Select Facility
            </h4>

            <!-- Facility Type Filter -->
            <div class="facility-filter mb-4">
                <div class="btn-group w-100" role="group">
                    <button type="button" class="btn btn-outline-primary active" data-filter="all">
                        All
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filter="discussion_room">
                        Discussion Room
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filter="basketball_court">
                        Sport
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filter="stem_lab">
                        STEM
                    </button>
                </div>
            </div>

            <!-- Facility Cards -->
            <div class="facility-list">
                <?php foreach ($facilities as $facility): ?>
                <div class="facility-card"
                     data-facility-id="<?= e((string) $facility['id']) ?>"
                     data-facility-type="<?= e((string) $facility['type']) ?>"
                     data-advance-days="<?= e((string) $facility['advance_booking_days']) ?>">
                    <div class="facility-image">
                        <img src="<?= e(asset_url($facility['image_path'])) ?>"
                             alt="<?= e((string) $facility['name']) ?>">
                    </div>
                    <div class="facility-info">
                        <h5><?= e((string) $facility['name']) ?></h5>
                        <p class="facility-details">
                            <i class="fas fa-users me-1"></i> Capacity: <?= e((string) $facility['capacity']) ?>
                        </p>
                        <p class="facility-details">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= e((string) $facility['location']) ?>
                        </p>
                        <p class="facility-booking-rule">
                            <i class="fas fa-clock me-1"></i>
                            <?php if ((int) $facility['advance_booking_days'] === 0): ?>
                                Same-day booking only
                            <?php else: ?>
                                Book up to <?= e((string) $facility['advance_booking_days']) ?> day(s) in advance
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Right Panel: Booking Interface -->
    <div class="col-lg-8">
        <div class="booking-section">
            <!-- Selected Facility Display -->
            <div id="selected-facility" class="selected-facility-info d-none">
                <h4 class="section-title">
                    <i class="fas fa-calendar-check me-2"></i>Book <span id="selected-facility-name"></span>
                </h4>
                <div class="facility-summary">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Capacity:</strong> <span id="selected-facility-capacity"></span> people</p>
                            <p><strong>Location:</strong> <span id="selected-facility-location"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Hours:</strong> 08:00 - 17:00</p>
                            <p><strong>Booking rule:</strong> <span id="selected-facility-rule"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Selection -->
            <div id="date-selection" class="date-section d-none">
                <h5>Select Date</h5>
                <div class="date-input-group">
                    <input type="date" id="booking-date" class="form-control" min="">
                    <small class="form-text text-muted">
                        You can book up to <span id="max-days-text">0</span> day(s) in advance.
                    </small>
                    <div class="alert alert-info mt-2 d-none" id="date-help-alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Please choose a date within the allowed booking range.
                    </div>
                </div>
            </div>

            <!-- Time Slot Selection -->
            <div id="time-selection" class="time-section d-none">
                <h5>Select Time</h5>
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Multiple bookings:</strong> You can select up to 2 consecutive time slots.
                    Click on available slots to select them.
                </div>
                <div class="time-grid" id="time-grid">
                    <!-- Time slots will be generated by JavaScript -->
                </div>
                <div class="time-legend mt-3">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="legend-item">
                            <span class="legend-color available"></span>
                            <small>Available</small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color booked"></span>
                            <small>Booked</small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color selected"></span>
                            <small>Selected</small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color disabled"></span>
                            <small>Unavailable</small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #f8f9fa; border-color: #dee2e6; opacity: 0.6;"></span>
                            <small>Non-consecutive</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div id="booking-form-section" class="booking-form d-none">
                <h5>Booking Details</h5>
                <form id="booking-form">
                    <div class="mb-3">
                        <label for="booking-purpose" class="form-label">Purpose</label>
                        <textarea class="form-control" id="booking-purpose" rows="3"
                                placeholder="Please describe the purpose of your booking (minimum 10 characters)"
                                maxlength="500" required></textarea>
                        <div class="form-text">
                            <span id="char-count">0</span>/500
                        </div>
                    </div>

                    <!-- Booking Summary -->
                    <div class="booking-summary mb-4">
                        <h6>Booking Summary</h6>
                        <div class="summary-content">
                            <p><strong>Facility:</strong> <span id="summary-facility">-</span></p>
                            <p><strong>Date:</strong> <span id="summary-date">-</span></p>
                            <p><strong>Time:</strong> <span id="summary-time">-</span></p>
                            <p><strong>Duration:</strong> <span id="summary-duration">1 hour</span></p>
                        </div>
                    </div>

                    <!-- Daily Booking Limit Notice -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Daily limit:</strong> You can make up to 2 booking requests per day.
                        <span id="daily-booking-count"></span>
                    </div>

                    <div class="booking-actions">
                        <button type="button" class="btn btn-secondary me-2" id="reset-booking">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary" id="confirm-booking">
                            <i class="fas fa-check me-1"></i> Confirm Booking
                        </button>
                    </div>
                </form>
            </div>

            <!-- Initial State -->
            <div id="initial-state" class="text-center py-5">
                <i class="fas fa-hand-pointer text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">Select a facility to begin</h4>
                <p class="text-muted">Pick a facility from the left to choose a date and time slot.</p>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-3">Processing booking...</div>
            </div>
        </div>
    </div>
</div>
