<!-- 申請一覧（一般と管理。if文？）認証ミドルウェアで区別-->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stamp_list.css') }}">
@endsection

@section('content')
<div class="stamp-list__content">
    <div class="stamp-list__heading">
        <p class="stamp-list__heading-item">申請一覧</p>
    </div>
    <div class="stamp-list__inner">
        <div class="stamp-list__row">
                <a class="stamp-list__link">承認待ち</a>
                <a class="stamp-list__link">承認済み</a>
            </div>
        </div>
    </div>
    <table>
        <tr>
            <td colspan="6" class="empty-row">
                <div class="empty-columns">
                    <span>状態</span>
                    <span>名前</span>
                    <span>対象日時</span>
                    <span>申請理由</span>
                    <span>申請日時</span>
                    <span>詳細</span>
                </div>
            </td>
        </tr>
        @foreach($approvals as $approval)
        @php
        $loginUser = Auth::user();
        @endphp
        <tr>
            <td colspan="6" class="empty-row">
                <div class="empty-columns">
                    <span>
                        @if($approval->status === 0)
                        承認待ち
                        @else
                        承認済み
                        @endif
                    </span>
                    <span>{{ $approval->user->name }}</span>
                    <span>{{ $approval->targetdate->format('Y-m-d') }}</span>
                    <span>{{ $approval->reason }}</span>
                    <span>{{ $approval->created_at->format('Y-m-d') }}</span>
                    <span>
                        @if($loginUser->role === 1)
                        <a href="{{ route('stampCorrection', $approval->id) }}" class="detail-item">詳細</a>
                        @else
                        <a href="{{ route('attendance.detail', $approval->id) }}" class="detail-item">詳細</a>
                        @endif
                    </span>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection