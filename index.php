<?php
session_set_cookie_params(3600 * 24 * 30,"/");
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My tchat</title>
</head>

<body>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Jost&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/75e926a663.js" crossorigin="anonymous"></script>
<link href="style.css" rel="stylesheet">
<link rel="icon" href="favicon.ico" type="image/x-icon">

<div class="container">
    <?php

    include 'data.php';

    $sessionOpened = false;

    if (empty($_SESSION['pseudo'])) {
        if (isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
            $_SESSION['pseudo'] = $_POST['pseudo'];

            $isAdmin = ($_POST['pseudo'] == $nameAdmin);
            if ($isAdmin) {
                if (isset($_POST['password']) && $_POST['password'] == $passwordAdmin) {
                    $_SESSION['password'] = true;
                    $sessionOpened = true;
                } else {
                    $sessionOpened = false;
                }
            } else {
                $sessionOpened = true;
            }
        } else {
            $sessionOpened = false;
        }
    } else {
        $isAdmin = ($_SESSION['pseudo'] == $nameAdmin);
        if ($isAdmin) {
            if (isset($_SESSION['password']) || isset($_POST['password']) && $_POST['password'] == $passwordAdmin) {
                $_SESSION['password'] = true;
                $sessionOpened = true;
            } else {
                $sessionOpened = false;
            }
        } else {
            $sessionOpened = true;
        }
    }

    if ($sessionOpened) {
        include 'connect.php';
        include 'functions.php';
        include 'modalNewMsg.html';
        ?>

        <div id="c1" class="mt-5 mb-5 d-flex justify-content-between">
            <div>
                Bienvenue <span id="pseudo" class="big-text"><?= $_SESSION['pseudo'] ?></span> !
                <?php
                if ($isAdmin) { ?>
                    <span onclick="displayMsgToValidate($(this))" id="btnMsgToValidate" class="ml-2 pointer">
                        <i class="fa fa-envelope white"></i>
                        <span class="badge badge-secondary" id="nbMsgToValidate"></span>
                    </span>
                    <span onclick="displayMsgValidated($(this))" id="btnMsgValidated" class="ml-2 pointer d-none">
                        <i class="fa fa-angle-double-left white"></i>
                    </span>
                <?php } ?>
            </div>
            <div>
                <button type="button" class="btn" data-toggle="modal" data-target="#modalNewMsg">
                    <i class="fa fa-plus-square big-icon"></i>
                </button>
            </div>
        </div>

        <div id="messages">
            <?php

            $messages = getMessages($bdd, 'VALIDE');

            foreach ($messages as $message) {
                echo getMessageDisplay($message);
            }
            ?>
        </div>

        <?php
    } else {
        include 'modalConnection.php';
    } ?>
</div>

<script
        src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="script.js"></script>
</body>
</html>
