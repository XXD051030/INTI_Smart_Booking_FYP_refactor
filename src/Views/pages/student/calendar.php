<?php declare(strict_types=1); ?>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<style>
    :root {
        --inti-red: #f61f1f;
        --inti-red-dark: #d41a1a;
        --inti-red-soft: rgba(246, 31, 31, 0.08);
        --inti-red-border: rgba(246, 31, 31, 0.18);
    }

    .calendar-container {
        background: #ffffff;
        border-radius: 14px;
        padding: 0;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef0f4;
        overflow: hidden;
    }

    #calendar {
        background: #ffffff;
        border-radius: 14px;
        overflow: hidden;
        min-height: 600px;
        width: 100%;
    }

    .fc-toolbar.fc-header-toolbar {
        background: #ffffff;
        padding: 20px 24px;
        margin: 0 !important;
        border-bottom: 1px solid #eef0f4;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: auto;
    }

    .fc-toolbar-title {
        color: #1f2937;
        font-weight: 600;
        font-size: 1.35rem;
        margin: 0;
        letter-spacing: 0;
    }

    .fc-button-group {
        background: #f4f5f7;
        border-radius: 8px;
        padding: 3px;
        gap: 2px;
    }

    .fc-button-group .fc-button,
    .fc-toolbar .fc-button {
        background: transparent !important;
        border: none !important;
        color: #4b5563 !important;
        font-weight: 500 !important;
        padding: 6px 14px !important;
        border-radius: 6px !important;
        font-size: 0.875rem !important;
        text-transform: capitalize !important;
        box-shadow: none !important;
        transition: background-color 0.15s ease, color 0.15s ease !important;
        margin: 0 !important;
    }

    .fc-button:hover {
        background: #ffffff !important;
        color: var(--inti-red) !important;
    }

    .fc-button:focus {
        box-shadow: 0 0 0 3px rgba(246, 31, 31, 0.18) !important;
    }

    .fc-button-primary.fc-button-active,
    .fc-button-primary:not(:disabled).fc-button-active {
        background: var(--inti-red) !important;
        color: #ffffff !important;
        box-shadow: 0 1px 2px rgba(246, 31, 31, 0.25) !important;
    }

    .fc-prev-button, .fc-next-button {
        background: #f4f5f7 !important;
        border-radius: 6px !important;
        padding: 6px 10px !important;
    }

    .fc-prev-button:hover, .fc-next-button:hover {
        background: #ffffff !important;
        color: var(--inti-red) !important;
    }

    .fc-today-button {
        background: var(--inti-red) !important;
        color: #ffffff !important;
        border-radius: 6px !important;
        padding: 6px 14px !important;
        font-weight: 600 !important;
        margin-left: 8px !important;
    }

    .fc-today-button:hover {
        background: var(--inti-red-dark) !important;
        color: #ffffff !important;
    }

    .fc-today-button:disabled {
        background: #d1d5db !important;
        color: #ffffff !important;
        opacity: 1 !important;
    }

    .fc-col-header {
        background: #fafbfc;
    }

    .fc-col-header-cell {
        background: transparent;
        height: 44px;
        padding: 0;
        text-align: center;
        vertical-align: middle;
        border: none;
        border-bottom: 1px solid #eef0f4 !important;
    }

    .fc-col-header-cell-cushion {
        color: #6b7280;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.75rem;
        line-height: 44px;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .fc-scrollgrid,
    .fc-scrollgrid-section-header,
    .fc-scrollgrid-section-body,
    .fc-scrollgrid table {
        margin: 0 !important;
        border: none !important;
        padding: 0 !important;
        border-collapse: collapse !important;
    }

    .fc-scrollgrid-section-header + .fc-scrollgrid-section-body {
        border-top: none !important;
    }

    .fc-daygrid-day {
        border: 1px solid #eef0f4 !important;
        transition: background-color 0.15s ease;
    }

    .fc-daygrid-day:hover {
        background: #fafbfc;
    }

    .fc-daygrid-day-number {
        color: #374151;
        font-weight: 500;
        font-size: 0.875rem;
        padding: 8px 10px;
        text-decoration: none;
    }

    .fc-day-other .fc-daygrid-day-number {
        color: #cbd1d8;
    }

    .fc-day-today {
        background: var(--inti-red-soft) !important;
    }

    .fc-day-today .fc-daygrid-day-number {
        color: #ffffff;
        font-weight: 600;
        background: var(--inti-red);
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 6px 8px 0 auto;
        padding: 0;
        font-size: 0.8125rem;
    }

    .fc-event {
        background: var(--inti-red) !important;
        border: none !important;
        border-radius: 6px !important;
        padding: 3px 8px !important;
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #ffffff !important;
        margin: 1px 4px !important;
        box-shadow: none !important;
        transition: background-color 0.15s ease !important;
        cursor: pointer !important;
        overflow: hidden !important;
    }

    .fc-event:hover {
        background: var(--inti-red-dark) !important;
    }

    .fc-event-title {
        font-weight: 500 !important;
    }

    .fc-event-time {
        font-weight: 500 !important;
        opacity: 0.95;
        margin-right: 4px;
    }

    .fc-daygrid-more-link {
        color: var(--inti-red) !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
    }

    .fc-list-event-dot {
        border-color: var(--inti-red) !important;
    }

    .fc-list-day-cushion {
        background: #fafbfc !important;
    }

    .fc-list-event:hover td {
        background: var(--inti-red-soft) !important;
    }

    .calendar-stats {
        background: #ffffff;
        border-radius: 14px;
        padding: 8px 4px;
        margin-bottom: 20px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef0f4;
    }

    .stat-item {
        text-align: center;
        padding: 18px 12px;
        position: relative;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--inti-red);
        display: block;
        line-height: 1.1;
        margin-bottom: 4px;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
        .fc-toolbar.fc-header-toolbar {
            padding: 14px 16px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .fc-toolbar-title {
            font-size: 1.1rem;
        }
        .fc-button-group .fc-button,
        .fc-toolbar .fc-button {
            padding: 5px 10px !important;
            font-size: 0.8rem !important;
        }
    }

    .calendar-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
        font-size: 1rem;
        color: #6b7280;
    }

    .loading-spinner {
        width: 32px;
        height: 32px;
        border: 3px solid #eef0f4;
        border-top: 3px solid var(--inti-red);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-right: 12px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">
        <i class="far fa-calendar me-2" style="color: #f61f1f;"></i>
        <span style="color: #f61f1f; font-weight: 600;">Calendar Overview</span>
    </h3>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm" onclick="goToToday()">
            <i class="fas fa-calendar-day me-1"></i> Today
        </button>
        <button class="btn btn-outline-secondary btn-sm" onclick="refreshCalendar()" title="Refresh Calendar">
            <i class="fas fa-sync-alt me-1"></i> Refresh
        </button>
        <a href="<?= e(app_url('booking.php')) ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> New Booking
        </a>
    </div>
</div>

<!-- Calendar Stats -->
<div class="calendar-stats">
    <div class="row">
        <div class="col-md-3">
            <div class="stat-item">
                <span class="stat-number" id="total-bookings">-</span>
                <span class="stat-label">Total Bookings</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-item">
                <span class="stat-number" id="this-month">-</span>
                <span class="stat-label">This Month</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-item">
                <span class="stat-number" id="upcoming">-</span>
                <span class="stat-label">Upcoming</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-item">
                <span class="stat-number" id="this-week">-</span>
                <span class="stat-label">This Week</span>
            </div>
        </div>
    </div>
</div>

<!-- Calendar Container -->
<div class="calendar-container">
    <div id="calendar-loading" class="calendar-loading">
        <div class="loading-spinner"></div>
        Loading calendar...
    </div>
    <div id='calendar' style="display: none;"></div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
    let calendar;
    let allEvents = [];

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const loadingEl = document.getElementById('calendar-loading');
        setTimeout(() => {
            initializeCalendar(calendarEl, loadingEl);
        }, 50);
    });

    function initializeCalendar(calendarEl, loadingEl) {
        const container = calendarEl.parentElement;
        if (container.offsetWidth === 0 || container.offsetHeight === 0) {
            setTimeout(() => initializeCalendar(calendarEl, loadingEl), 200);
            return;
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,listWeek'
            },
            height: 'auto',
            aspectRatio: 1.35,
            firstDay: 1,
            weekends: true,
            dayMaxEvents: 3,
            moreLinkClick: 'popover',
            eventDisplay: 'block',
            displayEventTime: true,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch('<?= e(app_url('get_bookings.php')) ?>')
                    .then(response => response.json())
                    .then(data => {
                        allEvents = data;
                        updateStats(data);
                        successCallback(data);
                        loadingEl.style.display = 'none';
                        calendarEl.style.display = 'block';
                        setTimeout(() => {
                            if (calendar) {
                                calendar.updateSize();
                                calendar.render();
                                setTimeout(() => updateStats(allEvents), 200);
                            }
                        }, 50);
                    })
                    .catch(error => {
                        console.error('Error fetching events:', error);
                        failureCallback(error);
                        updateStats([]);
                        loadingEl.style.display = 'none';
                        calendarEl.style.display = 'block';
                        setTimeout(() => {
                            if (calendar) {
                                calendar.updateSize();
                                calendar.render();
                            }
                        }, 100);
                    });
            },
            eventClick: function(info) {
                showEventDetails(info.event);
            },
            eventMouseEnter: function(info) {
                info.el.style.transform = 'translateY(-3px) scale(1.05)';
                info.el.style.zIndex = '100';
            },
            eventMouseLeave: function(info) {
                info.el.style.transform = '';
                info.el.style.zIndex = '';
            },
            datesSet: function() {
                if (allEvents && allEvents.length > 0) {
                    updateStats(allEvents);
                }
            }
        });

        calendar.render();

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && calendar) {
                    setTimeout(() => {
                        calendar.updateSize();
                        calendar.render();
                    }, 100);
                    observer.unobserve(calendarEl);
                }
            });
        }, { threshold: 0.1 });

        observer.observe(calendarEl);
    }

    function updateStats(events) {
        const eventsToUse = (events && events.length > 0) ? events : allEvents;
        if (!eventsToUse || eventsToUse.length === 0) {
            animateNumber('total-bookings', 0);
            animateNumber('this-month', 0);
            animateNumber('upcoming', 0);
            animateNumber('this-week', 0);
            return;
        }

        const now = new Date();
        const currentYear = now.getFullYear();
        const currentMonth = now.getMonth();
        const startOfMonth = new Date(currentYear, currentMonth, 1);
        const endOfMonth = new Date(currentYear, currentMonth + 1, 0, 23, 59, 59);
        const startOfWeek = new Date(now);
        const dayOfWeek = now.getDay();
        const daysFromMonday = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
        startOfWeek.setDate(now.getDate() - daysFromMonday);
        startOfWeek.setHours(0, 0, 0, 0);
        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        endOfWeek.setHours(23, 59, 59, 999);

        const totalBookings = eventsToUse.length;
        const thisMonth = eventsToUse.filter(event => {
            const eventDate = new Date(event.start);
            return eventDate >= startOfMonth && eventDate <= endOfMonth;
        }).length;
        const upcoming = eventsToUse.filter(event => new Date(event.start) >= now).length;
        const thisWeek = eventsToUse.filter(event => {
            const eventDate = new Date(event.start);
            return eventDate >= startOfWeek && eventDate <= endOfWeek;
        }).length;

        animateNumber('total-bookings', totalBookings);
        animateNumber('this-month', thisMonth);
        animateNumber('upcoming', upcoming);
        animateNumber('this-week', thisWeek);
    }

    function animateNumber(elementId, targetNumber) {
        const element = document.getElementById(elementId);
        const start = parseInt(element.textContent) || 0;
        const duration = 1000;
        const increment = (targetNumber - start) / (duration / 16);
        let current = start;
        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= targetNumber) || (increment < 0 && current <= targetNumber)) {
                current = targetNumber;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 16);
    }

    function showEventDetails(event) {
        const startTime = new Date(event.start).toLocaleString('en-US', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
        const endTime = new Date(event.end).toLocaleString('en-US', {
            hour: '2-digit', minute: '2-digit'
        });

        const alertHtml = `
            <div class="alert alert-dismissible fade show" role="alert" style="
                background: #ffffff;
                border: 1px solid #eef0f4;
                border-left: 4px solid #f61f1f;
                border-radius: 10px;
                box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
            ">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <i class="fas fa-calendar-alt" style="color: #f61f1f; font-size: 1.4rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-2" style="color: #1f2937; font-weight: 600;">${event.title}</h5>
                        <p class="mb-1" style="color: #4b5563;"><strong>Date:</strong> ${startTime}</p>
                        <p class="mb-1" style="color: #4b5563;"><strong>End Time:</strong> ${endTime}</p>
                        <p class="mb-0" style="color: #4b5563;"><strong>Booking ID:</strong> #${event.id}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        const mainContent = document.querySelector('.col-md-9.col-lg-10.p-4');
        const existingAlert = mainContent.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        mainContent.insertAdjacentHTML('afterbegin', alertHtml);

        setTimeout(() => {
            const alert = mainContent.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }

    function goToToday() {
        if (calendar) {
            calendar.today();
        }
    }

    function refreshCalendar() {
        if (calendar) {
            const loadingEl = document.getElementById('calendar-loading');
            const calendarEl = document.getElementById('calendar');
            loadingEl.style.display = 'flex';
            calendarEl.style.display = 'none';
            setTimeout(() => {
                calendar.updateSize();
                calendar.render();
                calendar.refetchEvents();
                loadingEl.style.display = 'none';
                calendarEl.style.display = 'block';
                setTimeout(() => {
                    if (allEvents && allEvents.length > 0) {
                        updateStats(allEvents);
                    }
                }, 500);
            }, 300);
        }
    }

    window.addEventListener('load', function() {
        const statsElements = document.querySelectorAll('.stat-item');
        statsElements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            setTimeout(() => {
                element.style.transition = 'all 0.6s ease';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 100);
        });

        setTimeout(() => {
            if (calendar) {
                calendar.updateSize();
                calendar.render();
                if (allEvents && allEvents.length > 0) {
                    updateStats(allEvents);
                }
            }
        }, 500);
    });

    window.addEventListener('resize', function() {
        if (calendar) {
            clearTimeout(window.resizeTimeout);
            window.resizeTimeout = setTimeout(() => {
                calendar.updateSize();
            }, 250);
        }
    });

    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && calendar) {
            setTimeout(() => {
                calendar.updateSize();
            }, 100);
        }
    });
</script>
