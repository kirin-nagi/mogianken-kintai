<!-- 修正申請承認画面 -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/stamp_detail.css') }}">
@endsection

@section('content')
<div class="stamp-correction__content">
    <div class="stamp-correction__heading">
        <p class="stamp-correction__heading-item">勤怠詳細</p>
    </div>
    <div class="stamp-correction__table">
        <table class="stamp-correction__inner">
            <tr class="stamp-correction__item">
                <th class="heading-item">名前</th>
                <td class="text-item">
                    <p class="approval-name">{{ $approval->user->name }}</p>
                </td>
            </tr>
            <tr class="stamp-correction__item">
                <th class="heading-item">日付</th>
                <td class="year-date">
                    <p class="top-item">{{ $detail->work_date->format('Y') }}年</p>
                    <p class="bottom-item">{{ $detail->work_date->format('n月j日') }}</p>
                </td>
            </tr>
            <tr class="stamp-correction__item">
                <th class="heading-item">出勤・退勤</th>
                <td class="date-time">
                    <p class="top-item">{{ $detail->start_work->format('H:i') }}</p>
                    <span class="time-separator">～</span>
                    <p class="bottom-item">{{ $detail->end_work->format('H:i') }}</p>
                </td>
            </tr>
            <tr class="stamp-correction__item">
                <th class="heading-item">休憩</th>
                <td class="date-time">
                    <p class="top-item">{{ $detail->rest_start->format('H:i') }}</p>
                    <span class="time-separator">～</span>
                    <p class="bottom-item">{{ $detail->rest_end->format('H:i') }}</p>
                </td>
            </tr>
            <tr class="stamp-correction__item">
                <th class="heading-item">休憩２</th>
                <td class="date-time">
                    <p class="top-item">{{ $detail->rest_start2?->format('H:i') }}</p>
                    @if($detail->rest_start2 && $detail->rest_end2)
                    <span class="time-separator">～</span>
                    @endif
                    <p class="bottom-item">{{ $detail->rest_end2?->format('H:i') }}</p>
                </td>
            </tr>
            <tr class="stamp-correction__item">
                <th class="heading-item">備考</th>
                <td class="text-item">
                    <p class="detail-reason">{{ $detail->reason }}</p>
                </td>
            </tr>
        </table>
    </div>
    <div class="stamp__button">
        @if($approval->status === 0)
        <form method='post' action="{{ route('stamp.stampCorrection', $approval->id) }}" >
            @csrf
            <button class="stamp__button-submit" type="submit">承認</button>
        </form>
        @else
        <span class="stamp__button-approved">承認済み</span>
        @endif
    </div>
</div>
@endsection