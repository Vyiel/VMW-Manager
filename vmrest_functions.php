<?php

require 'php_globals.php';


function powerState($id, $state)
{
	if($state === 1)
	{
		$command = "on";
	}
	elseif($state === 2)
	{
		$command = "shutdown";
	}
	elseif($state === 3)
	{
		$command = "pause";
	}
	elseif($state === 4)
	{
		$command = "unpause";
	}

	$vmwareVmID = $id;

	$putVmsPowerUrl = $GLOBALS['vmrest_baseURL'].'/api/vms/' . $vmwareVmID . '/power';
	$username = $GLOBALS['vmrest_user'];
	$password = $GLOBALS['vmrest_pass'];

	$headers = [
	    "Accept: application/vnd.vmware.vmw.rest-v1+json",
	    "Content-Type: application/vnd.vmware.vmw.rest-v1+json"
	];


	$ch = curl_init($putVmsPowerUrl);
	curl_setopt($ch, CURLOPT_URL, "$putVmsPowerUrl");
	curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $command);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	// echo($response);
	$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	return $statusCode;
}


function getAllVmwareID()
{

	$getVmsUrl = $GLOBALS['vmrest_baseURL'].'/api/vms';
	$username = $GLOBALS['vmrest_user'];
	$password = $GLOBALS['vmrest_pass'];

	$ch = curl_init($getVmsUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/vnd.vmware.vmw.rest-v1+json'));

	$response = curl_exec($ch);
	curl_close($ch);

	$vms = json_decode($response, true);

	return $vms;

}


function getVmwareID($location)
{
	$getVmsUrl = $GLOBALS['vmrest_baseURL'].'/api/vms';
	$username = $GLOBALS['vmrest_user'];
	$password = $GLOBALS['vmrest_pass'];

	$ch = curl_init($getVmsUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/vnd.vmware.vmw.rest-v1+json'));

	$response = curl_exec($ch);
	curl_close($ch);

	$vms = json_decode($response, true);

	foreach ($vms as $vm) 
	{
		$vmwpath = $vm['path'];
		$vmwid = $vm['id'];

	    if ($vmwpath == $location)
	    {
	   		return $vmwid;
	    }
	}

}


function getVmState($vmw_id)
{
	$getVmStateUrl = $GLOBALS['vmrest_baseURL'].'/api/vms/' . $vmw_id . '/power';
	$username = $GLOBALS['vmrest_user'];
	$password = $GLOBALS['vmrest_pass'];

	$ch = curl_init($getVmStateUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/vnd.vmware.vmw.rest-v1+json'));

	$response = curl_exec($ch);
	// echo($response);
	curl_close($ch);

	$vms = json_decode($response, true);
	$state = $vms['power_state'];
	if ($state == "poweredOn")
	{
	  return "Running";
	}
	elseif ($state == "poweredOff")
	{
	  return "Stopped";
	}
	elseif ($state == "paused")
	{
	  return "Paused";
	}
}


function getVmLocs()
{
	$allVmLocs = [];

	$getVmLocs = $GLOBALS['vmrest_baseURL'].'/api/vms';
	$username = $GLOBALS['vmrest_user'];
	$password = $GLOBALS['vmrest_pass'];

	$ch = curl_init($getVmLocs);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/vnd.vmware.vmw.rest-v1+json'));

	$response = curl_exec($ch);
	curl_close($ch);

	$vms = json_decode($response, true);

	foreach ($vms as $vm) 
	{
		array_push($allVmLocs, $vm['path']);
	}

	return $allVmLocs;

}

function cloneVM($parentID, $name)
{

	$cloneUrl = $GLOBALS['vmrest_baseURL'].'/api/vms';
	$username = $GLOBALS['vmrest_user'];
	$password = $GLOBALS['vmrest_pass'];

	$data = array(
	    'name' => $name,
	    'parentId' => $parentID
	);

	$json_data = json_encode($data);

	$ch = curl_init($cloneUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);

	$headers = array(
	    'Content-Type: application/vnd.vmware.vmw.rest-v1+json',
	    'Accept: application/vnd.vmware.vmw.rest-v1+json',
	);

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	if ($http_code == 201) 
	{
	    return true;
	} 
	else 
	{
	    return false;
	}

}

?>