<?php declare(strict_types=1); ?>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<style>
    /* Modern Calendar Container */
    .calendar-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .calendar-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        pointer-events: none;
    }

    #calendar {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        position: relative;
        z-index: 1;
        min-height: 600px;
        width: 100%;
    }

    .fc-toolbar.fc-header-toolbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 25px;
        margin: 0;
        border-radius: 16px 16px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: auto;
        border: none;
    }

    .fc-toolbar-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.8rem;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        letter-spacing: 0.5px;
    }

    .fc-button-group {
        background: rgba(255,255,255,0.2);
        border-radius: 25px;
        padding: 5px;
        backdrop-filter: blur(10px);
    }

    .fc-button {
        background: transparent !important;
        border: none !important;
        color: #fff !important;
        font-weight: 600 !important;
        padding: 8px 15px !important;
        border-radius: 20px !important;
        transition: all 0.3s ease !important;
        margin: 0 2px !important;
    }

    .fc-button:hover {
        background: rgba(255,255,255,0.3) !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
    }

    .fc-button:focus {
        box-shadow: 0 0 0 3px rgba(255,255,255,0.3) !important;
    }

    .fc-col-header {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8eeff 100%);
    }

    .fc-col-header-cell {
        background: transparent;
        height: 60px;
        padding: 0;
        text-align: center;
        vertical-align: middle;
        border: none;
        position: relative;
    }

    .fc-col-header-cell::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 20%;
        right: 20%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #667eea, transparent);
    }

    .fc-col-header-cell-cushion {
        color: #4a5568;
        text-decoration: none;
        font-weight: 700;
        font-size: 1rem;
        line-height: 60px;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
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
        border: 1px solid rgba(0,0,0,0.05) !important;
        transition: all 0.3s ease;
        position: relative;
    }

    .fc-daygrid-day:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        transform: scale(1.02);
        z-index: 2;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .fc-daygrid-day-number {
        color: #4a5568;
        font-weight: 600;
        font-size: 1rem;
        padding: 10px;
        text-decoration: none;
    }

    .fc-day-today {
        background: linear-gradient(135deg, rgba(246, 31, 31, 0.1) 0%, rgba(255, 107, 107, 0.1) 100%) !important;
        border: 2px solid #f61f1f !important;
    }

    .fc-day-today .fc-daygrid-day-number {
        color: #f61f1f;
        font-weight: 700;
        background: rgba(246, 31, 31, 0.1);
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 5px auto;
    }

    .fc-event {
        background: linear-gradient(135deg, #f61f1f 0%, #ff6b6b 100%) !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 6px 12px !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        color: #fff !important;
        margin: 2px 4px !important;
        box-shadow: 0 4px 15px rgba(246, 31, 31, 0.3) !important;
        transition: all 0.3s ease !important;
        cursor: pointer !important;
        position: relative !important;
        overflow: hidden !important;
    }

    .fc-event::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }

    .fc-event:hover {
        transform: translateY(-3px) scale(1.05) !important;
        box-shadow: 0 8px 25px rgba(246, 31, 31, 0.4) !important;
        z-index: 10 !important;
    }

    .fc-event:hover::before {
        left: 100%;
    }

    .fc-event-title {
        font-weight: 600 !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    .fc-event-time {
        font-weight: 500 !important;
        opacity: 0.9;
    }

    .calendar-stats {
        background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .stat-item {
        text-align: center;
        padding: 15px;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        display: block;
    }

    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
        .calendar-container {
            padding: 15px;
            margin: 10px;
        }
        .fc-toolbar-title {
            font-size: 1.4rem;
        }
        .fc-button {
            padding: 6px 10px !important;
            font-size: 0.8rem !important;
        }
    }

    .calendar-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
        font-size: 1.2rem;
        color: #667eea;
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 15px;
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
            <div class="alert alert-info alert-dismissible fade show" role="alert" style="
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                border: 1px solid rgba(102, 126, 234, 0.3);
                border-radius: 12px;
                backdrop-filter: blur(10px);
            ">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <i class="fas fa-calendar-alt" style="color: #667eea; font-size: 1.5rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-2" style="color: #4a5568;">${event.title}</h5>
                        <p class="mb-1"><strong>Date:</strong> ${startTime}</p>
                        <p class="mb-1"><strong>End Time:</strong> ${endTime}</p>
                        <p class="mb-0"><strong>Booking ID:</strong> #${event.id}</p>
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
