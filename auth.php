<?php 
session_start();
require 'db_conn.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
	
	$email = $_POST['email'];
	$password = $_POST['password'];
	$SHA1_password = sha1($password);

	if (empty($email)) {
		header("Location: login?error=Email is required");
	}else if (empty($password)){
		header("Location: login?error=Password is required&email=$email");
	}else {
		$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
		$stmt->execute([$email]);

		if ($stmt->rowCount() === 1) {
			$user = $stmt->fetch();

			$user_id = $user['users_id'];
			$user_email = $user['email'];
			$user_password = $user['password'];
			$user_full_name = $user['full_name'];

			if ($email === $user_email) {
				if ($SHA1_password === $user_password)
				{
					$_SESSION['user_id'] = $user_id;
					$_SESSION['user_email'] = $user_email;
					$_SESSION['user_full_name'] = $user_full_name;
					header("Location: index");
				}
				else 
				{
					header("Location: login?error=Incorect User name or password&email=$email");
				}
			}else {
				header("Location: login?error=Incorect User name or password&email=$email");
			}
		}else {
			header("Location: login?error=Incorect User name or password&email=$email");
		}
	}
}
