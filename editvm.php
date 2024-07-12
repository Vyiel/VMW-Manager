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

if(isset($_POST['submit']) && $_POST['submit'] == "edit")
{
    $id = htmlspecialchars($_POST['id'], ENT_QUOTES);
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $desc = htmlspecialchars($_POST['description'], ENT_QUOTES);
    // $loc = '"'.htmlspecialchars($_POST['location'], ENT_QUOTES).'"'; -> THIS ONE NEEDED FOR VMRUN IMPLEMENTATION
    $port = htmlspecialchars($_POST['port'], ENT_QUOTES);


    $getLoc = $conn->prepare("SELECT location FROM vms WHERE vms_id = ?");
    $getLoc->execute([$id]);

    $vm = $getLoc->fetch(PDO::FETCH_ASSOC); 

    $loc = $vm['location'];

    $checkIfExists = $conn->prepare("SELECT * FROM vms WHERE name = ? OR port = ?");
    $checkIfExists->execute([$name, $port]);

    if ($checkIfExists->rowCount() < 1)
    {

      $jsonArgs = array('vmxLoc' => $loc, 'port' => strval($port));
      $jsonStr = json_encode($jsonArgs);

      $tmp_file = tempnam(sys_get_temp_dir(), 'json');
      file_put_contents($tmp_file, $jsonStr);
      $command = "python parseVmx.py $tmp_file";
      exec($command . " " . $args, $output, $return_var);
      unlink($tmp_file);

      print_r($output);

      if ($output[0] == "True")
      {
        $qry = "UPDATE vms SET name = ?, description = ?, port = ? WHERE vms_id = ?";
        $stmt = $conn->prepare($qry);
        $stmt->execute([$name, $desc, $port, $id]);
        $last_insert_ID = $conn->lastInsertId();
      }
      else
      {
        echo "<script>
              alert('Error saving to VMWare VNC Config! Update Failed!');
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
  <title> Edit VM </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href='https://fonts.googleapis.com/css?family=Raleway:200,400,800' rel='stylesheet' type='text/css'><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

  <?php

  $id = htmlspecialchars($_POST['edit'], ENT_QUOTES);  

  $getData = $conn->prepare("SELECT * FROM vms WHERE vms_id = ?");
  $getData->execute([$id]);

  $vm = $getData->fetch(PDO::FETCH_ASSOC); 

  $vm_ID = $vm['vms_id'];
  $vm_name = $vm['name'];
  $vm_desc = $vm['description'];
  $vm_port = $vm['port'];

  ?>

   
   <div class="container mt-3" style="align-items: center;">
  <div class="mt-2 p-1 bg-dark text-white rounded" >
    <h1 style="text-align: center;">Edit Virtual Machines</h1> 
  </div>
  <form style="align-content: center;" action="" method="POST">
  <div class="form-group row" style="align-content: center;">
    <p style="text-align: center;"> - </p>
    <div class="col-sm-12">
        <input type="hidden" class="form-control" name="id" value="<?php echo($vm_ID); ?>">
        <input type="text" class="form-control" name="name" placeholder="<?php echo($vm_name); ?>">
        <input type="text" class="form-control" name="description" placeholder="<?php echo($vm_desc); ?>">
        <!-- <input type="text" class="form-control" name="location" placeholder="Enter VM Location in the server computer. I.E Path to VMX" required> -->
        <input type="text" class="form-control" name="port" placeholder="<?php echo($vm_port); ?>" required>
        <center> <button type="submit" class="btn btn-secondary" name="submit" value="edit" style="text-align: center;"> Go </button> </center>
    </div>
  </div>

  </div>
</form>
</div>




</body>
</html>


