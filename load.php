<?php

include 'connect.php';
include 'functions.php';
include 'data.php';

$messages = getMessages($bdd, $_GET['status'] ?? 'VALIDE', $class);
$messagesStr = '';

foreach ($messages as $message) {
    $messagesStr .= getMessageDisplay($message);
}

echo $messagesStr;