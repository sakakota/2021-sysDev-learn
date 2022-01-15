<?php
// DB接続
$dbh = new PDO('mysql:host=mysql;dbname=techc', 'root', '');

if(!empty($_POST['email']) && !empty($_POST['password'])){
  //
  $pre = 'SELECT * FROM users WHERE email = :email ORDER BY id DESC LIMIT 1';
  $select_sth = $dbh->prepare($pre);
  $select_sth->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $select_sth->execute();
  $user = $select_sth->fetch();

  if(empty($user)){
    // Errorリダイレクト
    header("HTTP/1.1 302 Found");
    header("Location: ./login.php?error=1");
    return;
  }
  
  $correct_password = password_vertify($_POST['password'], $user['password']);

  if(!$correct_password)  
    // Errorリダイレクト
    header("HTTP/1.1 302 Found");
    header("Location: ./login.php?error=1");
    return;
  }

  session_start();
  // セッションに会員IDを設定
  $_SESSION['login_user_id'] = $user['id'];

  // ここまででログイン完了
  header("HTTP/1.1 302 Found");
  header("Location: ./login_finish.php");
  
}
?>

<h1>ログイン</h1>

<!-- ログインフォーム -->
<form method="POST">

<label>
メールアドレス：<input type="email" name="email">
</label>
<br>
<label>
パスワード：<input type="password" name="password">
</label>
<br>
<button type="submit">ログイン</button>

</form>
<?php if(!empty($_GET['error'])): // エラー判定 ?>
<div style="color:red">
  メールアドレス、もしくはパスワードが違います。
</div>
<?php endif; ?>

<p>登録がまだの方は<a href="./signup.php">こちら</a>から</p>
