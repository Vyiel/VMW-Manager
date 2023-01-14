<?php 
	include 'db_conn.php';

	session_start();

	if (empty($_SESSION['user_id']) && empty($_SESSION['user_email'])) 
	{
		header("Location: login");
	}

?>

<?php

	include 'app_config.php'
?>



<?php

function callVmware($location, $action, $port)
{
	if ($action == "start")
	{
		$vmrun_loc = $GLOBALS['vmrun_location'];
		$host_loc = $GLOBALS['host_location'];
		$command = "start" . " " . $location;
		$final_command = $vmrun_loc . " " . $command;
		$exec = exec($final_command);
		echo "<script>
		alert('The VM will be accessible at " . $host_loc . ":" . $port . " in a minute! ');
		window.location = 'vm';
			</script>";	
	}

	elseif ($action == "stop")
	{
		$vmrun_loc = $GLOBALS['vmrun_location'];
		$command = "stop" . " " . $location;
		$final_command = $vmrun_loc . " " . $command;
		$exec = exec($final_command);
		echo "<script>
		alert('The VM will be shutdown in a minute! ');
		window.location = 'vm';
			</script>";	
	}

	elseif ($action == "pause")
	{
		$vmrun_loc = $GLOBALS['vmrun_location'];
		$command = "pause" . " " . $location;
		$final_command = $vmrun_loc . " " . $command;
		$exec = exec($final_command);
		echo "<script>
		alert('The VM will be paused in a minute! ');
		window.location = 'vm';
			</script>";	
	}

	elseif ($action == "resume")
	{
		$vmrun_loc = $GLOBALS['vmrun_location'];
		$host_loc = $GLOBALS['host_location'];
		$command = "resume" . " " . $location;
		$final_command = $vmrun_loc . " " . $command;
		$exec = exec($final_command);
		echo "<script>
		alert('The VM will be resumed in a minute and will be accessible at " . $host_loc . ":'" . $port . ");
		window.location = 'vm';
			</script>";	
	}
}

?>

<?php

if(isset($_REQUEST['error']))
{
	echo "<script>
		alert('Please check for illegal values! ');
		window.location = 'vm';
		</script>";
}

if(isset($_POST['start']))
{
	$vm_ID = htmlspecialchars($_POST['start'], ENT_QUOTES);

	$stmt = $conn->prepare("SELECT * FROM vms WHERE id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		callVmware($vm_loc, 'start', $vm_port);
	}
	else
	{
		echo "Value Error!";
	}
}

elseif (isset($_POST['stop']))
{
	$vm_ID = htmlspecialchars($_POST['stop'], ENT_QUOTES);

	$stmt = $conn->prepare("SELECT * FROM vms WHERE id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		callVmware($vm_loc, 'stop', $vm_port);
	}
	else
	{
		echo "Value Error!";
	}
}

elseif (isset($_POST['pause']))
{
	$vm_ID = htmlspecialchars($_POST['pause'], ENT_QUOTES);

	$stmt = $conn->prepare("SELECT * FROM vms WHERE id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		callVmware($vm_loc, 'pause', $vm_port);
	}
	else
	{
		echo "Value Error!";
	}
}

elseif (isset($_POST['resume']))
{
	$vm_ID = htmlspecialchars($_POST['resume'], ENT_QUOTES);

	$stmt = $conn->prepare("SELECT * FROM vms WHERE id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		callVmware($vm_loc, 'resume', $vm_port);
	}
	else
	{
		echo "Value Error!";
	}
}

elseif (isset($_POST['remove']))
{
	$vm_ID = htmlspecialchars($_POST['remove'], ENT_QUOTES);

	$stmt = $conn->prepare("DELETE FROM vms WHERE id=?;");
	$stmt->execute([$vm_ID]);

	if($stmt)
	{
		echo "<script>
		alert('Record Removed! ');
		window.location = 'vm';
			</script>";	
	}

}

elseif (isset($_POST['details']))
{
	$vm_ID = htmlspecialchars($_POST['details'], ENT_QUOTES);

	$stmt = $conn->prepare("SELECT * FROM vms WHERE id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		$vm_desc = $vms['description'];

		
		echo "<script>
		alert(
		'Virtual Machine Name: " . $vm_name . "\\nVirtual Machine description: " . $vm_desc . "\\nVirtual Machine Access Port: " . $vm_port . "');

		window.location = 'vm';
			</script>";	

	}
	else
	{
		echo "Value Error!";
	}
}

else
{
	header("Location: vm?error=Value Error! ");
}

?>
