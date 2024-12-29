<?php
// logout.php
require_once '../helper/auth_helper.php';

$auth = AuthHelper::getInstance();
$auth->logout();

header('Location: login.php');
exit();