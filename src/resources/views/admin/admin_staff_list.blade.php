<!-- スタッフ別勤怠一覧（管理者） -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff_list.css') }}">
@endsection

@section('content')
<div class="admin-each__content">
    <div class="admin-each__heading">
        <div id="date" class="date">{{ $user->name }}さんの勤務</div>
    </div>
    <div class="month-navigation">
        <a href="{{ route('admin.attendanceList', ['id' => $user->id,'month' => $prevMonth->format('Y-m')]) }}" class="prev-month">
            ← 前月
        </a>
        <span class="current-month">
            📅  {{ $currentMonth->format('Y/m') }}
        </span>
        <a href="{{ route('admin.attendanceList', ['id' => $user->id, 'month' => $nextMonth->format('Y-m')]) }}" class="next-month">
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
        $key = $date->format('Y-m-d');
        $dayAttendances = $attendances->get($key);

        if($dayAttendances){
            $first = $dayAttendances->sortBy('start_work')->first();
            $last = $dayAttendances->whereNotNull('end_work')->sortByDesc('end_work')->first();

            $totalRestMinutes = $dayAttendances->flatMap->rests->sum('rest_time');
            $totalWorkSeconds = $dayAttendances->sum('total_work_seconds');
        }
        @endphp
        <tr>
            <td colspan="6" class="empty-row">
                <div class="empty-columns">
                    <span>
                    <!-- 日付 -->
                    {{ $date->format('m/d') }}
                    ({{ [ '日','月','火','水','木','金','土',][$date->dayOfWeek]}})
                    </span>
                    @if($dayAttendances)
                    <span>
                    <!-- 出勤 -->
                    {{ $first?->start_work?->format('H:i') ?? '-' }}
                    </span>
                    <span>
                    <!-- 退勤 -->
                    {{ $last?->end_work?->format('H:i') ?? '-' }}
                    </span>
                    <span>
                    <!-- 休憩合計 -->
                    {{ $totalRestMinutes ? gmdate('H:i', $totalRestMinutes * 60) : '0:00' }}
                    </span>
                    <span>
                    <!-- 勤務合計 -->
                    {{ gmdate('H:i', $totalWorkSeconds) }}
                    </span>
                    <span>
                        <a href="{{ route('attendance.adminShowDetail', $first->id) }}" class="detail-item">詳細</a>
                    </span>
                    @else
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span class="detail-holiday">詳細</span>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </table>
    <div class="admin-each__button">
        <button class="admin-each__button-submit" type="submit">CSV出力</button>
    </div>
</div>
@endsection