@extends('layouts.master', ['bodyClass' => 'auth'])

@section('content')
    <registration-form token="{{request('token')}}"></registration-form>
@endsection
