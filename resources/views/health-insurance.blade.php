@extends('layouts.home', ['title' => 'Health Insurance | REDTax Financial Services'])


@section('content')
    @include('landing_page.navigation')
    @include('landing_page.health-insurance')
    @include('landing_page.newsletter')   
    @include('landing_page.site-footer')  
@endsection