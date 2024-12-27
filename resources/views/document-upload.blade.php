@extends('layouts.home', ['title' => 'Document Upload | REDTax Financial Services'])

@section('content')
    @include('landing_page.navigation')
    @include('landing_page.document-upload')
    @include('landing_page.success')
    @include('landing_page.newsletter')   
    @include('landing_page.site-footer')  
@endsection

@section('script')
    document.getElementById('email').addEventListener('blur', function () {
        const email = this.value;
        
        if (email) {
            axios.post('{{ route('check.email') }}', {
                email: email,
                _token: '{{ csrf_token() }}'
            })
            .then(function (response) {
                if (response.data.exists) {
                    document.getElementById('emailError').innerHTML = 'This email address was already registered. Please request your account access by clicking the \"LOGIN AS\" button on the top right corner and select \"CLIENT\".';
                    document.getElementById('email').classList.add('is-invalid');
                } else {
                    document.getElementById('emailError').innerHTML = '';
                    document.getElementById('email').classList.remove('is-invalid');
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        } else if(email === ''){
            document.getElementById('emailError').innerHTML = '';
            document.getElementById('email').classList.remove('is-invalid');
        }
    });  
@endsection