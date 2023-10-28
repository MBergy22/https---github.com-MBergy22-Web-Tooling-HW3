<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <link href="css/bootstrap.min.css" rel="stylesheet">

<script src="http://code.jquery.com/jquery-3.6.0.min.js"></script>
<style type="text/css">
  /*Importing Google Font*/
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
.forget-password
  {
    text-align: right;
    margin-top: -15px;  
  }

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body 
{
  background: linear-gradient(#0FCACA, #D629AC);
}

::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  color: black;
}
::-moz-placeholder { /* Firefox 19+ */
  color: black;
}
:-ms-input-placeholder { /* IE 10+ */
  color: black;
}
:-moz-placeholder { /* Firefox 18- */
  color: black;
}

.wrapper {

  max-width: 350px;
  min-height: 600px;
  margin: 80px auto;
  padding: 50px 30px 30px 20px;
  background-color: #fff;
  border-radius: 0px;
}
 
.logo {

  width: 80px;
  margin: auto;
}

.logo img {

  width: 100%;
  height: 80px;
  object-fit: cover;
  border-radius: 50%;
  margin: auto;
  display: block;
}

.wrapper .name {

  font-weight: 600;
  font-size: 1.4rem;
  letter-spacing: 1.3px;
  padding-left: 10px;
  color: #000;
}

.wrapper .form-field input {
  width: 100%;
  display: block;
  border: none;
  outline: none;
  background: none;
  font-size: 1.2rem;
  color: #000;
  padding: 10px 15px 10px 10px;
/* border: 1px solid red; */
}

.wrapper .form-field {
  border-bottom: 2px solid #000;
  
}

.wrapper .form-field .fas {
  color: #000;
}

.wrapper .btn {
  box-shadow: none;
  width: 100%;
  height: 40px;
  background-color: #03a9f4;
  color: #000;
  border-radius: 25px;
  letter-spacing: 1.5px;

}


.wrapper .btn:hover {
  background-color: #039be5;
}

.wrapper a {
  text-decoration: none;
  font-size: 0.8rem;
  color: #03a9f4;

}

.wrapper a:hover {
  color: #039be5;
}

.error {
    color: red;
    font-size: 0.9rem;
    margin: 5px 0;
}
</style>

<!-- Javascript, Jquery, and Ajax for checking username availability -->
<script>
    function checkEmailAvailability()
    {
        $('#loaderIcon').show();
        jQuery.ajax({
            url: "emailAvailability.php",
            data: "userEmail=" + $('#userEmail').val(),
            type: "POST",

            success: function(data)
            {
                $("#email-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
    console.log("AJAX error: ", textStatus, errorThrown);
}

        });
    }

    function checkUsernameAvailability()
    {
        $('#loaderIcon').show();
        jQuery.ajax({
            url: "usernameAvailability.php",
            data: "userName=" + $('#userName').val(),
            type: "POST",

            success: function (data)
            {
                $("#username-availability-status").html(data);
                $("#loaderIcon").hide();
            },

            error: function()
            {

            }

        });
    }
</script>

  <title>Sign up Form</title>
              

</head>
<body>
  <div class="wrapper">
    <div class="logo">
      <img src="image/add-user.png" alt="">
    </div>                          
        <div class="text-center mt-4 name">
          Sign up Page
        </div>

<?php


$userName = "";
$userEmail = "";  
$userPassword = "";
$userNamePattern = '/^[a-zA-Z0-9_]{3,20}$/';
$emailPattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/';
$passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,20}$/';
$userNameError = "";
$emailError = "";
$passwordError = "";

if (isset($_POST['signup'])) {
    $userName = $_POST['userName'];
    $userPassword = $_POST['password'];
    $userEmail = $_POST['userEmail'];

    if (!preg_match($userNamePattern, $userName)) {
        $userNameError = "Invalid Username";
    }
    if (!preg_match($emailPattern, $userEmail)) {
        $emailError = "Invalid Email";
    }
    if (!preg_match($passwordPattern, $userPassword)) {
        $passwordError = "Invalid Password";
    }

    //include database connection
    include('config/db.php');

            $options = ['cost'=>12];
            $hashedpass = password_hash($userPassword, PASSWORD_BCRYPT, $options);

            $query = "SELECT * FROM userdata WHERE (UserName = ? || UserEmail = ?)";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $userName);
            $stmt->bindParam(2, $userEmail);
            $stmt->execute();
            $results = $stmt->fetchALL(PDO::FETCH_OBJ);

            if($stmt->rowCount() == 0)  
            {
                if(preg_match($userNamePattern, $userName) && preg_match($emailPattern, $userEmail))
                {
                    
                    // Inserting into the database
                    $query = "INSERT INTO userdata SET UserName=?, UserEmail=?, LoginPassword=?";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(1, $userName, PDO::PARAM_STR);
                    $stmt->bindParam(2, $userEmail, PDO::PARAM_STR);
                    $stmt->bindParam(3, $hashedpass, PDO::PARAM_STR);
                    $stmt->execute();
                }
                else
                {
                    if(!preg_match($userNamePattern, $userName)) {
                        //echo "Invalid Username<br>";
                        $userNameError= "Invalid Username";
                    }
                    if(!preg_match($emailPattern, $userEmail)) {
                        // echo "Invalid Email<br>";
                        $emailError = "Invalid Email";
                    }
                }
            }
            else
            {
                echo "Username or Email already exists.";
            }
        }
    ?>

<form class="p-3 mt-3" method="post">

<!-- ... your previous code ... -->

<form class="p-3 mt-3" method="post">

<!-- Username Field -->
<div class="form-field">
    <div class="d-flex align-items-center">
        <span class="far fa-user"></span>
        <input type="text" name="userName" id="userName" onblur="checkUsernameAvailability()" placeholder="Username">
        <span id ="username-availability-status" style="font-size: 12px;"></span>
    </div>
</div>
<div class="error"><?php echo $userNameError; ?></div>

<!-- Email Field -->
<div class="form-field">
    <div class="d-flex align-items-center">
        <span class="far fa-envelope"></span>
        <input type="text" name="userEmail" id="userEmail" onblur="checkEmailAvailability()" placeholder="Email">
        <span id ="email-availability-status" style="font-size: 12px;"></span>
    </div>
</div>
<div class="error"><?php echo $emailError; ?></div>

<!-- Password Field -->
<div class="form-field">
    <div class="d-flex align-items-center">
        <span class="fas fa-key"></span>
        <input type="password" name="password" id="pwd" placeholder="Password">
    </div>
</div>
<div class="error"><?php echo $passwordError; ?></div>


<div class="text-center fs-6">
    <a href="#">Forget password?</a> or <a href="login.php">Sign in</a>
</div>


<script src="js/bootstrap.bundle.min.js"></script>
<script type="bootstrap.min.css.map"></script>


</body>

</html>