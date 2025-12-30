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
    <!-- 教材見ながらやる -->
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
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endforeach
        @foreach($attendance->rests as $rest)
        <tr>
            <td></td>
        </tr>
        @endforeach
        @foreach
        <tr>
            <td></td>
        </tr>
        @endforeach
    </table>
</div>
@endsection