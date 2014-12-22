<?php
	session_start();
	switch  ($_GET['item']) {
		case 'editor':
			if (isset($_SESSION['editor']))
				unset($_SESSION['editor']);
			else
				$_SESSION['editor'] = "1";
		break;
	}
	header("Location: index.php");
?>
