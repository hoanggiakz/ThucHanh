<?php
session_start();
session_unset();
session_destroy();
header('Location: /THUCHANHPHP/TUAN7/index.php');
exit();
?>