@extends('layouts.home', ['title' => 'Tax Planning | REDTax Financial Services'])


@section('content')
    @include('landing_page.navigation')
    @include('landing_page.tax-planning')
    @include('landing_page.newsletter')   
    @include('landing_page.site-footer')  
@endsection