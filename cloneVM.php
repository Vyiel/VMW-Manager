<?php 
	require 'db_conn.php';

	session_start();

	if (empty($_SESSION['user_id']) && empty($_SESSION['user_email'])) 
	{
		header("Location: login");
	}

  require 'php_globals.php';
  require 'vmrest_functions.php';

?>

<?php

function relax() {
    ;
}


?>


<?php


if(isset($_POST['submit']) && $_POST['submit'] == "clone")
{
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $parentID = htmlspecialchars($_POST['vmPathID'], ENT_QUOTES);

    echo "<script>
            alert('This process takes time! DON'T CLOSE TAB OR REFRESH PAGE!!! ');
            </script>";

    $stat = cloneVM($parentID, $name);
    if ($stat == true)
    {
        echo "<script>
            alert('Cloning VM in Progress. Check back in a few minutes! ');
            window.location = 'vm';
            </script>"; 
    }

    else
    {
        echo "<script>
            alert('Failed to Clone VM. Unknown Error! Kindly check in person! ');
            window.location = 'vm';
            </script>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Create VM from Existing </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href='https://fonts.googleapis.com/css?family=Raleway:200,400,800' rel='stylesheet' type='text/css'><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

   
   <div class="container mt-3" style="align-items: center;">
  <div class="mt-2 p-1 bg-dark text-white rounded" >
    <h1 style="text-align: center;">Create VM from Existing VMs</h1> 
  </div>
  <form style="align-content: center;" action="" method="POST">
  <div class="form-group row" style="align-content: center;">
    <p style="text-align: center;"> - </p>
    <div class="col-sm-12">
        <input type="text" class="form-control" name="name" placeholder="New VM Name" required>
        <!-- <input type="text" class="form-control" name="location" placeholder="Enter VM Location in the server computer. I.E Path to VMX" required> -->
        <select class="form-control" name="vmPathID">
          <option> Select a VMX path for cloning from the available options </option>
        <?php

          $vmxlist = getAllVmwareID();
          foreach ($vmxlist as $row) 
          {

            $baseVmID = $row['id'];
            $baseVmPath = $row['path'];
            
          ?>
            <option value="<?php echo($baseVmID); ?>"> <?php echo($baseVmPath); ?> </option>

          <?php
          }

        ?>
      </select>

        <center> <button type="submit" class="btn btn-secondary" name="submit" value="clone" style="text-align: center;"> Clone </button> </center>

    </div>
  </div>

  </div>
</form>
</div>




</body>
</html>
