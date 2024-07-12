
<?php 
    session_start();

    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email']))
    {
        header("Location: index");
    }
?>

<?php

  require 'php_globals.php';

?>



<?php

if (isset($_REQUEST['file_name']))
{
	$file = basename($_REQUEST['file_name']);
	$dirPath = $GLOBALS['uploadLoc'];
	$filePath = $dirPath.$file;

	if(!file_exists($filePath))
	{
    	echo "<script>
              alert('File Not Found! ');
              window.location = 'index';
              </script>"; 
	} 
	else 
	{
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-Disposition: attachment; filename=" . basename($filePath));
	    header("Content-Type: application/zip");
	    header("Content-Transfer-Encoding: binary");

	    readfile($filePath);
	}


}




?>