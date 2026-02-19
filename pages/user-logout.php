<?php
session_start();
unset($_SESSION['site_user_id']);
unset($_SESSION['site_user_name']);
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? './'));
exit;
