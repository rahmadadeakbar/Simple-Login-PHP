<?php
//membutuhkan file config
require_once "config.php";
//Tentukan variabel dan inisialisasi dengan nilai kosong
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
//Memproses data formulir saat formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Silahkan mengisi username.";
    } else {
        // menyiapkan select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // mengikat variable ke statement yang dipersiapkansebagai parameter
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameter
            $param_username = trim($_POST["username"]);
            // usaha untuk mengeksekusi statement yangdipersiapkan
            if (mysqli_stmt_execute($stmt)) { /* menyimpan hasil */
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Username sudah ada yang punya.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "maaf! Ada sesuatu yang salah. Mohon mencoba lagi setelah ini.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Validasi password
    if (empty(trim($_POST["password"]))) {
        $password_err = " Silahkan mengisikan password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = " Password harus berisi paling sedikit
    6 character.";
    } else {
        $password = trim($_POST["password"]);
    }
    // Validasi confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = " Silahkan confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = " Password tidak cocok.";
        }
    } // Periksa kesalahan input sebelum insert ke database
    if (
        empty($username_err) && empty($password_err) &&
        empty($confirm_password_err)
    ) {
        //mempersiapkan insert statement
        $sql = "INSERT INTO users (username, password) VALUES
    (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Ikat variabel ke pernyataan yang disiapkansebagai parameter
            mysqli_stmt_bind_param(
                $stmt,
                "ss",
                $param_username,
                $param_password
            );
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // membuat password hash
            // berusaha mengeksekusi statement yang sudah dipersiapkan
            if (mysqli_stmt_execute($stmt)) {
                // Redirect ke halaman login page
                header("location: login.php");
            } else {
                echo " maaf! Ada sesuatu yang salah. Mohon mencoba
    lagi setelah ini.";
            }
            // statement penutup
            mysqli_stmt_close($stmt);
        }
    }
    // koneksi ditutup
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
        <h2>Sign Up</h2>
        <p>Silahkan isi form ini untuk membuat account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>

                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">

                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password;
                                                                                    ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Apakah sudah memiliki account? <a href="login.php">Login disini</a>.</p>
        </form>
    </div>
</body>

</html>