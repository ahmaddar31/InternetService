<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="Style1.css">
    <title>Login - CoDelta Technologies</title>
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Roboto', sans-serif;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .header .brand h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }

        .header .brand p {
            margin: 0;
            font-size: 1rem;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .alert {
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="brand">
                <h1>CoDelta Technologies</h1>
                <p>Empowering Businesses with Technology</p>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <div class="card" style="width: 25rem; margin: 0 auto; padding: 20px;">
            <div class="card-header text-center bg-dark text-white">
                LOGIN
            </div>
            <div class="card-body">
                <?php
                if (isset($_GET['flag'])) {
                    if ($_GET['flag'] == 1) {
                        echo "<div class='alert alert-danger text-center'>Email or Password is wrong!</div>";
                    } elseif ($_GET['flag'] == 2) {
                        echo "<div class='alert alert-warning text-center'>Please login first!</div>";
                    }
                }
                ?>
                <form method="post" action="./loginAction.php">
                    <div class="form-group">
                        <label for="user">User</label>
                        <input type="email" class="form-control" id="user" name="user" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="Password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-4">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
