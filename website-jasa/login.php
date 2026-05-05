<?php
require_once 'config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo1dbi.png">
    <title>Login - Jasa Pembuatan Website Profesional</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #FF653F;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 430px;
            padding: 20px;
        }

        .login-card {
            border: none;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .login-header {
            background: #ffffff;
            color: white;
            text-align: center;
            padding: 15px 20px;
        }


        .login-body {
            padding: 40px;
            background: white;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dc3545;
        }

        .btn-login {
            height: 50px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 16px;
        }

        .demo-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            font-size: 14px;
        }

        .brand-text {
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <div class="card login-card">

        <!-- Header -->
        <div class="login-header">
            <img src="img/logo1dbi.png" alt="logo" style="width: 100px;">
        </div>

        <!-- Body -->
        <div class="login-body">

            <h4 class="text-center mb-4 fw-bold">
                Login Account
            </h4>

            <!-- Error -->
            <?php if ($error): ?>
                <div class="alert alert-danger text-center">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST">

                <div class="mb-3">
                    <label class="mb-2">Username</label>
                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Masukkan username"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="mb-2">Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="btn w-100 btn-login" style="background-color: #FF653F;">
                    Login Sekarang
                </button>

            </form>

            <!-- Demo Info -->
            <div class="demo-info mt-4">
                <strong>Demo Login:</strong><br>
                Admin → <b>admin / admin</b><br>
                User → <b>user / user</b>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <small class="brand-text">
                    © 2026 DBI TECH — Website Profesional
                </small>
            </div>

        </div>

    </div>

</div>

</body>
</html>