<!-- 勤怠詳細画面 -->
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
                <span class="form__label--item">名前</div>
            </div>
            <div class="form__group-content">
                <p class="user-name">{{ $attendance->user->name }}</p>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">日付</div>
            </div>
            <div class="form__group-content">
                <div id="date" class="date"></div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">出勤・退勤</div>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="time" name="work-time" />
                    <input type="time" name="work-time" />
                </div>
                <div class="form__error">
                    @error('work-time')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">休憩</div>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="time" name="rest-time" />
                    <input type="time" name="rest-time" />
                </div>
                <div class="form__error">
                    @error('rest-time')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">休憩2
                </div>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="time" name="rest-time" />
                    <input type="time" name="rest-time" />
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">備考</div>
            </div>
            <div class="form__group-content">
                <div class="form__input--textarea">
                    <textarea name="description"></textarea>
                </div>
                <div class="form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">修正</button>
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