<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>


<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__img" href="">
                    <img src='/storage/image/logo.svg' width="250">
                </a>
                <ul class="header-nav">
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="/attendance">勤怠</a>
                        <a class="header-nav__link" href="/attendance/list">勤務一覧</a>
                        <a class="header-nav__link" href="/stamp_correction_request/list">申請</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"class="header-nav__link">ログアウト</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>


</html>