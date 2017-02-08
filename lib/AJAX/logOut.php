<?php
session_start();
session_destroy();
$_SESSION['logged_in'] = false;
$_SESSION['display_logout'] = true;
die();
