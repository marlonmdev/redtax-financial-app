(function() {
    let scrollPosition = 0;
    // Save scroll position before the request
    document.addEventListener('htmx:beforeRequest', function() {
        scrollPosition = window.scrollY;
    });

    // Restore scroll position after content swap
    document.addEventListener('htmx:afterSwap', function() {
        window.scrollTo(0, scrollPosition);
    });
})();

(function() {
    // Start of the navigation links functions
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    function setActiveLink() {
        const currentUrl = window.location.href;
        const currentPath = window.location.pathname;
        const currentHash = window.location.hash;

        navLinks.forEach(link => {
            // Remove active class from all links
            link.classList.remove('active');

            // Get the full URL, path, and hash from each link
            const linkUrl = link.href;
            const linkPath = new URL(linkUrl).pathname;
            const linkHash = link.hash;

            // Match the exact path and hash
            if (currentHash && currentHash === linkHash) {
                link.classList.add('active');
            } else if (!currentHash && linkPath === currentPath && linkHash === '') {
                // Handle links without a fragment, like home or contact
                link.classList.add('active');
            } else if (currentPath === '/contact' && linkPath === '/contact') {
                // Handle the contact link specifically
                link.classList.add('active');
            } else if (currentPath.match(/\/appointment\/\d+\/calendar/) && linkPath === '/appointment') {
                // Handle the appointment calendar link specifically
                link.classList.add('active');
            }
        });
    }

    // Set active link when a nav item is clicked
    navLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            const linkHash = this.hash;

            // Only prevent default for links with fragments (smooth scrolling)
            if (linkHash) {
                event.preventDefault();
                if (window.location.pathname !== '/') {
                    // Redirect to the homepage with the fragment
                    window.location.href = '/' + linkHash;
                } else {
                    // Just update the hash for smooth scrolling on the same page
                    window.location.hash = linkHash;
                }
            }
            // Set the active link manually
            setActiveLink();
        });
    });

    setActiveLink();
    // End of the navigation links functions

})();


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

function showPassword(){
	let inputPassword = document.querySelector("#password");
	if (inputPassword.type === "password") {
		inputPassword.type = "text";
	} else {
		inputPassword.type = "password";
	}
}

function formatPhoneNumber(event){
    let inputElement = event.target;
    let input = inputElement.value.replace(/\D/g, ''); // Remove all non-digit characters

    if (input.length <= 3) {
        input = `(${input}`;
    } else if (input.length <= 6) {
        input = `(${input.slice(0, 3)}) ${input.slice(3)}`;
    } else if (input.length <= 10) {
        input = `(${input.slice(0, 3)}) ${input.slice(3, 6)}-${input.slice(6)}`;
    } else {
        input = `(${input.slice(0, 3)}) ${input.slice(3, 6)}-${input.slice(6, 10)}`;
    }

    inputElement.value = input;
}
