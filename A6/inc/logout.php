<?php
        require_once("mysql_settings.php");
        @session_start();
        session_destroy();
        header("Location: index.php");

?>