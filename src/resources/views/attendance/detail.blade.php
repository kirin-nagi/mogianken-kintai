@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
@if($viewState === 'pending')
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
                    <p class="top-item">{{ $approval->detail->work_date->format('Y') }}年</p>
                    <p class="bottom-item">{{ $approval->detail->work_date->format('n月j日') }}</p>
                </td>
            </tr>
            <tr class="stamp-correction__item">
                <th class="heading-item">出勤・退勤</th>
                <td class="date-time">
                    <p class="top-item">{{ $approval->detail->start_work->format('H:i') }}</p>
                    <span class="time-separator">～</span>
                    <p class="bottom-item">{{ $approval->detail->end_work->format('H:i') }}</p>
                </td>
            </tr>
            <tr class="stamp-correction__item">
                <th class="heading-item">休憩</th>
                <td class="date-time">
                    <p class="top-item">{{ $approval->detail->rest_start->format('H:i') }}</p>
                    <span class="time-separator">～</span>
                    <p class="bottom-item">{{ $approval->detail->rest_end->format('H:i') }}</p>
                </td>
            </tr>
            <tr class="stamp-correction__item">
                <th class="heading-item">休憩２</th>
                <td class="date-time">
                    <p class="top-item">{{ $approval->detail->rest_start2?->format('H:i') }}</p>
                    @if($approval->detail->rest_start2 && $approval->detail->rest_end2)
                    <span class="time-separator">～</span>
                    @endif
                    <p class="bottom-item">{{ $approval->detail->rest_end2?->format('H:i') }}</p>
                </td>
            </tr>
            <tr class="stamp-correction__item">
                <th class="heading-item">備考</th>
                <td class="text-item">
                    <p class="detail-reason">{{ $approval->detail->reason }}</p>
                </td>
            </tr>
        </table>
    </div>
<div>
    @else
<div class="detail-form__content">
    <div class="detail__heading">
        <p class="detail__heading-item">勤怠詳細</p>
    </div>
    <form action="{{ route('attendance.detail', $attendance->id) }}" method="post" class="form">
        @csrf
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">名前</span>
            </div>
            <div class="form__group-content">
                <p class="user-name">{{ $attendance->user->name }}</p>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">日付</span>
            </div>
            <div class="form__group-content">
                <div id="date" class="date">
                    <p class="top-item">{{ $attendance->work_date->format('Y') }}年</p>
                    <p class="bottom-item">{{ $attendance->work_date->format('n月j日') }}</p>
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">出勤・退勤</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="time" name="start_work" value="{{ old('start_work', $attendance->start_work ? $attendance->start_work->format('H:i') : '' ) }}" />
                    <span class="time-separator">～</span>
                    <input type="time" name="end_work" value="{{ old('end_work', $attendance->end_work ? $attendance->end_work->format('H:i') : '' ) }}" />
                </div>
                <div class="form__error">
                    @error('start_work') {{ $message }}@enderror
                    @error('end_work') {{ $message }}@enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">休憩</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="time" name="rest_start" value="{{ old($attendance->rest_start ?$attendance->rest_start->format('H:i') : '' ) }}" />
                    <span class="time-separator">～</span>
                    <input type="time" name="rest_end" value="{{ old($attendance->rest_end ?$attendance->rest_end->format('H:i') : '' ) }}" />
                </div>
                <div class="form__error">
                    @error('rest_start') {{ $message }}@enderror
                    @error('rest_end') {{ $message }}@enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">休憩2</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="time" name="rest_start2" value="{{ old($attendance->rest_start2 ?$attendance->rest_start2->format('H:i') : '' )}}" />
                    <span class="time-separator">～</span>
                    <input type="time" name="rest_end2" value="{{ old($attendance->rest_end2 ?$attendance->rest_end2->format('H:i') : '' )}}" />
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">備考</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--textarea">
                    <textarea name="description">{{ old('description', $attendance->description) }}</textarea>
                </div>
                <div class="form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        @endif
        <div class="form__button">
            @if($approval && $approval->status === 0)
            <span class="form__button-item">＊承認待ちのため修正できません</span>
            @else
            <button class="form__button-submit" type="submit">修正</button>
            @endif
        </div>
    </form>
</div>
@endsection