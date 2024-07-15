<?php 
	require 'db_conn.php';

	session_start();

	if (empty($_SESSION['user_id']) && empty($_SESSION['user_email'])) 
	{
		header("Location: login");
	}

?>

<?php

	require 'php_globals.php';
	require 'vmrest_functions.php';

?>


<?php


function callVmware($location, $action, $port)
{
	$vmrun_loc = $GLOBALS['vmrun_location'];
	$host_loc = $GLOBALS['host_location'];
	if ($action == "start")
	{
		$vmwid = getVmwareID($location);
		if ($vmwid)
		{
			$cmd = powerState($vmwid, 1);
			if ($cmd == 200)
			{
				echo "<script>
				alert('The VM will be accessible at " . $host_loc . " in a minute! ');
				window.location = 'vm';
				</script>";	
			}
			else
			{
				echo "<script>
				alert('ERROR performing action! - PowerStateNot200');
				window.location = 'vm';
				</script>";	

			}
		}
			
		// OLD CODE BELLOW FOR VMRUN USAGE. NOW MIGRATED TO VMREST API. KEEP OLD CODE FOR FALLBACK.
		// 
		// $vmrun_loc = $GLOBALS['vmrun_location'];
		// $host_loc = $GLOBALS['host_location'];

		// $command = "start" . " " . $location;
		// $final_command = $vmrun_loc . " " . $command;
		// $exec = exec($final_command);
		// echo "<script>
		// alert('The VM will be accessible at " . $host_loc . ":" . $port . " in a minute! ');
		// window.location = 'vm';
		// 	</script>";	
		
	}

	elseif ($action == "stop")
	{
		$vmwid = getVmwareID($location);
		if ($vmwid)
		{
			$cmd = powerState($vmwid, 2);
			if ($cmd == 200)
			{
				echo "<script>
				alert('The VM will be shutdown in a minute! ');
				window.location = 'vm';
				</script>";	
			}
			else
			{
				echo "<script>
				alert('ERROR performing action! - PowerStateNot200');
				window.location = 'vm';
				</script>";	

			}
		}
			
		// OLD CODE BELLOW FOR VMRUN USAGE. NOW MIGRATED TO VMREST API. KEEP OLD CODE FOR FALLBACK.
		// 
		// $vmrun_loc = $GLOBALS['vmrun_location'];
		// $host_loc = $GLOBALS['host_location'];
		// $command = "stop" . " " . $location;
		// $final_command = $vmrun_loc . " " . $command;
		// $exec = exec($final_command);
		// echo "<script>
		// alert('The VM will be shutdown in a minute! ');
		// window.location = 'vm';
		// 	</script>";	
	}

	elseif ($action == "pause")
	{
		$vmwid = getVmwareID($location);
		if ($vmwid)
		{
			$cmd = powerState($vmwid, 3);
			if ($cmd == 200)
			{
				echo "<script>
				alert('The VM will be paused in a minute! ');
				window.location = 'vm';
				</script>";	
			}
			else
			{
				echo "<script>
				alert('ERROR performing action! - PowerStateNot200');
				window.location = 'vm';
				</script>";	

			}
		}

		// OLD CODE BELLOW FOR VMRUN USAGE. NOW MIGRATED TO VMREST API. KEEP OLD CODE FOR FALLBACK.
		// 
		// $vmrun_loc = $GLOBALS['vmrun_location'];
		// $command = "pause" . " " . $location;
		// $final_command = $vmrun_loc . " " . $command;
		// $exec = exec($final_command);
		// echo "<script>
		// alert('The VM will be paused in a minute! ');
		// window.location = 'vm';
		// 	</script>";	
	}

	elseif ($action == "resume")
	{
		$vmwid = getVmwareID($location);
		if ($vmwid)
		{
			$cmd = powerState($vmwid, 4);
			if ($cmd == 200)
			{
				echo "<script>
				alert('The VM will be resumed in a minute and will be accessible at " . $host_loc . ":'" . $port . ");
				window.location = 'vm';
				</script>";	
			}
			else
			{
				echo "<script>
				alert('ERROR performing action! - PowerStateNot200');
				window.location = 'vm';
				</script>";	

			}
		}

		// OLD CODE BELLOW FOR VMRUN USAGE. NOW MIGRATED TO VMREST API. KEEP OLD CODE FOR FALLBACK.
		// 
		// $vmrun_loc = $GLOBALS['vmrun_location'];
		// $host_loc = $GLOBALS['host_location'];		// $command = "resume" . " " . $location;
		// $final_command = $vmrun_loc . " " . $command;
		// $exec = exec($final_command);
		// echo "<script>
		// alert('The VM will be resumed in a minute and will be accessible at " . $host_loc . ":'" . $port . ");
		// window.location = 'vm';
		// 	</script>";	
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

	$stmt = $conn->prepare("SELECT * FROM vms WHERE vms_id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['vms_id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		callVmware($vm_loc, 'start', $vm_port);
	}
	else
	{
		echo "<script>
		alert(
		'Mismatch/Unavailable Data! DB/Table Issue!');

		window.location = 'vm';
			</script>";	
	}
}

elseif (isset($_POST['stop']))
{
	$vm_ID = htmlspecialchars($_POST['stop'], ENT_QUOTES);

	$stmt = $conn->prepare("SELECT * FROM vms WHERE vms_id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['vms_id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		callVmware($vm_loc, 'stop', $vm_port);
	}
	else
	{
		echo "<script>
		alert(
		'Mismatch/Unavailable Data! DB/Table Issue!');

		window.location = 'vm';
			</script>";	
	}
}

elseif (isset($_POST['pause']))
{
	$vm_ID = htmlspecialchars($_POST['pause'], ENT_QUOTES);

	$stmt = $conn->prepare("SELECT * FROM vms WHERE vms_id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['vms_id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		callVmware($vm_loc, 'pause', $vm_port);
	}
	else
	{
		echo "<script>
		alert(
		'Mismatch/Unavailable Data! DB/Table Issue!');

		window.location = 'vm';
			</script>";	
	}
}

elseif (isset($_POST['resume']))
{
	$vm_ID = htmlspecialchars($_POST['resume'], ENT_QUOTES);

	$stmt = $conn->prepare("SELECT * FROM vms WHERE vms_id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['vms_id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		callVmware($vm_loc, 'resume', $vm_port);
	}
	else
	{
		echo "<script>
		alert(
		'Mismatch/Unavailable Data! DB/Table Issue!');

		window.location = 'vm';
			</script>";	
	}
}

elseif (isset($_POST['remove']))
{
	$vm_ID = htmlspecialchars($_POST['remove'], ENT_QUOTES);

	$stmt = $conn->prepare("DELETE FROM vms WHERE vms_id=?;");
	$stmt->execute([$vm_ID]);

	if($stmt)
	{
		echo "<script>
		alert('Record Removed! ');
		window.location = 'vm';
			</script>";	
	}
	else
	{
		echo "<script>
		alert(
		'Mismatch/Unavailable Data! DB/Table Issue!');

		window.location = 'vm';
			</script>";	
	}

}

elseif (isset($_POST['details']))
{
	$vm_ID = htmlspecialchars($_POST['details'], ENT_QUOTES);

	$stmt = $conn->prepare("SELECT * FROM vms, vnc_servers WHERE vms.vms_id = vnc_servers.vms_id AND vms.vms_id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['vms_id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		$vm_desc = $vms['description'];
		$websockify_port = $vms['websockify_port'];

		
		echo "<script>
		alert(
		'VM Name: " . $vm_name . "\\nVM description: " . $vm_desc . "\\nVM Access Port: " . $vm_port . "\\nBrowserVNC Port: " . $websockify_port . "');

		window.location = 'vm';
			</script>";	

	}
	else
	{
		echo "<script>
		alert(
		'Mismatch/Unavailable Data! DB/Table Issue!');

		window.location = 'vm';
			</script>";	
	}
}

elseif (isset($_POST['connect']))
{
	$vm_ID = htmlspecialchars($_POST['connect'], ENT_QUOTES);

	$stmt = $conn->prepare("SELECT * FROM vms, vnc_servers WHERE vms.vms_id = vnc_servers.vms_id AND vms.vms_id=?");
	$stmt->execute([$vm_ID]);

	if ($stmt->rowCount() === 1) 
	{
		$vms = $stmt->fetch();

		$vm_id = $vms['vms_id'];
		$vm_name = $vms['name'];
		$vm_loc = $vms['location'];
		$vm_port = $vms['port'];
		$vm_desc = $vms['description'];
		$websockify_port = $vms['websockify_port'];

		echo'<script>
		window.location = "noVNC/vnc_lite?host=' . $GLOBALS["host_location"] . '&port=' . $websockify_port . '";
		// window.open(\'vm\');
		</script>
		';




	}
	else
	{
		echo "<script>
		alert(
		'Mismatch/Unavailable Data! DB/Table! VNC Infra/Config Issue!');

		window.location = 'vm';
			</script>";
	}
}

else
{
	header("Location: vm?error=Value Error! ");
}

?>
