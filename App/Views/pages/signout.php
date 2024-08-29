<?php


use App\Core\SystemSession;

$oSession = new SystemSession();
$oSession->destroy();
header('Location: /signin');