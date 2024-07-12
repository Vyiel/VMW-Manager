<?php 
	require 'db_conn.php';

	session_start();

	if (empty($_SESSION['user_id']) && empty($_SESSION['user_email'])) 
	{
		header("Location: login");
	}

?>


<?php

if(isset($_POST['submit']) && $_POST['submit'] == "add")
{
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
    $c_password = htmlspecialchars($_POST['c_password'], ENT_QUOTES);


    if (sha1($password) === sha1($c_password)) 
    {
        $qry = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
        $stmt= $conn->prepare($qry);
        $stmt->execute([$name, $email, sha1($password)]);

        if($stmt)
        {
            echo "<script>
              alert('New Addition Recorded');
              window.location = 'index';
                </script>"; 
        }
        else
        {
            echo "ERROR";
        }
    }
    else
    {
        echo "<script>
              alert('Passwords didnt match!');
              window.location = 'adduser';
                </script>"; 
    }
    
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Add Users </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href='https://fonts.googleapis.com/css?family=Raleway:200,400,800' rel='stylesheet' type='text/css'><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

   
   <div class="container mt-3" style="align-items: center;">
  <div class="mt-2 p-1 bg-dark text-white rounded" >
    <h1 style="text-align: center;">Add Users</h1> 
  </div>
  <form style="align-content: center;" action="" method="POST">
  <div class="form-group row" style="align-content: center;">
    <p style="text-align: center;"> - </p>
    <div class="col-sm-12">
        <input type="text" class="form-control" name="name" placeholder="Enter full user name" required>
        <input type="email" class="form-control" name="email" placeholder="Enter email address" required>
        <input type="password" class="form-control" name="password" placeholder="Password" required>
        <input type="password" class="form-control" name="c_password" placeholder="Confirm Password" required>
        <center> <button type="submit" class="btn btn-secondary" name="submit" value="add" style="text-align: center;"> Add </button> </center>
    </div>
  </div>

  </div>
</form>
</div>




</body>
</html>


