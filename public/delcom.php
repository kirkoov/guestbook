<?php
session_start();
require '../ini.php';

if(isset($_SESSION['emAIl']) && isset($_GET['id'])) {
	$comment->delCom($_GET['id'], $_SESSION['emAIl']);
}

header('Location: /');