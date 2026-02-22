<?php
    session_start();
    include 'includes/DB.php';
    include 'includes/Query.php';

    $Qobject = new Query;

    if(isset($_SESSION['type']))
    {
        header("location:index.php");
    }

    $message = '';

    if(isset($_POST["login"]))
    {

        $user_email = $_POST['user_email'];
        $query = "
            SELECT * FROM users 
            WHERE user_email = '$user_email'
        ";

        $result = $Qobject->select($query);
        $count = $Qobject->table_row_count($query);
        
        if($count > 0)
        {
            
            foreach($result as $row)
            {
                if($row['user_status'] == 1)
                {
                    if(password_verify($_POST["user_password"], $row["user_password"]))
                    {
                        
                        $_SESSION['type'] = $row['user_role'];
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['user_name'] = $row['user_name'];
                        header("location:index.php");
                                             
                    }
                    else
                    {
                        $message = "<label>Wrong Password</label>";
                    }
                }
                else
                {
                    $message = "<label>Your account is disabled, Contact Master</label>";
                }
            }
        }
        else
        {
            $message = "<label>Wrong Email Address</labe>";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MUTTYFEM SUPERMARKEt</title>
    <link rel="stylesheet" href="css/login.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css" integrity="sha512-usVBAd66/NpVNfBge19gws2j6JZinnca12rAe2l+d+QkLU9fiG02O1X8Q6hepIpr/EYKZvKx/I9WsnujJuOmBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
   
    
    <div class="global-container">
        <div class="card login-form">
            <div class="card-body">
                <h1 class="card-title text-center">LOGIN</h1>
                <div class="card-text">
                    <form action=""  method="post">
                        <?php echo $message; ?>
                        <div class="form-group">
                            <label for="user_email">Email Address <i class="fas fa-at"></i></label>
                            
                            <input type="email" name="user_email" id="user_email" class="form-control" placeholder="Enter Email">
                        </div>
                        
                        <div class="form-group">
                            <label for="user_password">Password <i class="fas fa-lock"></i></label>
                            <input type="password" name="user_password" id="user_password" class="form-control" placeholder="Enter Password"> 
                            <i class="fas fa-eye" id="password-icon"></i>
                            <i class="fas fa-eye-slash  hide-icon" id="hide-password-icon"></i>
                        </div>
                        <input type="submit" name="login" value="Sign In" class="btn btn-primary btn-block">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--form method="post"  class="box">
        
        <h1>Login</h1>
        <input type="text" name="user_email" id="" placeholder="Email">
        <input type="password" name="user_password" id="user_password" placeholder="Password"> <i class="fas fa-eye" id="password-icon"></i>
        <i class="fas fa-eye-slash  hide-icon" id="hide-password-icon"></i>
        <input type="submit" name="login" value="Login">
    </form-->
   
   
    <script>
        $('#password-icon').click(function(){
            $('#user_password').attr("type", "text");
            
            $(this).addClass('hide-icon');
            $('#hide-password-icon').removeClass('hide-icon');
            console.log("password");
        });
        
        $('#hide-password-icon').click(function(){
            $('#user_password').attr("type", "password");
            
            $(this).addClass('hide-icon');
            $('#password-icon').removeClass('hide-icon');
            console.log("password");
        });

    </script>
</body> 
</html>