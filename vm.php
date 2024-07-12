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


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> VM Operation </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href='https://fonts.googleapis.com/css?family=Raleway:200,400,800' rel='stylesheet' type='text/css'><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
</head>
<body>


  <?php include 'navbar.php'; ?>
  
  <?php

  function check_vm_stats($vm_loc)

  // DEPRECIATED: This function was for VM State Check using VMRUN. Upgraded to VMREST API. Keep code for fall back.
  {
    $result = Null;
    $clean_list = array();
    $vmrun_loc = $GLOBALS['vmrun_location'];
    $command = $vmrun_loc . " " . "list";
    $exec = exec($command, $result);
    // print_r($result);
    $num_rows = count($result);
    // echo($num_rows);

    for ($i=1; $i < $num_rows; $i++) 
    { 
      $split_from_list = explode("\\", $result[$i]);
      $vmx_name_from_list = end($split_from_list);
      // echo($vmx_name_from_list);
      array_push($clean_list, $vmx_name_from_list);
    }

      // print_r($clean_list);

      $split_from_arg = explode("\\", $vm_loc);
      $vmx_name_from_arg_untrimmed = end($split_from_arg);
      $vmx_name_from_arg_trimmed = trim($vmx_name_from_arg_untrimmed, '"');
      // echo($vmx_name_from_arg_trimmed . " " . $vmx_name_from_list ."<br>");

      $find = array_search($vmx_name_from_arg_trimmed, $clean_list);
      // echo("Index:" . $find);

      if ($find !== False) 
      {
        return 1;
      }
      else
      {
        return 0;
      }
  }

  ?>

  <?php

  function check_vm_statsV2($location)
  {
    $vmwid = getVmwareID($location);
    if ($vmwid)
    {
      $vmstate = getVmState($vmwid);
      return $vmstate;
    }
    else
    {
      return "StateCheckError!";
    }
  }

  ?>

   
   <div class="container mt-3" style="align-items: center;">
      <div class="mt-2 p-1 bg-dark text-white rounded" >
        <h1 style="text-align: center;">Operations</h1> 
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
          <th style="text-align: center;">VM Name</th>
          <!-- <th style="text-align: center;">VM Description</th> -->
          <th style="text-align: center;">Action</th>
        </tr>

        <tr>
          <td>
          </td>

   <!--        <td>
          </td>
 -->
          <td>
          </td>

        </tr>
      </thead>


<?php

  header("refresh: 30"); // For Refreshing the page every 30 seconds. Reason: VmStates JSON refresh. //

  $vmStatesList = array();

  $qry = "SELECT * FROM vms";
  $stmt = $conn->prepare($qry);
  $stmt->execute();
  $vms = $stmt->fetchAll(PDO::FETCH_ASSOC); 

  foreach ($vms as $row) 
  {
    $vm_ID = $row['vms_id'];
    $vm_name = $row['name'];
    $vm_desc = $row['description'];
    $vm_location = $row['location'];
    // echo("VM LOC " . $vm_location . "<br>");

    $status = check_vm_statsV2($vm_location);
    $vm_name_with_stats = $vm_name . " (" . $status . ")";

    if ($status == "Running")
    {
      $vmw_id = getVmwareID($vm_location);
      $arr = array("name" => $vm_name, "loc" => $vm_location, "vmID" => $vmw_id, "status" => "Running");
      array_push($vmStatesList, $arr);
    }

?>

    <tbody>
      <tr>
        <form action="action" method="POST">
          <td style="text-align: center;"> <?php echo($vm_name_with_stats); ?></td>
          <!-- <td style="text-align: center;"> <?php echo($vm_desc); ?></td> -->
          <td style="text-align: center;"> 
            <!-- <button type="submit" class="btn btn-secondary btn-sm" name="details" value="<?php echo($vm_ID); ?> ">Details</button>  KEEPING AS BACKUP -->
            <button type="submit" class="bi bi-info-circle" style="border: none" name="details" value="<?php echo($vm_ID); ?> "></button>
            <button type="submit" class="bi bi-tv" style="border: none" name="connect" value="<?php echo($vm_ID); ?> "></button>
            <button type="submit" class="bi bi-play-fill" style="border: none" name="start" value="<?php echo($vm_ID); ?> "></button>
            <button type="submit" class="bi bi-stop-circle" style="border: none" name="stop" value="<?php echo($vm_ID); ?> "></button>
            <button type="submit" class="bi bi-pause-circle" style="border: none" name="pause" value="<?php echo($vm_ID); ?> "></button>
            <button type="submit" class="bi bi-play-circle" style="border: none" name="resume" value="<?php echo($vm_ID); ?> "></button>
            <button formaction="editvm.php" type="submit" style="border: none" class="bi bi-pencil-square" aria-label="Edit" name="edit" value="<?php echo($vm_ID); ?> "></button>
            <button type="submit" class="bi bi-trash" style="border: none" aria-label="Close" name="remove" value="<?php echo($vm_ID); ?> "></button>
              <!-- <span aria-hidden="true">&times;</span> -->
            </button>
          </td>
        </form>
      </tr>

    <?php } ?>

        </tbody>
      </table>
      <form action="addvm" method=GET>
      <center> <button type="submit" class="btn btn-secondary" name="addvm" formaction="addvm" value="add" style="text-align: center;"> Add </button> </center>
      </form>
    </div>
  </div>
<!-- </form> -->
</div>

</body>
</html>

<?php

file_put_contents('vmStates.json', json_encode($vmStatesList, JSON_PRETTY_PRINT));

?>


