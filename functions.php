<?php

/**
 * @param array $data
 * @return string
 */
function getMessageDisplay($data) {

    $isAReply = !empty($data['replyTo']);

    switch ($data['status']) {
        case 'A_VALIDER':
            $buttons =
                '<i class="fa fa-smile medium-icon green pr-2 pointer" onclick="moderateMsg($(this), 1)"></i>
                <i class="fas fa-angry medium-icon red pointer" onclick="moderateMsg($(this), 0)"></i>';
            break;
        case 'VALIDE':
            $buttons = (isAdmin() && !$isAReply)
                ? '<i class="fas fa-reply medium-icon pointer" onclick="reply($(this))"></i>'
                : '';
            break;
        default:
            $buttons = '';
    }

    $additionalClasses = $isAReply ? ' ml-5 opacity' : '';

    return "<p id=\"" . $data['id'] . "\" class='post" . $additionalClasses. "'>" .
        '<span class="font-weight-bold">' . ($data['pseudo'] ?? 'anonyme') . "</span><br>" .
        '<span class="font-italic">' . $data['dateFormatted'] . "</span><br>" .
        $data['message'] .
        '<div class="text-right pr-3 mt-neg">' . $buttons . "</div></p>";
}

function isAdmin()
{
    session_start();
    include 'data.php';
    return ($_SESSION['pseudo'] == $nameAdmin);
}

function getQueryLoadMsg($bdd)
{
    return $bdd->prepare('
        SELECT m.id, m.pseudo, m.message, m.class,
        DATE_FORMAT(m.creationDate, "%e/%m %H:%i") as dateFormatted, m.status, m.replyTo
        FROM messages m
        WHERE m.status = :status AND m.class = :class AND m.replyTo IS NULL
        ORDER BY m.creationDate
        DESC LIMIT 0,10');
}

function getRepliesByMsg($bdd, $msgId)
{
    $query = $bdd->prepare('
        SELECT m.id, m.pseudo, m.message,
        DATE_FORMAT(m.creationDate, "%e/%m %H:%i") as dateFormatted, m.status, m.replyTo
        FROM messages m
        WHERE m.status = :status AND m.replyTo =:msgId
        ORDER BY m.replyTo
        DESC LIMIT 0,10');

    $query->execute([
            'msgId' =>  $msgId,
            'status' =>  'VALIDE'
    ]);

    $results = [];
    while($data = $query->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $data;
    }
    return $results;
}

function getMessages($bdd, $status, $class)
{
    $query = getQueryLoadMsg($bdd);
    $query->execute([
        'status' =>  $status,
        'class' => $class
    ]);

    $messages = [];
    while($data = $query->fetch(PDO::FETCH_ASSOC)){
        $messages[] = $data;
        $replies = getRepliesByMsg($bdd, $data['id']);
        foreach ($replies as $reply) {
            $messages[] = $reply;
        }
    }
    $query->closeCursor();

    return $messages;
}
