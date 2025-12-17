<!-- 出勤登録画面 出勤退勤・休憩始、終、if文で-->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<p>{{ now()->toDateString() }}</p>
<div class=""></div>

@endsection

