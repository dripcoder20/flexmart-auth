@extends('layouts.master', ['bodyClass' => 'auth'])

@section('content')
    <password-reset-form token="{{request('token')}}"></password-reset-form>
@endsection

