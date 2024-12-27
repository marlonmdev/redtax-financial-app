<x-app-layout>
    <div class="pagetitle">
        <h1>Document Management</h1>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">           
                @if(Gate::allows('isClient'))  
                    @include('documents.upload-form')
                @endif
                
                <div class="card">
                    <div class="card-body">                            
                        @include('documents.table-data')
                    </div>
                </div>
                  
            </div>
        </div>
    </section>
</x-app-layout>