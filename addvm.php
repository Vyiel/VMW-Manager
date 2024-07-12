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

function check_port()
{
  $host = "127.0.0.1";
  $port = rand(40000, 40200);
  $connection = @fsockopen($host, $port);
    while (is_resource($connection) == true)
      {
        $port = intval(rand(40000, 40200));
      }
    return $port;
}

if(isset($_POST['submit']) && $_POST['submit'] == "add")
{
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $desc = htmlspecialchars($_POST['description'], ENT_QUOTES);
    // $loc = '"'.htmlspecialchars($_POST['location'], ENT_QUOTES).'"'; -> THIS ONE NEEDED FOR VMRUN IMPLEMENTATION
    $loc = htmlspecialchars($_POST['location'], ENT_QUOTES);
    $port = htmlspecialchars($_POST['port'], ENT_QUOTES);

    // echo($name . $desc . $loc . $port);

    $checkIfExists = $conn->prepare("SELECT * FROM vms WHERE location = ? OR port = ?");
    $checkIfExists->execute([$loc, $port]);

    if ($checkIfExists->rowCount() < 1)
    {

      $jsonArgs = array('vmxLoc' => $loc, 'port' => strval($port));
      $jsonStr = json_encode($jsonArgs);

      $tmp_file = tempnam(sys_get_temp_dir(), 'json');
      file_put_contents($tmp_file, $jsonStr);
      $command = "python parseVmx.py $tmp_file";
      exec($command . " " . $args, $output, $return_var);
      unlink($tmp_file);

      if ($output[0] == "True")
      {
        $qry = "INSERT INTO vms (name, description, location, port) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($qry);
        $stmt->execute([$name, $desc, $loc, $port]);
        $last_insert_ID = $conn->lastInsertId();

        if ($stmt)
        {
          $rand_port = check_port();

          $checkIfExists = $conn->prepare("SELECT * FROM vnc_servers WHERE websockify_port = ?");
          $checkIfExists->execute([$rand_port]);
          while ($checkIfExists->rowCount() > 0) 
          {
            $rand_port = check_port();
            $checkIfExists = $conn->prepare("SELECT * FROM vnc_servers WHERE websockify_port = ?");
            $checkIfExists->execute([$rand_port]);
            // echo "PORT EXISTS!";
          }

          $i_stmt = $conn->prepare("INSERT INTO vnc_servers (vms_id, websockify_port) VALUES (?, ?)");
          $i_stmt->execute([$last_insert_ID, $rand_port]);
          if ($i_stmt)
          {
            echo "<script>
              alert('New Addition Recorded');
              window.location = 'vm';
                </script>"; 
            // relax();

          }
        }
        else
        {
          $i_stmt->error;
          echo "<script>
              alert('Failed to insert record!');
              window.location = 'vm';
                </script>"; 
        }
      }
      else
      {
        echo "<script>
              alert('Error saving to VMWare VNC Config! Record Failed!');
              window.location = 'vm';
                </script>";
      }

    }
    else
    {
      echo "<script>
            alert('VM or PORT already exists! Please try again.');
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
  <title> Add VMs </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href='https://fonts.googleapis.com/css?family=Raleway:200,400,800' rel='stylesheet' type='text/css'><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

   
   <div class="container mt-3" style="align-items: center;">
  <div class="mt-2 p-1 bg-dark text-white rounded" >
    <h1 style="text-align: center;">Add Virtual Machines</h1> 
  </div>
  <form style="align-content: center;" action="" method="POST">
  <div class="form-group row" style="align-content: center;">
    <p style="text-align: center;"> - </p>
    <div class="col-sm-12">
        <input type="text" class="form-control" name="name" placeholder="Enter VM Name">
        <input type="text" class="form-control" name="description" placeholder="Enter VM Description">
        <!-- <input type="text" class="form-control" name="location" placeholder="Enter VM Location in the server computer. I.E Path to VMX" required> -->
        <select class="form-control" name="location">
          <option> Select a VMX path from the available options </option>
        <?php

          $vmxlist = getVmLocs();
          foreach ($vmxlist as $row) 
          {?>
            <option value="<?php echo($row); ?>"> <?php echo($row); ?> </option>
          <?php
          }

          

        ?>
      </select>
        <input type="text" class="form-control" name="port" placeholder="Enter VNC Access Port. Make sure it's the same as entered from VMWare Workstation">
        <center> <button type="submit" class="btn btn-secondary" name="submit" value="add" style="text-align: center;"> Add </button> 
                 <button formaction="cloneVM" type="submit" class="btn btn-secondary" name="submit" value="create" style="text-align: center;"> Create </button> 
        </center>
    </div>
  </div>

  </div>
</form>
</div>




</body>
</html>


