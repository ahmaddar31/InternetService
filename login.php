<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./bootstrap-4.6.2-dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="Style1.css">
</head>

<body>
    <center>
        <?php
            if(isset($_GET['flag'])){
                if($_GET['flag']==1){
                    echo "<b>email or password is wrong!!</b>";
                }
                elseif($_GET['flag']==2){
                    echo "<script>alert('login before please')</script>";
                }
            }
        ?>
        <div class="card" style="width: 25rem;">
            <div class="card-header">
                 LOGIN
            </div>
            <form method="post" action="./loginAction.php">
                <div class="form-group">
                    <label>User: </label>
                    <input type="email" class="form-control" name="user" placeholder="User" required>
                </div>
                <div class="form-group">
                    <label>Password: </label>
                    <input type="password" class="form-control" name="Password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn" name="submit">Submit</button>
            </form>
        </div>
</body>

</html>