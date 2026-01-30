<!-- スタッフ別勤怠一覧（管理者） -->
@extends('layouts.admin_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff_each.css') }}">
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
</div>