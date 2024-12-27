<section id="services" class="bg-light-gray py-5">
  <div class="container">
    <h1 class="section-heading text-center text-dark-navy mb-3"><i class="bi bi-dash-lg"></i> Services We Offer <i class="bi bi-dash-lg"></i></h1>
    <h3 class="fs-5 text-center pb-5">Comprehensive tax, financial planning, and insurance solutions tailored to your needs.</h3>
    <div class="row">
        <div class="col-lg-12 text-end">
          <a
            class="btn btn-dark-red mb-3 mr-1"
            href="#carouselExampleIndicators2"
            data-bs-target="#carouselExampleIndicators2"
            data-bs-slide="prev"
          >
            <i class="bi bi-arrow-left"></i>
          </a>
          <a
            class="btn btn-dark-red mb-3"
            href="#carouselExampleIndicators2"
            data-bs-target="#carouselExampleIndicators2"
            data-bs-slide="next"
          >
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>
        <div class="col-lg-12">
          <div
            id="carouselExampleIndicators2"
            class="carousel slide"
            data-bs-ride="carousel"
          >
            <div class="carousel-inner">
              
              <div class="carousel-item active">
                <div class="row">
                  <div class="col-md-4 col-sm-12 mb-3">
                    <div class="card">
                      <img
                        class="img-fluid"
                        alt="100%x280"
                        src="{{ asset('assets/images/tax-services.jpg') }}"
                      />
                      <div class="card-body">
                        <h4 class="card-title fw-bold">Tax Services</h4>
                        <p class="card-text fw-medium">
                            Comprehensive tax preparation and planning for individuals and businesses.
                        </p>
                        <div class="text-end">
                          <a href="{{ route('tax-services') }}"  class="btn btn-dark-red">Read More<i class="bi bi-arrow-right-short ms-1"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12 mb-3">
                    <div class="card">
                      <img
                        class="img-fluid"
                        alt="100%x280"
                        src="{{ asset('assets/images/audit-representation.jpg') }}"
                      />
                      <div class="card-body">
                        <h4 class="card-title fw-bold">Audit Representation</h4>
                        <p class="card-text fw-medium">
                          Professional representation during tax audits and disputes.
                        </p>
                        <div class="text-end">
                          <a href="{{ route('audit-representation') }}"  class="btn btn-dark-red">Read More<i class="bi bi-arrow-right-short ms-1"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12 mb-3">
                    <div class="card">
                      <img
                        class="img-fluid"
                        alt="100%x280"
                        src="{{ asset('assets/images/business-consulting.jpg') }}"
                      />
                      <div class="card-body">
                        <h4 class="card-title fw-bold">Business Consulting</h4>
                        <p class="card-text fw-medium">
                          Expert advice to enhance business efficiency and growth.
                        </p>
                        <div class="text-end">
                          <a href="{{ route('business-consulting') }}"  class="btn btn-dark-red">Read More<i class="bi bi-arrow-right-short ms-1"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="carousel-item">
                <div class="row">
                  <div class="col-md-4 col-sm-12 mb-3">
                    <div class="card">
                      <img
                        class="img-fluid"
                        alt="100%x280"
                        src="{{ asset('assets/images/tax-planning.jpg') }}"
                      />
                      <div class="card-body">
                        <h4 class="card-title fw-bold">Tax Planning</h4>
                        <p class="card-text fw-medium">
                            Strategic tax planning to maximize savings and ensure compliance.
                        </p>
                        <div class="text-end">
                          <a href="{{ route('tax-planning') }}" class="btn btn-dark-red">Read More<i class="bi bi-arrow-right-short ms-1"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12 mb-3">
                    <div class="card">
                      <img
                        class="img-fluid"
                        alt="100%x280"
                        src="{{ asset('assets/images/life-insurance.png') }}"
                      />
                      <div class="card-body">
                        <h4 class="card-title fw-bold">Life Insurance</h4>
                        <p class="card-text fw-medium">
                            Tailored life insurance solutions to protect your love ones' financial future.
                        </p>
                        <div class="text-end">
                          <a href="{{ route('life-insurance') }}"  class="btn btn-dark-red">Read More<i class="bi bi-arrow-right-short ms-1"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12 mb-3">
                    <div class="card">
                      <img
                        class="img-fluid"
                        alt="100%x280"
                        src="{{ asset('assets/images/health-insurance.png') }}"
                      />
                      <div class="card-body">
                        <h4 class="card-title fw-bold">Health Insurance</h4>
                        <p class="card-text fw-medium">
                            Comprehensive health insurance plans for individuals, families, and businesses.
                        </p>
                        <div class="text-end">
                          <a href="{{ route('health-insurance') }}"  class="btn btn-dark-red">Read More<i class="bi bi-arrow-right-short ms-1"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                     
              <div class="carousel-item">
                <div class="row">
                  
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img
                                class="img-fluid"
                                alt="100%x280"
                                src="{{ asset('assets/images/bookkeeping-and-payroll.jpg') }}"
                            />
                            <div class="card-body">
                                <h4 class="card-title fw-bold">Bookkeeping and Payroll</h4>
                                <p class="card-text fw-medium">
                                  Accurate and efficient bookkeeping and payroll services.
                                </p>
                                <div class="text-end">
                                  <a href="{{ route('bookkeeping-and-payroll') }}"  class="btn btn-dark-red">Read More<i class="bi bi-arrow-right-short ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
  </div>
</section>