@extends('layouts.master', ['bodyClass' => 'auth'])

@section('content')
<div class="tw-fixed">
    Code: {{$code?:'expired'}}
</div>
<verification-form token="{{request('token')}}" mobile="{{$mobile}}"></verification-form>
@endsection
