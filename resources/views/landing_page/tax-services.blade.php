<section class="bg-light-red pt-3">
    <div class="container">
        <div class="d-flex justify-content-start align-items-center">
            <h1 class="fs-1 fw-bold text-start text-light-white pb-3 lsp-2">
                <i class="bi bi-card-checklist"></i> Tax Services</h1>
        </div>
    </div>
</section>
<section class=" bg-dark-red py-1"></section>
<section class="bg-light-gray py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="list-group">
                    <a href="{{ route('tax-services') }}" class="list-group-item list-group-item-action bg-dark text-light border-0 fs-5 py-3 fw-medium" aria-current="true">
                        <i class="bi bi-caret-right-fill me-1"></i> Tax Services
                    </a>
                    <a href="{{ route('audit-representation') }}" class="list-group-item list-group-item-action fs-5 py-3 fw-medium">
                        <i class="bi bi-caret-right-fill me-1"></i> Audit Representation
                    </a>
                    <a href="{{ route('business-consulting') }}" class="list-group-item list-group-item-action fs-5 py-3 fw-medium">
                        <i class="bi bi-caret-right-fill me-1"></i>  Business Consulting
                    </a>
                    <a href="{{ route('bookkeeping-and-payroll') }}" class="list-group-item list-group-item-action fs-5 py-3 fw-medium">
                        <i class="bi bi-caret-right-fill me-1"></i>  Bookkeeping and Payroll
                    </a>
                    <a href="{{ route('tax-planning') }}" class="list-group-item list-group-item-action fs-5 py-3 fw-medium">
                        <i class="bi bi-caret-right-fill me-1"></i>  Tax Planning
                    </a>
                    <a href="{{ route('life-insurance') }}" class="list-group-item list-group-item-action fs-5 py-3 fw-medium">
                        <i class="bi bi-caret-right-fill me-1"></i>  Life Insurance
                    </a>
                    <a href="{{ route('health-insurance') }}" class="list-group-item list-group-item-action fs-5 py-3 fw-medium">
                        <i class="bi bi-caret-right-fill me-1"></i> Health Insurance
                    </a>
                </div>
            </div>
            
            <div class="col-lg-8">
                <img src="{{ asset('assets/images/tax-services.jpg') }}" class="img-responsive" alt="Tax Services">
                <div class="bg-light p-4">
                    <h2 class="fw-bold">Tax Services</h2>
                    <p class="fw-medium text-justify">   
                        Our tax services include meticulous tax preparation, strategic tax planning, and representation for audits. We ensure compliance with all tax laws while maximizing your savings. Whether you are an individual, small business, or large corporation, our experienced tax professionals are here to provide personalized support and expert advice.
                    </p>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('contact') }}" class="btn btn-dark p-3">Get A Quote <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</section>