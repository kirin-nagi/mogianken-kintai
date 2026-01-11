@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="attendance-list__content">
    <div class="attendance-list__heading">
        <p class="list-heading__item" >勤怠一覧</p>
    </div>
    <div class="month-navigation">
        <a href="{{ route('attendance.list', ['month' => $prevMonth->format('Y-m')]) }}" class="prev-month">
            ← 前月
        </a>
        <span class="current-month">
            📅  {{ $currentMonth->format('Y/m') }}
        </span>
        <a href="{{ route('attendance.list', ['month' => $nextMonth->format('Y-m')]) }}" class="next-month">
            翌月 →
        </a>
    </div>
    <table>
        <tr>
            <td colspan="6" class="empty-row">
                <div class="empty-columns">
                    <span>日付</span>
                    <span>出勤</span>
                    <span>退勤</span>
                    <span>休憩</span>
                    <span>合計</span>
                    <span>詳細</span>
                </div>
            </td>
        </tr>

        @foreach($dates as $date)
        @php
        $attendance = $attendances[$date->format('Y-m-d')] ?? null;
        @endphp
        <tr>
            <td colspan="6" class="empty-row">
                <div class="empty-columns">
                    <span>
                    <!-- 日付 -->
                    {{ $date->format('m/d') }}
                    ({{ [ '日','月','火','水','木','金','土',][$date->dayOfWeek]}})
                    </span>
                    @if($attendance)
                    <span>
                    <!-- 出勤 -->
                    {{ $attendance->start_work->format('H:i') }}
                    </span>
                    <span>
                    <!-- 退勤 -->
                    {{ $attendance->end_work?->format('H:i') ?? '-' }}
                    </span>
                    <span>
                    <!-- 休憩合計 -->
                    {{ $attendance->rests->sum('rest_time') ? $attendance->total_rest_formatted : '0:00' }}
                    </span>
                    <span>
                    <!-- 勤務合計 -->
                    {{ $attendance->total_work_formatted }}
                    </span>
                    <span>
                        <a href="{{ route('attendance.detail', $attendance->id) }}" class="detail-item">詳細</a>
                    </span>
                    @else
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span>
                        <a href="{{ route('attendance.detail', $date->format('Y-m-d')) }}" class="detail-item">詳細</a>
                    </span>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection