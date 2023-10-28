<?php
session_start();

// Include DB connection
include('config/db.php');

function validateNotEmpty($value, $fieldName) {
    if (empty($value)) {
        return "$fieldName is required.";
    }
    return '';
}

function validateAlphanumeric($value, $fieldName) {
    if (!ctype_alnum($value)) {
        return "$fieldName must contain only numbers and letters.";
    }
    return '';
}

function validateEmailFormat($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }
    return '';
}

if (isset($_POST['login'])) {
    $userName = $_POST['userName'];
    $password = $_POST['password'];

    // Perform input validation for username and password
    $passwordError = validateNotEmpty($password, 'Password');

if (strpos($userName, '@') !== false) {
    // This means it's an email
    $emailError = validateEmailFormat($userName);
    if (!empty($emailError)) {
        $_SESSION['error'] = $emailError;
        header("Location: index.php");
        exit();
    }
} else {
    // This means it's a username
    $userNameError = validateNotEmpty($userName, 'Username');
    if (empty($userNameError)) {
        $userNameError = validateAlphanumeric($userName, 'Username');
    }
    if (!empty($userNameError)) {
        $_SESSION['error'] = $userNameError;
        header("Location: index.php");
        exit();
    }
}

    

    if (empty($passwordError)) {
        $passwordError = validateAlphanumeric($password, 'Password');
    }

    // Check if there are no validation errors
    if (empty($userNameError) && empty($passwordError)) {
        
        // Get data from the database, column names (basically means OR)
        $query = "SELECT UserName, UserEmail, LoginPassword, Role FROM userdata WHERE (UserName=:uname || UserEmail =:uemail)";

        $stmt = $con->prepare($query);
        $stmt->bindParam(':uname', $userName, PDO::PARAM_STR);
        $stmt->bindParam(':uemail', $userName, PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        if ($stmt->rowCount() > 0) {
            foreach ($results as $row) {
                $hashpass = $row->LoginPassword;
                $Role = $row->Role;
            }

            // Verifying Password and Role
            if (password_verify($password, $hashpass)) {
                $_SESSION['userlogin'] = $row->UserName;
                $_SESSION['userRole'] = $Role;

                // Redirect based on the role
                if ($Role == 'Admin') {
                    header("Location: welcome.php"); // Redirect to the admin page
                    exit();
                } elseif ($Role == 'User') {
                    header("Location: welcome.php"); // Redirect to the user page
                    exit();
                } elseif ($Role == 'SuperAdmin') {
                    header("Location: welcome.php"); // Redirect to the superadmin page
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Invalid login credentials.';
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = 'Invalid login credentials.';
            header("Location: index.php");
            exit();
        }
    } else {
        // Handle validation errors
        $_SESSION['error'] = 'Invalid login credentials.';
        header("Location: index.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        /*Importing Google Font*/
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        .forget-password {
            text-align: right;
            margin-top: -15px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(#0FCACA, #D629AC);
        }

        /* Placeholder text color */
        ::-webkit-input-placeholder {
            color: black;
        }

        ::-moz-placeholder {
            color: black;
        }

        :-ms-input-placeholder {
            color: black;
        }

        :-moz-placeholder {
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
    </style>
    <title>Login Form</title>
</head>

<body>

<script>
    <?php
    if (isset($_SESSION['error'])) {
        echo "document.querySelector('#userName').value = '$userName';"; // Preserve the entered username
        echo "document.querySelector('#pwd').value = '';"; // Clear the password field
    }
    ?>
</script>

<?php
if (isset($_SESSION['error'])) 
{
    echo "<div class='text-center mt-4 name' style='color:red;'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}
?>

<div class="wrapper">
    <div class="logo">
        <img src="image/6681204.png" alt="">
    </div>
    <div class="text-center mt-4 name">
        Sign in Page
    </div>
    <form class="p-3 mt-3" method="post">
        <div class="form-field d-flex align-items-center">
            <span class="far fa-user"></span>
            <input type="text" name="userName" id="userName" placeholder="Username or Email">
            <?php if (!empty($userNameError)) echo "<span style='color:red;'>$userNameError</span>"; ?>
        </div>
        <div class="form-field d-flex align-items-center">
            <span class="fas fa-key"></span>
            <input type="password" name="password" id="pwd" placeholder="Password">
            <?php if (!empty($passwordError)) echo "<span style='color:red;'>$passwordError</span>"; ?>
        </div>
        <button type="submit" class="btn mt-3" name="login">Login</button>
    </form>
    <div class="text-center fs-6">
        <a href="#">Forget password?</a> or <a href="signup.php">Sign up</a>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</div>
</body>

</html>
