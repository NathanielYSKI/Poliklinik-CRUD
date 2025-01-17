<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Pilih Login</title>

    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./assets/compiled/css/app.css" />
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css" />
    <link rel="stylesheet" href="./assets/compiled/css/auth.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="login.php"><img src="./assets/compiled/svg/POLI.svg" alt="Logo" /></a>
                    </div>
                    <h1 class="auth-title">Pilih Login</h1>
                    <p class="auth-subtitle mb-5">
                        Silakan pilih untuk masuk sebagai Dokter atau Pasien.
                    </p>

                    <div class="d-grid gap-3">
                        <a href="login.php" class="btn btn-primary btn-lg shadow-lg">Login sebagai Dokter</a>
                        <a href="login_pasien.php" class="btn btn-success btn-lg shadow-lg">Login sebagai Pasien</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right"></div>
            </div>
        </div>
    </div>
</body>

</html>
