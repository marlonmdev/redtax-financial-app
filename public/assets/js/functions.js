const uploadForm = document.getElementById('uploadForm');
uploadForm.addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData();
    let documentFile = document.getElementById('document').files[0];
    let btnSubmit = document.getElementById("btnSubmit");
    let btnSubmitText = document.getElementById("btnSubmitText");
    let btnSubmitSpinner = document.getElementById("btnSubmitSpinner");
    
    const formFields = ['name', 'segment', 'company', 'email', 'phone', 'preferred_contact', 'address', 'city', 'services', 'quotation'];
    formFields.forEach(field => {
        formData.append(field, document.getElementById(field).value);
    });
    
    formData.append('document', documentFile);
    btnSubmit.disabled = true;
    btnSubmitText.innerHTML = "SUBMITTING";
    btnSubmitSpinner.classList.remove("d-none");
    
    axios.post('/document-upload', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(function (response) {
        showSuccessToast(response.data.message);
        uploadForm.reset();
        clearErrors();
        resetButton();
    })
    .catch(function (error) {
        if (error.response && error.response.status === 422) {
            displayErrors(error.response.data.errors);
            repopulateFields(error.response.data.old);
            resetButton();
        } else {
            showErrorToast(`An error occurred: ${error}`);
            resetButton();
        }
    });
});

const displayErrors = (errors) => {
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

const repopulateFields = (oldData) => {
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

const clearErrors = () => {
    document.querySelectorAll('.is-invalid').forEach(element => {
        element.classList.remove('is-invalid');
    });

    document.querySelectorAll('.invalid-feedback').forEach(element => {
        element.remove();
    });
}

const resetButton = () => {
    document.getElementById("btnSubmit").disabled = false;
    document.getElementById("btnSubmitText").innerHTML = "SUBMIT NOW";
    document.getElementById("btnSubmitSpinner").classList.add("d-none");
}

const showSuccessToast = (message) => {
    const toastElement = document.getElementById('successToast');
    toastElement.querySelector('.toast-message').textContent = message;
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });
    toast.show();
}

const showErrorToast = (message) => {
    const toastElement = document.getElementById('errorToast');
    toastElement.querySelector('.toast-message').textContent = message;
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });
    toast.show();
}
