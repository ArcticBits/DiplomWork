<?php
session_start();
require '../db.php'; 
$key_path = '../encryption.key';
if (!file_exists($key_path)) {
    die('Encryption key not found.');
}
$key = file_get_contents($key_path);
if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
    die('Invalid key size.');
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if ($new_password !== $confirm_password) {
        $message = 'Пароли не совпадают.';
    } else {
        $new_password_hashed = sodium_crypto_pwhash_str(
            $new_password,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
        );

        try {
            $query = "SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW()";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $query = "UPDATE users SET password = :password, reset_token = NULL, reset_expires = NULL WHERE reset_token = :token";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':password', sodium_bin2hex($new_password_hashed));
                $stmt->bindParam(':token', $token);
                $stmt->execute();
                header('Location: ../Registration/log.html');
                exit();
            } else {
                $message = 'Неверный или истекший токен.';
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
} else {
    if (isset($_GET['token'])) {
        $token = htmlspecialchars($_GET['token']);
    } else {
        die('Токен не предоставлен.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Сброс пароля</title>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      <?php if (!empty($message)) { ?>
        alert("<?php echo $message; ?>");
      <?php } ?>
    });
  </script>
</head>
<body>
  <section>
    <div class="form-box">
      <div class="form-value">
        <form id="resetPasswordForm" action="reset_password.php" method="post">
          <h2>Сброс пароля</h2>
          <input type="hidden" name="token" value="<?php echo $token; ?>">
          <div class="inputbox">
            <ion-icon name="lock-closed-outline"></ion-icon>
            <input type="password" id="new_password" name="new_password" required>
            <label for="new_password">Новый пароль</label>
          </div>
          <div class="inputbox">
            <ion-icon name="lock-closed-outline"></ion-icon>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <label for="confirm_password">Подтвердите новый пароль</label>
          </div>
          <button type="submit">Сбросить пароль</button>
        </form>
      </div>
    </div>
  </section>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
