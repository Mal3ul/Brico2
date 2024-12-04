<?php
session_start();

if (session_status() === PHP_SESSION_ACTIVE) {
    $_SESSION = array();
    session_destroy();
}

// session_destroy();


header('Location: ../inclus/index.php');
exit();
?>
