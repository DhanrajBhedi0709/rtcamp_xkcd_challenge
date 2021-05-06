<?php

    require_once 'DBConnection.php';

    $alert = '';

    if(isset($_POST['email']) && !empty($_POST['email'])){
        $email = htmlspecialchars($_POST['email']);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $hash = md5($randomString);
        $conn = Database::getInstance()->getConnection();
        if(!$conn){
            die('Connection not Established');
        }
        $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO tbl_user (email, hash) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hash);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $to = $email;
                $subject = "Verification";
                $url = "http://xkcd.ictmu.in/verify.php?email=$email&hash=$hash";
                $msg = "
                    <html>
                        <head>
                            <title>Verification</title>
                        </head>
                        <body>
                            Verification Link<br />
                            <a target='_blank' href=$url>Click to Verify</a>
                        </body>
                    </html>
                ";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: noreply@ictmu.in" . "\r\n";

                mail($to, $subject, $msg, $headers);

                $alert = 'Verification Mail sent to your email address.';

            } else {
                echo "Error Occured";
            }
        } else {
            $alert = "Email already registered";
        }
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>XKCD Challenge</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
    <?php
        if(!empty($alert)){
            ?>
        <div class="alert alert-primary" role="alert">
            <?php echo $alert; ?>
        </div>
    <?php

        }

    ?>
        <div class="container">
            <h1>Subscribe for XKCD Images</h1>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" name="email" placeholder="Enter email">
                    <small class="form-text text-muted">We"ll never share your email with anyone else.</small>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        <div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>