@extends('layouts.home')

@section('content')
    @include('landing_page.navigation')
    @include('landing_page.hero')
    @include('landing_page.hero-contact')
    @include('landing_page.about')
    @include('landing_page.services')
    @include('landing_page.success')
    @include('landing_page.newsletter')   
    @include('landing_page.site-footer')  
@endsection

