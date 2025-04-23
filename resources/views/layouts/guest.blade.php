@extends('layouts.template.base')
@push('styles')
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
@endpush

@section('header')
    <x-header />
@endsection

@section('footer')
    <x-footer />
@endsection
