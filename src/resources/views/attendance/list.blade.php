<!-- 勤怠一覧 -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="attendance-list__content">
    <div class="month-navigation">
        <a href="{{ route('attendance.list', ['month' => $prevMonth->format('Y-m')]) }}">
            ← 前月
        </a>
        <span class="current-month">
            {{ $currentMonth->format('Y/m') }}
        </span>
        <a href="{{ route('attendance.list', ['month' => $nextMonth->format('Y-m')]) }}">
            翌月 →
        </a>
    </div>
    <div class="attendance-list__heading">
        <p class="list-heading__item" >勤怠一覧</p>
    </div>
    <table>
        <tr>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>
        @foreach($dates as $date)
        @php
        $attendance = $attendances[$date->format('Y-m-d')] ?? null;
        @endphp

        <tr>
            <td>
                <!-- 日付 -->
                {{ $date->format('m/d') }}
                ({{ [ '日','月','火','水','木','金','土',][$date->dayOfWeek]}})
            </td>
            @if($attendance)
            <td>
                <!-- 出勤 -->
                {{ $attendance->start_work->format('H:i') }}
            </td>
            <td>
                <!-- 退勤 -->
                {{ $attendance->end_work?->format('H:i') ?? '-' }}
            </td>
            <td>
                <!-- 休憩合計 -->
                {{ $attendance->rests->sum('rest_time') ? $attendance->total_rest_formatted : '0:00' }}
            </td>
            <td>
                <!-- 勤務合計 -->
                {{ $attendance->total_work_formatted }}
            </td>
            <td>
                <a href="{{ route('attendance.detail', $attendance) }}">詳細</a>
            </td>
            @else
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @endif
        </tr>
        @endforeach
    </table>
</div>
@endsection

<!-- 休みの日は何も表示されないように設定 -->
<!-- user_idに合った勤務表表示 -->