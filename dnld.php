

<?php 
    session_start();

    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_email']))
    {
        header("Location: index");
    }
?>


<?php

$dirPath = "Z:/vmops/";
$files = scandir($dirPath);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL List</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        table {
            width: 80%;
            max-width: 600px;
            margin: auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>

    <table>
        <thead>
            <tr>
                <th>URLs</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($files as $file) 
                {
                    $filePath = $dirPath . '/' . $file;
                    if (is_file($filePath)) 
                    {
                    ?>
                        <tr>
                        <td><a href="download_action.php?file_name=<?php echo($file); ?> "> <?php echo($file); ?></a></td>
                        </tr>
                    <?php
                    }

                }
            ?>
            
        </tbody>
    </table>

</body>
</html>
