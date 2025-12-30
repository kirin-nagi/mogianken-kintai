<!-- 出勤登録画面 出勤退勤・休憩始、終、if文で-->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="date-time__content">
    @if(!$attendance)
    <p class="attendance-item">勤務外</p>
    <div class="clock">
        <div id="date" class="date"></div>
        <div id="time" class="time"></div>
    </div>
    <form action="{{ route('attendance.start') }}" method="post" class="attendance__button">
        @csrf
        <button class="attendance__button-submit" type="submit">出勤</button>
    </form>
    @elseif ($attendance->isFinished())
    <p class="attendance-item">退勤済</p>
    <div class="clock">
        <div id="date" class="date"></div>
        <div id="time" class="time"></div>
    </div>
    <p class="end-time">お疲れさまでした。</p>
    @elseif($attendance->isOnRest())
    <p class="attendance-item">休憩中</p>
    <div class="clock">
        <div id="date" class="date"></div>
        <div id="time" class="time"></div>
    </div>
    <form action="{{ route('attendance.rest_end') }}" method="post" class="attendance__button">
        @csrf
        <button class="attendance__button-submit" type="submit">休憩戻</button>
    </form>
    @else
    <p class="attendance-item">出勤中</p>
    <div class="clock">
        <div id="date" class="date"></div>
        <div id="time" class="time"></div>
    </div>
    <form action="{{ route('attendance.rest_start') }}" method="post" class="attendance__button">
        @csrf
        <button class="attendance__button-submit" type="submit">休憩</button>
    </form>
    <form action="{{ route('attendance.end') }}" method="post" class="attendance__button">
        @csrf
        <button class="attendance__button-submit" type="submit">退勤</button>
    </form>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded',() => {
        function updateClock(){
            const now = new Date();

            const weekdays = ['日','月','火','水','木','金','土'];
            const day = weekdays[now.getDay()];

            document.getElementById('date').textContent =
            `${now.getFullYear()}年${now.getMonth() + 1}月${now.getDate()}日(${day})`;

            document.getElementById('time').textContent =
            now.toLocaleTimeString('ja-JP',{
                hour: '2-digit',
                minute: '2-digit',
            });
        }

        updateClock();
        setInterval(updateClock, 1000);
    });
</script>
@endsection

