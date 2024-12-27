@extends('layouts.home', ['title' => 'Appointment Calendar | REDTax Financial Services'])


@section('content')
    @include('landing_page.navigation')
    @include('landing_page.appointment-calendar')
    @include('landing_page.site-footer')  
@endsection

@section('script')
    const calendar = document.getElementById('calendar');
    const monthYearHeading = document.getElementById('current-month-year');
    const today = new Date();

    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear(); 

    document.addEventListener('DOMContentLoaded', () => {
        renderCalendar(currentMonth, currentYear);
    
        // Event listeners for month navigation
        document.getElementById('prev-month').addEventListener('click', function() {
            if (currentMonth === 0) {
                currentMonth = 11;
                currentYear--;
            } else {
                currentMonth--;
            }
            renderCalendar(currentMonth, currentYear);
        });

        document.getElementById('next-month').addEventListener('click', function() {
            if (currentMonth === 11) {
                currentMonth = 0;
                currentYear++;
            } else {
                currentMonth++;
            }
            renderCalendar(currentMonth, currentYear);
        });
    });

    // Function to fetch available dates and render the calendar
    function renderCalendar(month, year) {
        const monthNames = ["January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December"];
        monthYearHeading.innerText = `${monthNames[month]} ${year}`;
    
        // Fetch available weekdays and blocked dates from the server
        axios.get('{{ route("appointment.available-days") }}')
            .then(response => {
                const availableDaysFull = response.data.availableDays;
                const unavailableDates = response.data.unavailableDates.map(date => new Date(date));
    
                const dayMap = {
                    'Sunday': 'Sun',
                    'Monday': 'Mon',
                    'Tuesday': 'Tue',
                    'Wednesday': 'Wed',
                    'Thursday': 'Thu',
                    'Friday': 'Fri',
                    'Saturday': 'Sat'
                };
    
                const availableDays = availableDaysFull.map(day => dayMap[day]);
    
                const startDay = new Date(year, month, 1);
                const endDay = new Date(year, month + 1, 0);
    
                let calendarHTML = '<table class="table table-borderless"><thead><tr>';
                const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                weekdays.forEach(weekday => {
                    calendarHTML += `<th class="text-center">${weekday}</th>`;
                });
                calendarHTML += '</tr></thead><tbody>';
    
                let date = new Date(year, month, 1); 
                let rowHTML = '<tr>';
    
                // Padding for the first week of the month
                for (let i = 0; i < startDay.getDay(); i++) {
                    rowHTML += '<td></td>';
                }
    
                while (date <= endDay) {
                    const weekdayName = weekdays[date.getDay()];
                    const isAvailable = availableDays.includes(weekdayName);
    
                    // Check if date is in the unavailableDates array
                    const isUnavailableDate = unavailableDates.some(d => 
                        date.getDate() === d.getDate() && 
                        date.getMonth() === d.getMonth() &&
                        date.getFullYear() === d.getFullYear()
                    );
    
                    const isToday = date.getDate() === today.getDate() &&
                        date.getMonth() === today.getMonth() &&
                        date.getFullYear() === today.getFullYear();
                    
                    const isPast = date < today && !isToday;
    
                    let className = 'text-center';
    
                    // Mark date unavailable if it's in unavailableDates
                    if (isAvailable && !isPast && !isToday && !isUnavailableDate) {
                        className += ' available text-primary fw-bold';
                    } else {
                        className += ' unavailable-date text-muted';
                    }
    
                    rowHTML += `<td class="${className}">${date.getDate()}</td>`;
    
                    if (date.getDay() === 6) {
                        rowHTML += '</tr><tr>';
                    }
    
                    date.setDate(date.getDate() + 1);
                }
    
                for (let i = endDay.getDay() + 1; i <= 6; i++) {
                    rowHTML += '<td></td>';
                }
    
                rowHTML += '</tr>';
                calendarHTML += rowHTML + '</tbody></table>';
                calendar.innerHTML = calendarHTML;
    
                document.querySelectorAll('.available').forEach(td => {
                    td.style.cursor = 'pointer';
                    td.setAttribute("title", "Click to show available slots");
    
                    td.addEventListener("mouseover", function() {
                        td.style.backgroundColor = "#BBE9FF";
                        td.style.transition = "all ease-in 0.3s";
                    });
    
                    td.addEventListener("mouseout", function() {
                        td.style.backgroundColor = "#ffff";
                    });
    
                    td.addEventListener('click', function() {
                        const selectedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(td.innerText).padStart(2, '0')}`;
                        generateTimeSlots(selectedDate);
                    });
                });
    
                document.querySelectorAll('.unavailable-date').forEach(td => {
                    td.style.cursor = 'not-allowed';
                });
            })
            .catch(error => {
                console.error('Error fetching available days:', error);
            });
    }    
      
    function generateTimeSlots(date){
        document.getElementById('appointment-form').classList.add('d-none');
        document.querySelector('#timeslot-section').classList.remove('d-none');
        const selectedServiceId = document.getElementById('service-id').value;
        
        // Retrieve the CSRF token from the meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        // Set the CSRF token in the Axios headers
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    
        let dateObj = new Date(date);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        let formattedDate = dateObj.toLocaleDateString('en-US', options);
    
        axios.get('{{ route("appointment.timeslots") }}', {
            params: {
                date: date,
                serviceId: selectedServiceId
            }
        })
        .then(response => {
            const timeSlots = response.data.time_slots || [];
            const serviceDuration = response.data.service_duration;
            const breakStartTime = response.data.break_start_time;
            const breakEndTime = response.data.break_end_time;
            const blockStartTime = response.data.block_start_time;
            const blockEndTime = response.data.block_end_time;
    
            // Convert break and block start and end times to Date objects
            const formatTime = (timeStr) => new Date(`1970-01-01T${timeStr}`);
    
            const breakStartTimeObj = breakStartTime ? formatTime(breakStartTime) : null;
            const breakEndTimeObj = breakEndTime ? formatTime(breakEndTime) : null;
            const blockStartTimeObj = blockStartTime ? formatTime(blockStartTime) : null;
            const blockEndTimeObj = blockEndTime ? formatTime(blockEndTime) : null;
    
            const formatTimeToReadable = (timeStr) => {
                const options = { hour: '2-digit', minute: '2-digit' };
                return new Date(`1970-01-01T${timeStr}`).toLocaleTimeString('en-US', options);
            };
    
            let timeSlotsHTML = `<h4 class="text-center mb-3">${formattedDate} - Select timeslot</h4>`;
    
            if (timeSlots.length === 0) {
                timeSlotsHTML += '<h4 class="text-center text-dark-red"><i class="bi bi-exclamation-triangle-fill fs-4"></i> No more available timeslots for this date.</h4>';
            } else {
                timeSlotsHTML += `<div class="timeslot-container row">`;
    
                timeSlots.forEach((slot, index) => {
                    // Convert slot time to Date object
                    const slotTimeObj = formatTime(slot);
    
                    const isDuringBreak = breakStartTimeObj && breakEndTimeObj && slotTimeObj >= breakStartTimeObj && slotTimeObj < breakEndTimeObj;
                    const isDuringBlock = blockStartTimeObj && blockEndTimeObj && slotTimeObj >= blockStartTimeObj && slotTimeObj < blockEndTimeObj;
    
                    // Skip the slot if it falls within the break or block period
                    if (isDuringBreak || isDuringBlock) {
                        return; // Continue to the next slot
                    }

                    const readableSlotTime = formatTimeToReadable(slot);
    
                    timeSlotsHTML += `
                        <div class="timeslot col-lg-3 my-2">
                            <input type="radio" class="btn-check" name="timeslot" value="${slot}" id="timeslot-${index}">
                            <label class="btn btn-outline-primary" for="timeslot-${index}">${readableSlotTime}</label>                            
                        </div>`;
                });
    
                timeSlotsHTML += '</div>';
            }
            const timeSlotSection = document.querySelector('#timeslot-section');
            
            timeSlotSection.innerHTML = timeSlotsHTML;
            
            timeSlotSection.addEventListener('click', function(event) {
                const timeslot = event.target.closest('.timeslot');
                if (timeslot) {
                    const selectedSlot = timeslot.querySelector('input').value;
                    showProceedButton(date, selectedSlot, serviceDuration);
                }
            });
        })
        .catch(error => {
            console.error('Error fetching time slots:', error);
        });
    }  
    
    
    function formatTime(timeStr){
        const [time, period] = timeStr.split(' ');
        let [hours, minutes] = time.split(':').map(Number);
        if (period === 'PM' && hours !== 12) hours += 12;
        if (period === 'AM' && hours === 12) hours = 0;

        return new Date(`1970-01-01T${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:00`);
    };
    
    function formatTimeToReadable(time){
        const date = new Date(`1970-01-01T${time}`);
        let hours = date.getHours();
        const minutes = date.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        return `${hours}:${minutes.toString().padStart(2, '0')} ${ampm}`;
    };
    
    function showProceedButton(date, slot, duration){
        const proceedButton = document.getElementById('proceed-btn');
        proceedButton.classList.remove('d-none');
    
        // Store the selected slot value in the button's dataset
        proceedButton.dataset.selectedDate = date;
        proceedButton.dataset.selectedSlot = slot;
        proceedButton.dataset.serviceDuration = duration;
    }  
    
    // Add event listener to the Proceed button
    document.getElementById('proceed-btn').addEventListener('click', function() {
        document.querySelector('#timeslot-section').classList.add('d-none');
        document.querySelector('#appointment-form').classList.remove('d-none');
        
        const dateInfoText = document.getElementById('selected-date-info');
        const dateInput = document.getElementById('selected-date');
        const startTimeInput = document.getElementById('start-time-input');
        const endTimeInput = document.getElementById('end-time-input');

        // Retrieve the selected timeslot from the Proceed button's dataset
        const selectedDate = this.dataset.selectedDate;
        const selectedSlot = this.dataset.selectedSlot;
        const serviceDuration = this.dataset.serviceDuration;
        
        // Calculate start and end times
        const startTimeObj = formatTime(selectedSlot);
        const endTimeObj = new Date(startTimeObj.getTime() + serviceDuration * 60000); // Add duration to start time

        const startTime = formatTimeToReadable(selectedSlot);
        const endTime = formatTimeToReadable(endTimeObj.toTimeString().split(' ')[0]);
        
        // Set the hidden input fields value
        dateInput.value = selectedDate;
        
        const startDate = parse12HourTime(startTime);
        const endDate = parse12HourTime(endTime);
        
        startTimeInput.value = formatTo24HourTime(startDate);
        endTimeInput.value = formatTo24HourTime(endDate);
 
        // Show the form
        document.getElementById('appointment-form').classList.remove('d-none');
        
        const formattedTimeSlot = formatTimeToReadable(selectedSlot);
        const dayName = new Date(selectedDate).toLocaleDateString('en-US', { weekday: 'long' });
        const formattedDate = new Date(selectedDate).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });

        // Display the schedule information in the form
        const infoText = `${formattedTimeSlot} - ${endTime}, ${dayName}, ${formattedDate}`;
        dateInfoText.textContent = infoText;
        
        // Hide the Proceed button after it's clicked
        this.classList.add('d-none');
    });
    
    function parse12HourTime(timeStr){
        const [time, period] = timeStr.split(' ');
        let [hours, minutes] = time.split(':').map(Number);
    
        if (period === 'PM' && hours !== 12) hours += 12;
        if (period === 'AM' && hours === 12) hours = 0;
    
        // Return a Date object for the given time
        return new Date(1970, 0, 1, hours, minutes);
    };
    
    function formatTo24HourTime(date){
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const seconds = date.getSeconds().toString().padStart(2, '0');
    
        return `${hours}:${minutes}:${seconds}`;
    };
    
@endsection