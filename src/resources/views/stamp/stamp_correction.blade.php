<!-- 修正申請承認画面 -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stamp_correction.css') }}">
@endsection

@section('content')
<div class="stamp-correction__content">
    <div class="detail__heading">
        <p class="detail__heading-item">勤怠詳細</p>
    </div>
    <div class="stamp-correction__table">
        <table class="stamp-correction__inner">
            <tr>
                <th>名前</th>
                <td>{{ $approval->user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>
                    {{ $detail->work_date->format('Y') }}年
                    {{ $detail->work_date->format('n月j日') }}
                </td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    {{ $detail->start_work->format('H:i') }}
                    {{ $detail->end_work->format('H:i') }}
                </td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    {{ $detail->rest_start->format('H:i') }}
                    {{ $detail->rest_end->format('H:i') }}
                </td>
            </tr>
            <tr>
                <th>休憩２</th>
                <td>
                    {{ $detail->rest_start2?->format('H:i') }}
                    {{ $detail->rest_end2?->format('H:i') }}
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td>
                    {{ $detail->reason }}
                </td>
            </tr>
        </table>
    </div>
    <div class="form__button">
        @if($approval->status === '承認待ち')
        <form action="{{ route('stampCorrection', $approval->id) }}" method="post">
            @csrf
            <button class="form__button-submit" type="submit">承認</button>
        </form>
        @else
        <span class="form__button-approved">承認済み</span>
        @endif
    </div>
</div>
@endsection