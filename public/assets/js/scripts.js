function showPassword(){
	let inputPassword = document.querySelector("#input-password");
	if (inputPassword.type === "password") {
		inputPassword.type = "text";
	} else {
		inputPassword.type = "password";
	}
}

function generateRandomString(limit){
	const characters = "0123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ";
	let result = "";
	const charactersLength = characters.length;
	for (let i = 0; i < limit; i++) {
		result += characters.charAt(Math.floor(Math.random() * charactersLength));
	}
	return result;
}

function generatePassword(){
	const password = document.querySelector("#input-password");
	const current_year = new Date().getFullYear();
	password.value = generateRandomString(12);
}


function toggleCompanyField(){
    const selectCustomerType = document.querySelector('#customer-type');
    const companyField = document.querySelector('#company');

    if (selectCustomerType.value === 'Business') {
        companyField.removeAttribute('disabled');
    } else {
        companyField.value = "";
        companyField.setAttribute('disabled', 'disabled');
    }
}

// Code for task search name autocompletion
let debounceTimeout;

function searchforTask() {
    const input = document.querySelector('#assign-to');
    const resultsContainer = document.querySelector('#search-results');
    let query = input.value;

    if (!resultsContainer) {
        return;
    }

    if (debounceTimeout) {
        clearTimeout(debounceTimeout);
    }

    debounceTimeout = setTimeout(() => {
        if (query.length >= 1) {
            loadData(query);
        } else {
            resultsContainer.classList.add('d-none');
            resultsContainer.innerHTML = '';
        }
    }, 300); // Adjust debounce delay as needed (e.g., 300ms)
}

function loadData(query) {
    const input = document.querySelector('#assign-to');
    const id = document.querySelector('#assign-to-id');
    const resultsContainer = document.querySelector('#search-results');

    axios.get('/search', {
        params: { query: query }
    })
    .then(function (response) {
        const results = response.data;

        // Clear previous results
        resultsContainer.innerHTML = '';

        if (results.length > 0) {
            resultsContainer.classList.remove('d-none');

            results.forEach(function (result) {
                const div = document.createElement('div');
                div.textContent = `${result.name} **Role: ${result.role.role_name}**`;
                div.style.cursor = 'pointer';
                div.classList.add('border', 'px-3', 'py-2', 'fw-medium');

                div.addEventListener('click', function () {
                    id.value = result.id;
                    input.value = result.name;
                    resultsContainer.classList.add('d-none');
                });

                resultsContainer.appendChild(div);
            });
        } else {
            resultsContainer.classList.remove('d-none');
            const div = document.createElement('div');
            div.textContent = "No records found...";
            div.classList.add('border', 'px-3', 'py-2', 'fw-medium');
            resultsContainer.appendChild(div);
        }
    })
    .catch(function (error) {
        console.error('Error fetching data:', error);
    });
}

async function handlePreview(event, imageUrl, documentId) {
    event.preventDefault(); // Prevent default anchor navigation

    try {
        // Update the status
        await updateViewedStatus(documentId);
        
        // Create a temporary link element to view file in new tab
        const link = document.createElement('a');
        link.href = imageUrl;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Reload the page to refresh the table
        setTimeout(() => { window.location.reload(); }, 100);
    } catch (error) {
        console.error('Error:', error);
    }
}

async function updateViewedStatus(documentId) {
    try {
        const response = await axios.put(`/documents/${documentId}/update-viewed-status`, {
            viewed: true
        }, {
            withCredentials: true,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        console.log('Status updated:', response.data);
    } catch (error) {
        console.error('Error updating status:', error);
        throw error;
    }
}

async function handleDownload(event, documentId) {
    event.preventDefault(); // Prevent default anchor behavior

    try {
        // Update the document's status
        await updateDownloadedStatus(documentId);

       // Create a hidden form to trigger the file download
       const form = document.createElement('form');
       form.method = 'GET';
       form.action = `/documents/${documentId}/download`;
       form.style.display = 'none';

       // Append the form to the body and submit it
       document.body.appendChild(form);
       form.submit();

        // Reload the page to refresh the table
        setTimeout(() => {
            window.location.reload();
        }, 600);
    } catch (error) {
        console.error('Error:', error);
    }
}

async function updateDownloadedStatus(documentId) {
    try {
        await axios.put(`/documents/${documentId}/update-downloaded-status`, {
            downloaded: true
        }, {
            withCredentials: true,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        console.log('Download status updated');
    } catch (error) {
        console.error('Error updating status:', error);
        throw error;
    }
}

// For Appointment Schedules
function checkAllDays(allDaysCheckbox){
    const dayCheckboxes = document.querySelectorAll('.day-checkbox');
    
    // Set each day's checkbox to be checked or unchecked based on the All Days checkbox
    dayCheckboxes.forEach(checkbox => {
        checkbox.checked = allDaysCheckbox.checked;
    });
}

function updateAllDaysCheckbox(){
    const allDaysCheckbox = document.getElementById('allDaysCheckbox');
    const dayCheckboxes = document.querySelectorAll('.day-checkbox');
    const allChecked = Array.from(dayCheckboxes).every(checkbox => checkbox.checked);

    // If any of the day checkboxes are unchecked, uncheck "All Days" checkbox
    allDaysCheckbox.checked = allChecked;
}

function resetTimeInputs(){
    document.getElementById('break-start-time').value = '';
    document.getElementById('break-end-time').value = ''; 
}


// For Appointment Scheduling
function fetchTimeSlots(){
    const rescheduleDate = document.getElementById('reschedule-date').value;
    const selectedServiceId = document.getElementById('service-id').value;
    const timeSlotSelect = document.getElementById('timeslot');
    
    const today = new Date();
    // today.setHours(0, 0, 0, 0); // Reset time to start of the day
    const selectedDateObj = new Date(rescheduleDate);

    // Check if the selected date is today or earlier
    if (selectedDateObj <= today) {
        timeSlotSelect.innerHTML = '';
        alert('Please select a valid date, it should be later than today.');
        return;
    }
    
    if (selectedServiceId == '') {
        timeSlotSelect.innerHTML = '';
        return;
    } 
    
    timeSlotSelect.removeAttribute('disabled');

    axios.post('/appointments/reschedule/timeslots', {
        date: rescheduleDate,
        serviceId: selectedServiceId
    })
    .then(function(response) {
        // Clear old options
        timeSlotSelect.innerHTML = '';
        
        // Populate the select box with the new timeslot options
        response.data.timeSlots.forEach(function(slot) {
            const option = document.createElement('option');
            option.value = slot.value;  // Start time in H:i:s format
            option.textContent = slot.label;  // Formatted time (e.g., 9:00 AM - 10:00 AM)
            timeSlotSelect.appendChild(option);
        });
    })
    .catch(function(error) {
        console.error('Error fetching timeslots:', error);
    });
}

function viewNoteModal(button) {
    // Get the data attributes from the button
    const noteId = button.getAttribute('data-note-id');
    const noteContent = button.getAttribute('data-note');
    const recipient = button.getAttribute('data-recipient');

    // Populate the modal with the note data
    document.getElementById('view-note').value = noteContent;
    document.getElementById('view-message-to').value = recipient;

    // Show the modal
    const viewModal = new bootstrap.Modal(document.getElementById('viewNoteModal'));
    viewModal.show();
}

function editNoteModal(button) {
    // Get the data attributes from the button
    const noteId = button.getAttribute('data-note-id');
    const noteContent = button.getAttribute('data-note');
    const recipientId = button.getAttribute('data-recipient');

    // Set the form action dynamically
    const form = document.getElementById('editNoteForm');
    form.action = `/notes/${noteId}/update`;

    // Set the hidden input for the note ID (if needed)
    document.getElementById('note-id').value = noteId;

    // Populate the modal with the note data
    document.getElementById('edit-note').value = noteContent;
    document.getElementById('edit-message-to').value = recipientId;

    // Show the modal
    const editModal = new bootstrap.Modal(document.getElementById('editNoteModal'));
    editModal.show();
}

// For Client Appointment Booking
function fetchAvailableTimeSlots(){
    const scheduleDate = document.getElementById('schedule-date').value;
    const selectedServiceId = document.getElementById('service-id').value;
    const timeSlotSelect = document.getElementById('timeslot');
    
    const selectedDateObj = new Date(scheduleDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Check if the selected date is today or earlier
    if (selectedDateObj <= today) {
        timeSlotSelect.innerHTML = '';
        return;
    }
    
    if (selectedServiceId == '') {
        timeSlotSelect.innerHTML = '';
        return;
    } 

    timeSlotSelect.removeAttribute('disabled');
    
    axios.post('/appointments/schedule/timeslots', {
        date: scheduleDate,
        serviceId: selectedServiceId
    })
    .then(function(response) {
        // Clear old options
        timeSlotSelect.innerHTML = '';
        
        // Populate the select box with the new timeslot options
        response.data.timeSlots.forEach(function(slot) {
            const option = document.createElement('option');
            option.value = slot.value;  // Start time in H:i:s format
            option.textContent = slot.label;  // Formatted time (e.g., 9:00 AM - 10:00 AM)
            timeSlotSelect.appendChild(option);
        });
    })
    .catch(function(error) {
        console.error('Error fetching timeslots:', error);
    });
}


// function populateNoteModal(button, action) {
//     // Get the data attributes from the button
//     const noteId = button.getAttribute('data-note-id');
//     const noteContent = button.getAttribute('data-note');
//     const recipientId = button.getAttribute('data-recipient');

//     // Set the form action dynamically if editing
//     const form = document.getElementById('editNoteForm');
    
//     if (action === 'edit') {
//         form.action = `/notes/${noteId}/update`;
//         // Enable input fields for editing
//         document.getElementById('editNoteLabel').textContent = 'Edit Note';
//         document.getElementById('edit-note').removeAttribute('disabled');
//         document.getElementById('edit-message-to').removeAttribute('disabled');
//         document.getElementById('update-button').style.display = 'block';
//     } else {
//         // Disable form submission if viewing
//         form.action = '';
//         // Make input fields read-only for viewing
//         document.getElementById('editNoteLabel').textContent = 'View Note';
//         document.getElementById('edit-note').setAttribute('disabled', true);
//         document.getElementById('edit-message-to').setAttribute('disabled', true);
//         document.getElementById('update-button').style.display = 'none';
//     }

//     // Set the hidden input for the note ID (if needed)
//     document.getElementById('note-id').value = noteId;

//     // Populate the modal with the note data
//     document.getElementById('edit-note').value = noteContent;
//     document.getElementById('edit-message-to').value = recipientId;

//     // Show the modal
//     const editModal = new bootstrap.Modal(document.getElementById('editNoteModal'));
//     editModal.show();
// }


