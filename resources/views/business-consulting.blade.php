@extends('layouts.home', ['title' => 'Business Consulting | REDTax Financial Services'])


@section('content')
    @include('landing_page.navigation')
    @include('landing_page.business-consulting')
    @include('landing_page.newsletter')   
    @include('landing_page.site-footer')  
@endsection