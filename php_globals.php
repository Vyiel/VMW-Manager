<?php

$json_file = __DIR__ . '\app_config.json';
$json_string = file_get_contents($json_file);
$data = json_decode($json_string, true);

$GLOBALS['vmrun_locatiom'] = $data['vmrun_location'];
$GLOBALS['host_location'] = $data['host_location'];
$GLOBALS['novnc_dir'] = $data['novnc_dir'];
$GLOBALS['vmrest_user'] = $data['vmrest_user'];
$GLOBALS['vmrest_pass'] = $data['vmrest_pass'];
$GLOBALS['vmrest_baseURL'] = $data['vmrest_baseURL'];
$GLOBALS['mysql_host'] = $data['mysql_host'];
$GLOBALS['mysql_user'] = $data['mysql_user'];
$GLOBALS['mysql_pass'] = $data['mysql_pass'];
$GLOBALS['mysql_DB'] = $data['mysql_DB'];
$GLOBALS['parseVmxLoc'] = $data['parseVmxLoc'];
$GLOBALS['vncCommonPassText'] = $data['vncCommonPassText'];
$GLOBALS['uploadLoc'] = $data['uploadLoc'];

?>

<?php

error_reporting(0);
set_time_limit(300)
// This is to avoid technical and line by line errors in prod that might compromise code leak and security.
// A better option would be to throw try catch errors but I wrote the code such a way, that if deployment was done correctly, there would be very less errors

?>