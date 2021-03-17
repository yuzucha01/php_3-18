
<?php

//入力しているかどうかのチェック
if(
    !isset($_POST["email"]) || $_POST["email"]=="" ||
    !isset($_POST["password"]) || $_POST["password"]==""
){
    header("Location: index.html");
    exit;
}

//POSTデータの取得
$email  = $_POST["email"];
$password  = $_POST["password"];

// エラー処理
try {
    $pdo = new PDO('mysql:dbname=gs_db;host=localhost;charset=utf8','root','root');
    echo "接続OK！";
  } catch (PDOException $e) {
    echo 'DB接続エラー！: ' . $e->getMessage();
  }

//データベース内のメールアドレスを取得
$stmt = $pdo->prepare("SELECT * from gs_an_table where email = ?");
$stmt->execute([$email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

    //データベース内のメールアドレスと重複していない場合
        if (!isset($row['email'])) {
            echo 'メールアドレス又はパスワードが間違っています。';
            return false;
        }

        //パスワードが合うか調べる
        if (password_verify($password, $row['password'])) {
        session_regenerate_id(true);
        //session_idを新しく生成し、置き換える

        $_SESSION['EMAIL'] = $row['email'];
        header("Location: top-page.html");
        exit;  // 処理終了

        } else {
        // 認証失敗
        header("Location: miss-login.html");
        exit;
        }
?>