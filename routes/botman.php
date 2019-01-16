<?php

use App\Http\Controllers\BotManController;

$botman = resolve('botman');
$botname = config('botman.telegram.username');

$botman->hears("/(start|help)(?:@$botname)?", function ($bot) {
    if ($bot->getUser()->getUsername()) {
        $userStr = '@'.$bot->getUser()->getUsername();
    } else {
        $userStr = "[{$bot->getUser()->getFirstName()}](tg://user?id={$bot->getUser()->getId()})";
    }

    $msg = "$userStr, voici la liste des commandes:\n\n"
          ."/start ou /help - Obtenir ce message\n"
          ."/rule `sujet` - Obtenir la liste des règles ou une règle en particulier";

    $bot->reply($msg, ['parse_mode' => 'markdown']);
});
