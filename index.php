<?php 
  session_start();

  if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) { 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>HOME</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
	<link href='https://fonts.googleapis.com/css?family=Raleway:200,400,800' rel='stylesheet' type='text/css'><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
	<link rel='stylesheet' href='https://www.marcoguglie.it/Codepen/AnimatedHeaderBg/demo-1/css/demo.css'><link rel="stylesheet" href="./style.css">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
</head>
<body>
	 
	 <?php include 'navbar.php'; ?>

<div id="large-header" class="large-header">
  <canvas id="demo-canvas"></canvas>
    <h1 class="main-title">VMware <span class="thin">Operations</span></h1>
</div>
<!-- partial -->
  <script src='https://www.marcoguglie.it/Codepen/AnimatedHeaderBg/demo-1/js/EasePack.min.js'></script>
<script src='https://www.marcoguglie.it/Codepen/AnimatedHeaderBg/demo-1/js/rAF.js'></script>
<script src='https://www.marcoguglie.it/Codepen/AnimatedHeaderBg/demo-1/js/TweenLite.min.js'></script><script  src="./script.js"></script>

</body>
</html>


<?php 
}else {
   header("Location: login");
}
 ?>
