<h1>模擬案件　勤怠管理アプリ</h1>

<br>
<h2>環境構築</h2>
<br>

<h3>Docker　ビルド</h3>



１, git clone リンク



２, docker-compose up -d --build
<br>


<h3>laravel環境構築</h3>
<br>

１,docker-compose exec php bash


２,composer install


３, cp .env.example .env


４， .envファイルの一部を以下のように編集<br>
DB_CONNECTION=mysql<br>
DB_HOST=mysql<br>
DB_PORT=3306<br>
DB_DATABASE=laravel_db<br>
DB_USERNAME=laravel_user<br>
DB_PASSWORD=laravel_pass<br>


５．php artisan key:generate


６，php artisan migrate


７，php artisan db:seed


<h3>github環境構築</h3>
<br>

１，git clone git@github.com:kirin-nagi/mogianken-kintai.git
<br>

２，cd リポジトリ名
<br>

３，git remote set-url origin git@github.com:kirin-nagi/リポジトリ名.git
<br>

４，git add .
<br>

５，git commit -m "リモートリポジトリ変更"
<br>

６，git push origin main
<br>




<h2>user/adminのログイン用初期データ</h2>
<br>
＜管理者＞<br>
●メールアドレス：admin@example.com<br>
●パスワード：　password<br>
<br>

＜一般ユーザー＞<br>
西　伶奈<br>
●メールアドレス：reina.n@coachtech.com<br>
●パスワード：password<br>
<br>


山田　太郎<br>
●メールアドレス：taro.y@coachtech.com<br>
●パスワード：password<br>
<br>


増田　一斉<br>
●メールアドレス：issei.m@coachtech.com<br>
●パスワード：password<br>
<br>


山本　敬吉<br>
●メールアドレス：keikichi.y@coachtech.com
<br>
●パスワード：password<br>
<br>


秋田　朋美<br>
●メールアドレス：tomomi.a@coachtech.com
<br>
●パスワード：password<br>
<br>


中西　教夫<br>
●メールアドレス：norio.n@coachtech.com
<br>
●パスワード：password<br>
<br>



<h2>使用技術</h2>
<br>



１，laravel 8.83.8
<br>


２，PHP 8.1.33
<br>


３，mysql 　8.0.26

<h2>URL</h2>
<br>

・環境開発：　　http://localhost/
<br>

・phpMyAdmin:  http://localhost:8080/
<br>


<h2>ER図</h2>
<img width="1042" height="843" alt="模擬案件　勤怠　ER図" src="https://github.com/user-attachments/assets/e5252504-0049-4180-a798-7a01c966f869" />





