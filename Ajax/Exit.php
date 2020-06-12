<?php
	session_start();
	unset($_SESSION['user']);
	session_destroy();
	echo "window.location.href='home.php';";
?>