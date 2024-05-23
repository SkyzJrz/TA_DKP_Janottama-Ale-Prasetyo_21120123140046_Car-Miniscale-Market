<?php
session_start();

class LoginSystem {
    private $users = [
        'admin' => '12345',
        'user2' => 'password2'
    ];

    private $maxAttempts = 3;

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['attempts'])) {
                $_SESSION['attempts'] = 0;
            }

            $username = $_POST['username'];
            $password = $_POST['password'];

            $valid_user = false;
            foreach ($this->users as $user => $pass) {
                if ($user === $username && $pass === $password) {
                    $valid_user = true;
                    break;
                }
            }

            if ($valid_user) {
                $_SESSION['username'] = $username;
                $_SESSION['attempts'] = 0;

                if (isset($_POST['remember'])) {
                    setcookie('username', $username, time() + (86400 * 30), "/");
                }

                header('Location: dashboard.php');
                exit;
            } else {
                $_SESSION['attempts']++;
                if ($_SESSION['attempts'] > $this->maxAttempts) {
                    $_SESSION['error'] = 'Maximum login attempts exceeded.';
                    $_SESSION['max_attempts_reached'] = true; 
                } else {
                    $_SESSION['error'] = 'Invalid username or password';
                }
                header('Location: login.php');
                exit;
            }
        }
    }

    public function handleMaxAttempts() {
        if (isset($_SESSION['max_attempts_reached']) && $_SESSION['max_attempts_reached']) {
            $_SESSION['attempts'] = 0;
            unset($_SESSION['max_attempts_reached']);
        }
    }

    public function displayError() {
        $error = '';
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        return $error;
    }
}

//OOP
$loginSystem = new LoginSystem();
$loginSystem->handleLogin();
$loginSystem->handleMaxAttempts();
$error = $loginSystem->displayError();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login CarMiniscaleMarket</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="wrapper">
        <form action="login.php" method="POST">
            <h2>Login</h2>
            <div class="error-message <?php if ($error) echo 'show'; ?>" id="error-message"><?php echo $error; ?></div>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bx-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bx-lock'></i>
            </div>
            <div class="remember-forget">
                <label><input type="checkbox" name="remember"> Remember Me</label>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
