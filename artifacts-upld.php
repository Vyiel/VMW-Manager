
<?php 
    session_start();

    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email']))
    {
        header("Location: index.php");
    }
?>

<?php

  require 'php_globals.php';

?>




 <?php
    // Handle file upload
    if (isset($_POST['submit'])) {
        $targetDir = $GLOBALS['uploadLoc'];
        $targetFile = $targetDir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;

        $bad_ext = array("dummy", "exe", "php", "phar", "php5", "php4", "php7", "phtml", "htaccess");

        if (file_exists($targetFile)) 
        {
            echo "Error, file already exists. <br>";
            $uploadOk = 0;
        }

        if(!empty($_FILES['file']))
        {
            $path = strtolower($targetDir . basename($_FILES['file']['name']));
            $split_by_dot = explode(".", $path);
            $ext = end($split_by_dot);

            if (array_search($ext, $bad_ext) == FALSE)
            {
                
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) 
                {
                    echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
                    echo "<script>
                        alert('File Uploaded! ');
                        window.location = 'index';
                        </script>"; 
                    sleep(2);
                } 
                else 
                {
                    echo "<script>
                        alert('File Upload Failed! ');
                        window.location = 'index';
                        </script>"; 
                    sleep(2);
                }
            }
            else 
                {
                    echo "<script>
                        alert('Illegal File Type! ');
                        window.location = 'artifacts';
                        </script>"; 
                    sleep(2);
                }
        }
    }
    ?>
</body>
</html>