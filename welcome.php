<?php
session_start();

// Check if the user is not logged in, then redirect to the login page
if (!isset($_SESSION['userlogin'])) {
    header("Location: index.php");
    exit();
}

// Retrieve username
$username = $_SESSION['userlogin'];
$role = $_SESSION['userRole'];
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>

h1, div {
    position: relative;
    z-index: 10; /* This ensures that the header and the div containing the paragraphs appear on top of the slideshow. */
}


/*Background Slideshow*/
body, html {
  height: 100%;
  margin: 0;
  padding: 0;
  overflow: hidden;  /* Hide overflow to ensure only part of the container is visible */
}

@keyframes slide {
    0%, 20% { left: 0%; }
    25%, 45% { left: -100%; }
    50%, 70% { left: -200%; }
    75%, 95% { left: -300%; }
    100% { left: 0%; }  /* This will return it to the start */
}

#slideshow {
    width: 400%;  /* 4 images side by side */
    height: 100%;
    position: absolute;  /* Use absolute instead of relative */
    animation: slide 16s infinite;
    top: 0;
    left: 0;
    -webkit-transition: left 1s ease-in-out;
    -moz-transition: left 1s ease-in-out;
    -ms-transition: left 1s ease-in-out;
    -o-transition: left 1s ease-in-out;
    transition: left 1s ease-in-out;
}

/* Individual images */
#slideshow img {
    float: left;
    width: 25%;  /* Each image takes up 25% of the container */
    height: 100%;
    position: relative;  /* Set position to relative */
    transition: left 1s ease-in-out;
}


/* Nav bar color */
.topnav {
  margin-top: 0;
  top: 0;
  left: 0;
  right: 0;
  width: 100%;
  position: absolute;
}

/* Navigation bar properties */
.topnav a {
    float: left;
    color: #fff;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 17px;
    background-color: rgba(0, 0, 0, 0.2); /* A touch of transparency to the links too */
}

.topnav a:hover {
    background-color: rgba(255, 255, 255, 0.8); /* Light grayish on hover */
    color: black;
}

.topnav a.active {
    background-color: rgba(137, 137, 137, 0.8); /* Gray for the active link */
    color: white;
}

.topnav .icon {
  float:right;
}
/* Hamburger button color */
.topnav .icon i {
  color: white;
}
/* Dropdown button properties */
.dropbtn {
  float: right;
  font-size: 24px;
  background: none;
  border: none;
  cursor: pointer;
}

/* Dropdown container properties */
.dropdown {
  float: right;
  position: relative;
  display: inline-block;
}

/* Dropdown content (hidden by default) */
.dropdown-content {
  display: none;
  position: absolute;
background-color: rgba(0, 0, 0, 0.2);  min-width: 160px;
  z-index: 1000;
  right: 0;
}

/* Dropdown content links */
.dropdown-content a {
  color: white;  /* The color you desire */
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  background-color: rgba(0, 0, 0, 0.2);
  float: none;
}

/* Dropdown content links on hover */
.dropdown-content a:hover {
  background-color: rgba(0, 0, 0, 0.2);
}

/* Show the dropdown menu when the dropdown button is clicked */
.show {
  display: block;
}




// Logout button alignment
.logout-form {
  float: right;
  margin-right: 20px; /* Adjust the margin as needed */
}

.logout-btn {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 17px;
  color: white;
}

.logout-btn:hover {
  background-color: #ddd;
  color: black;
}

</style>
</head>
<body>
<div id="slideshow">
    <img src="image/banff.jpg" alt="Banff">
    <img src="image/glacier.jpg" alt="Glacier">
    <img src="image/washington.jpg" alt="Washington">
    <img src="image/ranier.jpg" alt="Ranier">
</div>



    <div class="dropdown">
<button class="dropbtn" onclick="toggleDropdown(event)">☰</button>
        <div class="dropdown-content" id="myDropdown">
            <a href="#news">News</a>
            <a href="#contact">Contact</a>
            <a href="index.php">Index</a>
            <a href="#about">About</a>
            <a href="#profile">Profile</a>
            <a href="javascript:void(0);" onclick="logout()">Logout</a>  
        </div>
    </div>
</div>


<form id="logoutForm" method="post" action="logout.php">

<script>

// Logout function
function logout() 
{
  var confirmation = confirm("Are you sure you want to logout?");
  
  if (confirmation) 
  {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'logout.php', true); 
    xhr.onreadystatechange = function () 
    {
      if (xhr.readyState === 4 && xhr.status === 200) 
      {
        window.location.href = 'index.php'; // Redirect to index after logout
      }
    };
    xhr.send();
  }
}

var dropdownVisible = false;

function toggleDropdown() {
    var dropdown = document.getElementById("myDropdown");
    
    if (dropdownVisible) {
        dropdown.style.display = "none";
    } else {
        dropdown.style.display = "block";
    }
    
    dropdownVisible = !dropdownVisible;
}

function toggleDropdown(event) {
    var dropdown = document.getElementById("myDropdown");
    
    if (dropdownVisible) {
        dropdown.style.display = "none";
    } else {
        dropdown.style.display = "block";
    }
    
    dropdownVisible = !dropdownVisible;

    event.stopPropagation(); // Add this line to prevent event bubbling
}

document.addEventListener('click', function() {
    if (dropdownVisible) {
        document.getElementById("myDropdown").style.display = "none";
        dropdownVisible = false;
    }
});

</script>

<h1 style="text-align: center;">Welcome, <?php echo $role . " " . $username; ?></h1>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

</body>
</html>
