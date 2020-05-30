@extends('layouts.master', ['bodyClass' => 'auth'])

@section('content')
    Code: {{$code?:'expired'}}
    <verification-form token="{{request('token')}}" mobile="{{$mobile}}"></verification-form>
@endsection
