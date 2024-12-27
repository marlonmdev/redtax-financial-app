function storeAppointment(event){
    event.preventDefault();
    // Show the loader
    const loader = document.getElementById('loader');
    loader.style.opacity = '1';

    const form = document.getElementById('appointment-form');
    const serviceId = document.getElementById('service-id').value;

    // Collect form data
    const formData = new FormData(form);

    axios.post('/appointment', formData)
        .then(function(response) {
            clearErrors();
            if (response.data.success) {
                window.location.href = `/appointment/${serviceId}/calendar`;
            }
        })
        .catch(function(error) {            
            if (error.response && error.response.status === 422) {
                displayErrors(error.response.data.errors);
                repopulateFields(error.response.data.old);
            } else {
                window.location.href = `/appointment/${serviceId}/calendar`;
            }
        })
        .finally(function() {
            loader.style.opacity = '0';
        });
}

function displayErrors(errors){
    clearErrors();
    for (let field in errors) {
        let inputElement = document.querySelector(`[name="${field}"]`);
        if (inputElement) {
            inputElement.classList.add('is-invalid');
            
            let errorElement = document.createElement('div');
            errorElement.classList.add('invalid-feedback');
            errorElement.textContent = errors[field][0]; // Display the first error message
            
            inputElement.parentNode.appendChild(errorElement);
        }
    }
}

function repopulateFields(oldData){
    for (let field in oldData) {
        let inputElement = document.querySelector(`[name="${field}"]`);
        if (inputElement) {
            if (inputElement.type === 'file') {
                // For file inputs, you can't set the value, but you can display the filename
                let fileNameDisplay = document.createElement('div');
                fileNameDisplay.textContent = `Previously selected: ${oldData[field]}`;
                inputElement.parentNode.appendChild(fileNameDisplay);
            } else {
                inputElement.value = oldData[field];
            }
        }
    }
}

function clearErrors(){
    document.querySelectorAll('.is-invalid').forEach(element => {
        element.classList.remove('is-invalid');
    });

    document.querySelectorAll('.invalid-feedback').forEach(element => {
        element.remove();
    });
}


