<!-- 勤怠一覧 -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')
<div class="attendance-list__content">
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
        @foreach($attendances as $attendance)
        <tr>
            <td>
                {{ $attendance->start_work->format('m/d') }}
                ({{ [ '日','月','火','水','木','金','土',][$attendance->start_work->dayOfweek]}})
            </td>
            <td>
                {{ $attendance->start_work->format('H:i') }}
            </td>
            <td>
                {{ $attendance->end_work?->format('H:i') ?? '-' }}
            </td>
            <td>
                {{ $attendance->total_rest_time }}
            </td>
            <td>
                {{ $attendance->total_work_time }}
            </td>
            <td>
                <a href="{{ route('attendance.detail', $attendance) }}">詳細</a>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection

<!-- 休みの日は何も表示されないように設定 -->