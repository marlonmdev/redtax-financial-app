<x-app-layout>
    <div class="pagetitle">
        @if(Gate::allows('accessRestrictedPages'))
            <h1>Appointment Management</h1>
            <p class="text-dark"> <a href="{{ route('appointments.index') }}">Appointments</a> <i class="bi bi-caret-right-fill"></i> Preview</p>
        @else
            <h1>My Appointments</h1>
        @endif
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if(Gate::allows('accessRestrictedPages'))
                            <div class="d-flex justify-content-between py-3">
                                <div class="d-flex justify-content-start">
                                    <a href="javascript:window.history.back();" class="btn btn-dark">Close</a>
                                </div>   
                                
                                <div class="form-group justify-content-end d-flex gap-2">
                                    <a href="{{ route('appointments.edit', ['appointment' => $appointment->id]) }}" class="btn btn-success" title="Edit Appointment">Edit</a>
        
                                    <form hx-post="{{ route('appointments.destroy', ['appointment' => $appointment->id]) }}" hx-target="body">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" onclick="return confirm('Are you sure to delete appointment?')" class="btn btn-danger" title="Delete Appointment">Delete</button>
                                    </form>
                                </div>                      
                            </div>
                        @else
                            <div class="d-flex justify-content-between align-items-center py-3">
                                <h5 class="text-dark fw-medium">Appointment Details</h5>
                                <a href="{{ route('my-appointments') }}" class="btn btn-dark">Close</a>
                            </div>
                        @endif                             
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-medium text-nowrap">Name:</td>
                                        <td class="fst-italic">{{ $appointment->name }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Email:</td>
                                        <td class="fst-italic">{{ $appointment->email }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Phone Number:</td>
                                        <td class="fst-italic">{{ $appointment->phone ? $appointment->phone : 'None' }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Service:</td>
                                        <td class="fst-italic">{{ $appointment->service->service ?  $appointment->service->service : 'None' }}</td>
                                    </tr>
                                    
                                  
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Date:</td>
                                        <td class="fst-italic">{{ date('F d, Y', strtotime($appointment->date)) }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">From:</td>
                                        <td class="fst-italic">{{ date('g:i A', strtotime($appointment->start_time)) }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">To:</td>
                                        <td class="fst-italic">{{ date('g:i A', strtotime($appointment->end_time)) }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Location:</td>
                                        <td class="fst-italic">{{ $appointment->location }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Details:</td>
                                        <td class="fst-italic">{{ $appointment->details ? $appointment->details : 'None' }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Referred By:</td>
                                        <td class="fst-italic">{{ $appointment->referred_by ? $appointment->referred_by : 'None' }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Status:</td>
                                        <td class="fst-italic">{{ $appointment->status }}</td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>