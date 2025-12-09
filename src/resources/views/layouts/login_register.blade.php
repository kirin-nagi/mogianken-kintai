<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login_register.css') }}">
    @yield('css')
</head>


<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__img" href="">
                    <img src='/storage/image/logo.svg' width="250">
                </a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>


</html>