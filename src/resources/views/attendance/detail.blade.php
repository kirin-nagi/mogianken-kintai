@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection


@section('content')
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
                    @if('detail')
                    <p class="top-item">{{ $detail->work_date->format('Y') }}年</p>
                    <p class="bottom-item">{{ $detail->work_date->format('n月j日') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">出勤・退勤</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="time" name="start_work" value="{{ old('start_work', $attendance->start_work) }}" />
                    <span class="time-separator">～</span>
                    <input type="time" name="end_work" value="{{ old('end_work', $attendance->end_work) }}" />
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
                    <input type="time" name="rest_start" value="{{ old('rest_start', $attendance->rest_start) }}" />
                    <span class="time-separator">～</span>
                    <input type="time" name="rest_end" value="{{ old('rest_end', $attendance->rest_end) }}" />
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
                    <input type="time" name="rest_start2" value="{{ old('rest_start2', $attendance->rest_start2) }}" />
                    <span class="time-separator">～</span>
                    <input type="time" name="rest_end2" value="{{ old('rest_end2', $attendance->rest_end2) }}" />
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
        <div class="form__button">
            @if($canEdit)
            <button class="form__button-submit" type="submit">修正</button>
            @else
            <span class="form__button-item">＊承認待ちのため修正できません</span>
            @endif
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded',() => {
        function updateClock(){
            const now = new Date();

            document.getElementById('date').textContent =
            `${now.getFullYear()}年${now.getMonth() + 1}月${now.getDate()}日`;
        }

        updateClock();
        setInterval(updateClock, 1000);
    });
</script>
@endsection