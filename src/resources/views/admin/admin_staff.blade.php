<!-- スタッフ一覧（管理者） -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff.css') }}">
@endsection

@section('content')
<div class="staff-list__content">
    <div class="staff-list__heading">
        <p class="staff-list__heading-item">スタッフ一覧</p>
    </div>
    <table>
        <tr>
            <td colspan="3" class="empty-row">
                <div class="empty-columns">
                    <span>名前</span>
                    <span>メールアドレス</span>
                    <span>月次勤怠</span>
                </div>
            </td>
        </tr>
        @foreach($users as $user)
        <tr>
            <td colspan="3" class="empty-row">
                <div class="empty-columns">
                    <span>
                        {{ $user->name }}
                    </span>
                    <span>
                        {{ $user->email }}
                    </span>
                    <span>
                        <a href="{{ route('admin.attendanceList', $user->id) }}" class="detail-item">詳細</a>
                    </span>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection