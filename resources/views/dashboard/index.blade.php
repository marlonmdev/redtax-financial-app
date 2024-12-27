<x-app-layout>
  
  <div class="pagetitle">
    <h1>Dashboard</h1>
  </div>

  <section class="section dashboard">
      <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
            @include('dashboard.partials.overview-cards')
          
            @if(Gate::allows('accessRestrictedPages'))
              @include('dashboard.partials.weekly-appointments')
              @include('dashboard.partials.recent-clients')
              @include('dashboard.partials.recent-documents')
            @endif
            
            @if(Gate::allows('isClient'))
              @include('dashboard.partials.upload-form')
            @endif
          </div>
        </div>
        <!-- End Left side columns -->

        <!-- Right side columns -->
        {{-- <div class="col-lg-4">
          <div class="card"> 
            <div class="card-body">
              <h5 class="card-title">Recent Activity</h5>

              <div class="activity">
                <div class="activity-item d-flex">
                  <div class="activite-label">32 min</div>
                  <i
                    class="bi bi-circle-fill activity-badge text-success align-self-start"
                  ></i>
                  <div class="activity-content">
                    Quia quae rerum
                    <a href="#" class="fw-bold text-dark"
                      >explicabo officiis</a
                    >
                    beatae
                  </div>
                </div>
                <!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">56 min</div>
                  <i
                    class="bi bi-circle-fill activity-badge text-danger align-self-start"
                  ></i>
                  <div class="activity-content">
                    Voluptatem blanditiis blanditiis eveniet
                  </div>
                </div>
                <!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2 hrs</div>
                  <i
                    class="bi bi-circle-fill activity-badge text-primary align-self-start"
                  ></i>
                  <div class="activity-content">
                    Voluptates corrupti molestias voluptatem
                  </div>
                </div>
                <!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">1 day</div>
                  <i
                    class="bi bi-circle-fill activity-badge text-info align-self-start"
                  ></i>
                  <div class="activity-content">
                    Tempore autem saepe
                    <a href="#" class="fw-bold text-dark"
                      >occaecati voluptatem</a
                    >
                    tempore
                  </div>
                </div>
                <!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2 days</div>
                  <i
                    class="bi bi-circle-fill activity-badge text-warning align-self-start"
                  ></i>
                  <div class="activity-content">
                    Est sit eum reiciendis exercitationem
                  </div>
                </div>
                <!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">4 weeks</div>
                  <i
                    class="bi bi-circle-fill activity-badge text-muted align-self-start"
                  ></i>
                  <div class="activity-content">
                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                  </div>
                </div>
                <!-- End activity item-->
              </div>
            </div>
          </div>
          <!-- End Recent Activity -->
        </div> --}}
        <!-- End Right side columns -->
      </div>
  </section>

</x-app-layout>