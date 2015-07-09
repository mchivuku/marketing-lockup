@extends('app')
@section('left-navigation')

@include('leftnavigation',array('navigation'=>$leftnavigation))

@endsection

@yield('content')