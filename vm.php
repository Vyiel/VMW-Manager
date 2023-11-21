<?php 
	include 'db_conn.php';

	session_start();

	if (empty($_SESSION['user_id']) && empty($_SESSION['user_email'])) 
	{
		header("Location: login");
	}

  include 'app_config.php';


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

  $qry = "SELECT * FROM vms";
  $stmt = $conn->prepare($qry);
  $stmt->execute();
  $vms = $stmt->fetchAll(PDO::FETCH_ASSOC); 

  foreach ($vms as $row) 
  {
    $vm_ID = $row['id'];
    $vm_name = $row['name'];
    $vm_desc = $row['description'];
    $vm_location = $row['location'];
    // echo("VM LOC " . $vm_location . "<br>");

    $status = check_vm_stats($vm_location);
    // echo($status);
    if($status == 1)
    {
      $vm_name = $vm_name . " " . "(Running)";
      // echo($vm_name);
    }

?>

    <tbody>
      <tr>
        <form action="action" method="POST">
          <td style="text-align: center;"> <?php echo($vm_name); ?></td>
          <!-- <td style="text-align: center;"> <?php echo($vm_desc); ?></td> -->
          <td style="text-align: center;"> 
            <button type="submit" class="btn btn-secondary btn-sm" name="details" value="<?php echo($vm_ID); ?> ">Details</button>
            <button type="submit" class="btn btn-secondary btn-sm" name="start" value="<?php echo($vm_ID); ?> ">Start</button>
            <button type="submit" class="btn btn-secondary btn-sm" name="stop" value="<?php echo($vm_ID); ?> ">Stop</button>
            <button type="submit" class="btn btn-secondary btn-sm" name="pause" value="<?php echo($vm_ID); ?> ">Pause</button>
            <button type="submit" class="btn btn-secondary btn-sm" name="resume" value="<?php echo($vm_ID); ?> ">Resume</button>
            <button type="submit" class="close" aria-label="Close" name="remove" value="<?php echo($vm_ID); ?> ">
              <span aria-hidden="true">&times;</span>
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


