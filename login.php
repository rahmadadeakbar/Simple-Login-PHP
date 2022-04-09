<?php
// inisialisasi session
session_start();
// cek apakah user sudah login, apabila sudah maka akan redirect ke welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}
// Include config file
require_once "config.php";
// mendefinisikan variables dan inisialisasi nilai kosong
$username = $password = "";
$username_err = $password_err = "";
// memproses data apabila form sudah submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah username tidak diisi
    if (empty(trim($_POST["username"]))) {
        $username_err = "Silahkan isi username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Cek apakah password tidak diisi
    if (empty(trim($_POST["password"]))) {
        $password_err = "silahkan isi password.";
    } else {
        $password = trim($_POST["password"]);
    }
    // menvalidasi credentials
    if (empty($username_err) && empty($password_err)) {
        // menyiapkan select statement
        $sql = "SELECT id, username, password FROM users WHERE
    username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Ikat variabel ke pernyataan yang disiapkan sebagai parameter
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameter
            $param_username = $username;
            // Mencoba untuk mengeksekusi pernyataan yang telah disiapkan
            if (mysqli_stmt_execute($stmt)) {
                // menyimpan hasil atau result
                mysqli_stmt_store_result($stmt);
                // Cek apakah username sudah ada Apabila sudah ada maka menverifikasi password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // mengikat variables hasil
                    mysqli_stmt_bind_result(
                        $stmt,
                        $id,
                        $username,
                        $hashed_password
                    );
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // bila Password sudah benar, mulai untu new session 
                            session_start();
                            // menyimpan data di session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            // Redirect user ke welcome page
                            header("location: welcome.php");
                        } else {
                            // display error message seandainya password tidak valid
                            $password_err = "password yang anda masukkan tidak valid.";
                        }
                    }
                } else {
                    // Display error message apabila username tidak ada
                    $username_err = "tidak ada account yang ditemukan dengan username tersebut.";
                }
            } else {
                echo "Maaf, ada sesuatu yang salah. Silahkan coba beberapa saat lagi.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Silahkan isi data pribadi Anda untuk login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>

                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">

                <span class="help-block"><?php echo $username_err;
                                            ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err;
                                            ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Belum memiliki account? <a href="register.php">Daftar sekarang juga </a>.</p>
        </form>
    </div>
</body>

</html>