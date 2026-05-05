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
                <i class="fas fa-building me-2"></i><?= e(__('select')) ?>
            </h4>

            <!-- Facility Type Filter -->
            <div class="facility-filter mb-4">
                <div class="btn-group w-100" role="group">
                    <button type="button" class="btn btn-outline-primary active" data-filter="all">
                        <?= e(__('all')) ?>
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filter="discussion_room">
                        <?= e(__('dis')) ?>
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filter="basketball_court">
                        <?= e(__('s')) ?>
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filter="stem_lab">
                        <?= e(__('stem1')) ?>
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
                            <i class="fas fa-users me-1"></i> <?= e(__('capa')) ?> <?= e((string) $facility['capacity']) ?>
                        </p>
                        <p class="facility-details">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= e((string) $facility['location']) ?>
                        </p>
                        <p class="facility-booking-rule">
                            <i class="fas fa-clock me-1"></i>
                            <?php if ((int) $facility['advance_booking_days'] === 0): ?>
                                <?= e(__('bookday1')) ?>
                            <?php else: ?>
                                <?= e(__('bk')) ?> <?= e((string) $facility['advance_booking_days']) ?> <?= e(__('bk1')) ?>
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
                    <i class="fas fa-calendar-check me-2"></i><?= e(__('book')) ?> <span id="selected-facility-name"></span>
                </h4>
                <div class="facility-summary">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><?= e(__('capa')) ?></strong> <span id="selected-facility-capacity"></span> <?= e(__('ppl')) ?></p>
                            <p><strong><?= e(__('locat')) ?></strong> <span id="selected-facility-location"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><?= e(__('Hours')) ?></strong> 08:00 - 17:00</p>
                            <p><strong><?= e(__('bookingrule')) ?></strong> <span id="selected-facility-rule"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Selection -->
            <div id="date-selection" class="date-section d-none">
                <h5><?= e(__('selectdate')) ?></h5>
                <div class="date-input-group">
                    <input type="date" id="booking-date" class="form-control" min="">
                    <small class="form-text text-muted">
                        <?= e(__('bd1')) ?> <span id="max-days-text">0</span> <?= e(__('bd1.1')) ?>.
                    </small>
                    <div class="alert alert-info mt-2 d-none" id="date-help-alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong><?= e(__('note')) ?></strong> <?= e(__('notesub')) ?>
                    </div>
                </div>
            </div>

            <!-- Time Slot Selection -->
            <div id="time-selection" class="time-section d-none">
                <h5><?= e(__('selecttime')) ?></h5>
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong><?= e(__('mulbk')) ?></strong> <?= e(__('timeslotselect')) ?>
                    <?= e(__('timeslotselect1')) ?>
                </div>
                <div class="time-grid" id="time-grid">
                    <!-- Time slots will be generated by JavaScript -->
                </div>
                <div class="time-legend mt-3">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="legend-item">
                            <span class="legend-color available"></span>
                            <small><?= e(__('ava')) ?></small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color booked"></span>
                            <small><?= e(__('booked')) ?></small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color selected"></span>
                            <small><?= e(__('selected')) ?></small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color disabled"></span>
                            <small><?= e(__('unava')) ?></small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #f8f9fa; border-color: #dee2e6; opacity: 0.6;"></span>
                            <small><?= e(__('noncon')) ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div id="booking-form-section" class="booking-form d-none">
                <h5><?= e(__('bkdetails')) ?></h5>
                <form id="booking-form">
                    <div class="mb-3">
                        <label for="booking-purpose" class="form-label"><?= e(__('purpose')) ?></label>
                        <textarea class="form-control" id="booking-purpose" rows="3"
                                placeholder="Please describe the purpose of your booking (minimum 10 characters)"
                                maxlength="500" required></textarea>
                        <div class="form-text">
                            <span id="char-count">0</span>/<?= e(__('500')) ?>
                        </div>
                    </div>

                    <!-- Booking Summary -->
                    <div class="booking-summary mb-4">
                        <h6><?= e(__('bksum')) ?></h6>
                        <div class="summary-content">
                            <p><strong><?= e(__('fa')) ?></strong> <span id="summary-facility">-</span></p>
                            <p><strong><?= e(__('date')) ?></strong> <span id="summary-date">-</span></p>
                            <p><strong><?= e(__('time')) ?></strong> <span id="summary-time">-</span></p>
                            <p><strong><?= e(__('dura')) ?></strong> <span id="summary-duration"><?= e(__('1hour')) ?></span></p>
                        </div>
                    </div>

                    <!-- Daily Booking Limit Notice -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong><?= e(__('daylimit')) ?></strong> <?= e(__('daylimit1')) ?>
                        <span id="daily-booking-count"></span>
                    </div>

                    <div class="booking-actions">
                        <button type="button" class="btn btn-secondary me-2" id="reset-booking">
                            <i class="fas fa-undo me-1"></i> <?= e(__('reset')) ?>
                        </button>
                        <button type="submit" class="btn btn-primary" id="confirm-booking">
                            <i class="fas fa-check me-1"></i> <?= e(__('confirmbk')) ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Initial State -->
            <div id="initial-state" class="text-center py-5">
                <i class="fas fa-hand-pointer text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3"><?= e(__('selectbk')) ?></h4>
                <p class="text-muted"><?= e(__('selectbk1')) ?></p>
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
                    <span class="visually-hidden"><?= e(__('load')) ?></span>
                </div>
                <div class="mt-3"><?= e(__('processbk')) ?></div>
            </div>
        </div>
    </div>
</div>
