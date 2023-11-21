<?php 
	include 'db_conn.php';

	session_start();

	if (empty($_SESSION['user_id']) && empty($_SESSION['user_email'])) 
	{
		header("Location: login");
	}

?>

<?php


if(isset($_POST['remove']))
{

    $email = htmlspecialchars($_POST['remove'], ENT_QUOTES);
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 1)
    {
        $users = $stmt->fetch();
        $user_ID = $users['id'];

        $stmtd = $conn->prepare("DELETE FROM users WHERE id=?;");
        $stmtd->execute([$user_ID]);
        
        if($stmtd)
        {
            echo "<script>
              alert('User Removed!');
              window.location = 'users';
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
              alert('User is probably not in the Database!');
              window.location = 'users';
                </script>"; 
    }
    
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Users </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href='https://fonts.googleapis.com/css?family=Raleway:200,400,800' rel='stylesheet' type='text/css'><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

   
   <div class="container mt-3" style="align-items: center;">
      <div class="mt-2 p-1 bg-dark text-white rounded" >
        <h1 style="text-align: center;">Users</h1> 
      </div>
  <!-- <form style="align-content: center;" action="" method="POST"> -->
    <div class="form-group row" style="align-content: center;">
      <p style="text-align: center;"> - </p>
        <div class="col-sm-12">
        
        </div>
  </div>
    <div class="container" style="padding: 0%;">     
    <table class="table table-striped">
      <thead>
        <tr>
          <th style="text-align: center;">Full Name</th>
          <th style="text-align: center;">Email Address</th>
        </tr>

        <tr>
          <td>
          </td>

          <td>
          </td>

           <td>
          </td>

        </tr>
      </thead>


<?php

  $qry = "SELECT * FROM users";
  $stmt = $conn->prepare($qry);
  $stmt->execute();
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC); 

  foreach ($users as $row) 
  {
    $user_ID = $row['id'];
    $user_name = $row['full_name'];
    $email = $row['email'];

?>

    <tbody>
      <tr>
        <form action="/users" method="POST">
          <td style="text-align: center;"> <?php echo($user_name); ?></td>
          <td style="text-align: center;"> <?php echo($email); ?></td>
          <td style="text-align: center;"> 
            <button type="submit" class="close" aria-label="Close" name="remove" value="<?php echo($email); ?> ">
              <span aria-hidden="true">&times;</span>
            </button>
          </td>

        </form>
      </tr>

    <?php } ?>

        </tbody>
      </table>
      <form action="adduser" method=GET>
      <center> <button type="submit" class="btn btn-secondary" name="adduser" formaction="adduser" value="add" style="text-align: center;"> Add </button> </center>
      </form>
    </div>
  </div>
<!-- </form> -->
</div>




</body>
</html>


