@extends('layouts.home', ['title' => 'Bookkeeping and Payroll | REDTax Financial Services'])


@section('content')
    @include('landing_page.navigation')
    @include('landing_page.bookkeeping-and-payroll')
    @include('landing_page.newsletter')   
    @include('landing_page.site-footer')  
@endsection