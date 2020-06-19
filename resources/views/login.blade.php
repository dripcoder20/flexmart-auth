@extends('layouts.master', ['bodyClass' => 'auth'])

@section('content')
test
@if(session()->has('success'))
<notification-alert message="{{session()->get('success')}}"></notification-alert>
@endif
<login-form></login-form>
@endsection