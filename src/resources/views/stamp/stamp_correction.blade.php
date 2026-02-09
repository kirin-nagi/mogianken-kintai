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
    <form action="" method="post" class="form">
        <div class="stamp-correction__table">
            <table class="stamp-correction__inner">
                <tr class="stamp-correction__row">
                    <th class="stamp-correction__heading">名前</th>
                    <td class="stamp-correction__item">
                        <span>{{ $approval->user->name }}</span>
                    </td>
                </tr>
                <tr class="stamp-correction__row">
                    <th class="stamp-correction__heading">日付</th>
                    <td class="stamp-correction__item">
                        <span>{{ $detail->work_date->format('Y') }}年</span>
                        <span>{{ $detail->work_date->format('n月j日') }}</span>
                    </td>
                </tr>
                <tr class="stamp-correction__row">
                    <th class="stamp-correction__heading">出勤・退勤</th>
                    <td class="stamp-correction__item">
                        <span>{{ $detail->start_work->format('H:i') }}</span>
                        <span>{{ $detail->end_work->format('H:i') }}</span>
                    </td>
                </tr>
                <tr class="stamp-correction__row">
                    <th class="stamp-correction__heading">休憩</th>
                    <td class="stamp-correction__item">
                        <span>{{ $detail->rest_start->format('H:i') }}</span>
                        <span>{{ $detail->rest_end->format('H:i') }}</span>
                    </td>
                </tr>
                <tr class="stamp-correction__row">
                    <th class="stamp-correction__heading">休憩２</th>
                    <td class="stamp-correction__item">
                        <span>{{ $detail->rest_start2?->format('H:i') }}</span>
                        <span>{{ $detail->rest_end2?->format('H:i') }}</span>
                    </td>
                </tr>
                <tr class="stamp-correction__row">
                    <th class="stamp-correction__heading">備考</th>
                    <td class="stamp-correction__item">
                        <span>{{ $detail->reason }}</span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">承認</button>
        </div>
    </form>
</div>
@endsection