<section class="bg-light-red pt-3">
    <div class="container">
        <div class="d-flex justify-content-start align-items-center">
            <h1 class="fs-1 fw-bold text-start text-light-white pb-3 lsp-2">
                <i class="bi bi-card-checklist"></i> Tax Planning</h1>
        </div>
    </div>
</section>
<section class=" bg-dark-red py-1"></section>
<section class="bg-light-gray py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="list-group">
                    <a href="{{ route('tax-services') }}" class="list-group-item list-group-item-action fs-5 py-3 fw-medium">
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
                    <a href="{{ route('tax-planning') }}" class="list-group-item list-group-item-action bg-dark text-light border-0 fs-5 py-3 fw-medium" aria-current="true">
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
                <img src="{{ asset('assets/images/tax-planning.jpg') }}" class="img-responsive" alt="Tax Planning">
                <div class="bg-light p-4">
                    <h2 class="fw-bold">Tax Planning</h2>
                    <p class="fw-medium text-justify">   
                        Our tax planning services are designed to help you minimize your tax liabilities while staying fully compliant with tax laws. We analyze your financial situation, identify opportunities for tax savings, and develop a customized plan that aligns with your financial goals. Whether you're an individual, a small business, or a corporation, our experienced tax planners provide proactive advice and innovative strategies to optimize your tax outcomes year-round.
                    </p>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('contact') }}" class="btn btn-dark p-3">Get A Quote <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</section>