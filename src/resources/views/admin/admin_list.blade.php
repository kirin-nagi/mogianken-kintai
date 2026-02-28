<!-- 勤怠一覧（管理者） -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/list.css') }}">
@endsection

@section('content')
<div class="admin-list__content">
    <div class="admin-list__heading">
        <div id="date" class="date">{{ $currentDay->format('Y年m月d日') }}の勤務</div>
    </div>
    <div class="day-navigation">
        <a href="{{ route('admin.list', ['day' => $prevDay->format('Y-m-d')]) }}" class="prev-day">
            ← 前日
        </a>
        <span class="current-day">
            📅  {{ $currentDay->format('Y-m-d')}}
        </span>
        <a href="{{ route('admin.list', ['day' => $nextDay->format('Y-m-d')]) }}" class="next-day">
            翌日 →
        </a>
    </div>
    <table>
        <tr>
            <td colspan="6" class="empty-row">
                <div class="empty-columns">
                    <span>名前</span>
                    <span>出勤</span>
                    <span>退勤</span>
                    <span>休憩</span>
                    <span>合計</span>
                    <span>詳細</span>
                </div>
            </td>
        </tr>

        @foreach($attendances as $userAttendances)
        @php
        $first = $userAttendances->sortBy('start_work')->first();

        $last = $userAttendances->whereNotNull('end_work')->sortByDesc('end_work')->first();

        $totalRestMinutes = $userAttendances->flatMap->rests->sum('rest_time');

        $totalWorkSeconds = $userAttendances->sum('total_work_seconds');

        $startTime = $first->start_work->format('H:i');
        $endTime = $last?->end_work?->format('H:i') ?? '-';
        $restTime = $totalRestMinutes ? gmdate('H:i', $totalRestMinutes * 60) : '0:00';
        $workTime = gmdate('H:i', $totalWorkSeconds);
        @endphp
        <tr>
            <td colspan="6" class="empty-row">
                <div class="empty-columns">
                    <span>
                    <!-- 名前にしたい -->
                    {{ $first->user->name }}
                    </span>
                    <span>
                    <!-- 出勤 -->
                    {{ $startTime }}
                    </span>
                    <span>
                    <!-- 退勤 -->
                    {{ $endTime }}
                    </span>
                    <span>
                    <!-- 休憩合計 -->
                    {{ $restTime }}
                    </span>
                    <span>
                    <!-- 勤務合計 -->
                    {{ $workTime }}
                    </span>
                    <span>
                        <a href="{{ route('admin.attendance.showDetail', $first->id) }}" class="detail-item">詳細</a>
                    </span>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection