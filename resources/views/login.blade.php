@extends('layouts.master', ['bodyClass' => 'auth'])

@section('content')
    @if(session()->has('success'))
        <notification-alert message="{{session()->get('success')}}"></notification-alert>
    @endif
    <login-form></login-form>
@endsection
