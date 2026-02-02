<?php
session_start();
session_destroy();
header("Location: userLog.php");
exit;
?>