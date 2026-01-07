/**
 * Advanced Booking Calendar - JavaScript Utilities
 * Handles calendar interactions, date selection, and heatmap updates
 */

class BookingCalendar {
    constructor(options = {}) {
        this.packageId = options.packageId;
        this.currentMonth = options.month || new Date().getMonth() + 1;
        this.currentYear = options.year || new Date().getFullYear();
        this.calendarContainer = document.getElementById(options.containerId || 'calendar');
        this.blockedDates = options.blockedDates || [];
        this.eventDates = options.eventDates || [];
        this.onDateSelect = options.onDateSelect || null;
        this.init();
    }

    init() {
        if (this.calendarContainer) {
            this.render();
            this.setupEventListeners();
        }
    }

    /**
     * Get days in month
     */
    getDaysInMonth(month, year) {
        return new Date(year, month, 0).getDate();
    }

    /**
     * Get first day of month
     */
    getFirstDayOfMonth(month, year) {
        return new Date(year, month - 1, 1).getDay();
    }

    /**
     * Check if date is blocked
     */
    isDateBlocked(dateStr) {
        return this.blockedDates.includes(dateStr);
    }

    /**
     * Check if date has event
     */
    isDateEvent(dateStr) {
        return this.eventDates.includes(dateStr);
    }

    /**
     * Check if date is today
     */
    isToday(day, month, year) {
        const today = new Date();
        return day === today.getDate() && 
               month === today.getMonth() + 1 && 
               year === today.getFullYear();
    }

    /**
     * Check if date is in past
     */
    isDatePast(day, month, year) {
        const checkDate = new Date(year, month - 1, day);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return checkDate < today;
    }

    /**
     * Check if date is weekend
     */
    isWeekend(day, month, year) {
        const date = new Date(year, month - 1, day);
        const dayOfWeek = date.getDay();
        return dayOfWeek === 0 || dayOfWeek === 6;
    }

    /**
     * Format date to YYYY-MM-DD
     */
    formatDate(day, month, year) {
        const d = String(day).padStart(2, '0');
        const m = String(month).padStart(2, '0');
        return `${year}-${m}-${d}`;
    }

    /**
     * Get date from string
     */
    parseDateString(dateStr) {
        const [year, month, day] = dateStr.split('-');
        return {
            year: parseInt(year),
            month: parseInt(month),
            day: parseInt(day),
        };
    }

    /**
     * Render calendar
     */
    render() {
        const daysInMonth = this.getDaysInMonth(this.currentMonth, this.currentYear);
        const firstDay = this.getFirstDayOfMonth(this.currentMonth, this.currentYear);
        
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        let html = `<div class="calendar-month" data-month="${this.currentMonth}" data-year="${this.currentYear}">`;
        html += `<h3 class="calendar-title">${monthNames[this.currentMonth - 1]} ${this.currentYear}</h3>`;
        html += '<div class="calendar-grid">';

        // Day headers
        const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        dayNames.forEach(day => {
            html += `<div class="calendar-day-header">${day}</div>`;
        });

        // Empty cells for days before month starts
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="calendar-empty"></div>';
        }

        // Days of month
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = this.formatDate(day, this.currentMonth, this.currentYear);
            const isBlocked = this.isDateBlocked(dateStr);
            const hasEvent = this.isDateEvent(dateStr);
            const today = this.isToday(day, this.currentMonth, this.currentYear);
            const past = this.isDatePast(day, this.currentMonth, this.currentYear);
            const weekend = this.isWeekend(day, this.currentMonth, this.currentYear);

            let classes = 'calendar-day';
            if (isBlocked) classes += ' blocked';
            if (hasEvent) classes += ' event';
            if (today) classes += ' today';
            if (past) classes += ' past';
            if (weekend) classes += ' weekend';

            html += `<div class="${classes}" data-date="${dateStr}" data-day="${day}">`;
            html += `<span class="day-number">${day}</span>`;
            if (isBlocked) html += '<span class="day-status">Terblokir</span>';
            if (hasEvent) html += '<span class="day-status">Sibuk</span>';
            html += '</div>';
        }

        html += '</div></div>';
        this.calendarContainer.innerHTML = html;
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        const dayElements = document.querySelectorAll('.calendar-day:not(.blocked):not(.past)');
        dayElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.selectDate(element);
            });
        });
    }

    /**
     * Select date
     */
    selectDate(element) {
        const dateStr = element.dataset.date;
        
        // Remove previous selection
        document.querySelectorAll('.calendar-day.selected').forEach(el => {
            el.classList.remove('selected');
        });

        // Add selection
        element.classList.add('selected');

        // Trigger callback
        if (this.onDateSelect) {
            this.onDateSelect(dateStr, element);
        }
    }

    /**
     * Next month
     */
    nextMonth() {
        if (this.currentMonth === 12) {
            this.currentMonth = 1;
            this.currentYear++;
        } else {
            this.currentMonth++;
        }
        this.render();
        this.setupEventListeners();
    }

    /**
     * Previous month
     */
    prevMonth() {
        if (this.currentMonth === 1) {
            this.currentMonth = 12;
            this.currentYear--;
        } else {
            this.currentMonth--;
        }
        this.render();
        this.setupEventListeners();
    }

    /**
     * Update blocked dates
     */
    updateBlockedDates(dates) {
        this.blockedDates = dates;
        this.render();
        this.setupEventListeners();
    }

    /**
     * Update event dates
     */
    updateEventDates(dates) {
        this.eventDates = dates;
        this.render();
        this.setupEventListeners();
    }

    /**
     * Refresh calendar data from server
     */
    async refreshFromServer() {
        if (!this.packageId) return;

        try {
            const response = await fetch(
                `/customer/calendar/booking/${this.packageId}/data?month=${this.currentMonth}&year=${this.currentYear}`
            );
            const data = await response.json();

            if (data.success) {
                this.updateBlockedDates(data.blockedDates || []);
                this.updateEventDates(data.eventDates || []);
            }
        } catch (error) {
            console.error('Error refreshing calendar:', error);
        }
    }
}

/**
 * Calendar Heatmap - Visualize availability density
 */
class CalendarHeatmap {
    constructor(options = {}) {
        this.containerId = options.containerId || 'heatmap';
        this.data = options.data || {};
        this.month = options.month;
        this.year = options.year;
        this.init();
    }

    init() {
        const container = document.getElementById(this.containerId);
        if (container) {
            this.render(container);
        }
    }

    /**
     * Get color for status
     * 0 = available (green)
     * 1 = blocked (red)
     * 2 = busy (yellow)
     */
    getColor(status) {
        return {
            0: '#10b981', // Green
            1: '#ef4444', // Red
            2: '#f59e0b', // Amber
        }[status] || '#d1d5db';
    }

    /**
     * Render heatmap
     */
    render(container) {
        const entries = Object.entries(this.data);
        
        if (entries.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500">Tidak ada data</p>';
            return;
        }

        let html = '<div class="heatmap-container">';
        
        entries.forEach(([date, status]) => {
            const color = this.getColor(status);
            const label = {0: 'Tersedia', 1: 'Terblokir', 2: 'Sibuk'}[status] || 'N/A';
            
            html += `<div class="heatmap-cell" style="background-color: ${color};" title="${date}: ${label}"></div>`;
        });

        html += '</div>';
        container.innerHTML = html;
    }
}

/**
 * Export Calendar
 */
function exportCalendarToiCal(packageId, type = 'all') {
    const url = `/owner/calendar/${packageId}/export?type=${type}`;
    window.location.href = url;
}

/**
 * Initialize all calendar components on page load
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize booking calendar if exists
    const bookingCalendarContainer = document.getElementById('booking-calendar');
    if (bookingCalendarContainer) {
        window.bookingCalendar = new BookingCalendar({
            containerId: 'booking-calendar',
            packageId: window.packageId,
            month: window.currentMonth || new Date().getMonth() + 1,
            year: window.currentYear || new Date().getFullYear(),
            blockedDates: window.blockedDates || [],
            eventDates: window.eventDates || [],
            onDateSelect: function(dateStr) {
                // Update hidden date input if exists
                const dateInput = document.getElementById('event_date');
                if (dateInput) {
                    dateInput.value = dateStr;
                }
            }
        });
    }

    // Initialize heatmap if exists
    const heatmapContainer = document.getElementById('calendar-heatmap');
    if (heatmapContainer && window.heatmapData) {
        window.calendarHeatmap = new CalendarHeatmap({
            containerId: 'calendar-heatmap',
            data: window.heatmapData,
        });
    }
});

// Export for use in other scripts
if (typeof window !== 'undefined') {
    window.BookingCalendar = BookingCalendar;
    window.CalendarHeatmap = CalendarHeatmap;
    window.exportCalendarToiCal = exportCalendarToiCal;
}
