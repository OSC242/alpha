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
          ."/rule `sujet` - Obtenir la liste des règles ou une règle en particulier\n"
          ."/web - Point d'entrée web";

    $bot->reply($msg, ['parse_mode' => 'markdown']);
});

$botman->hears("/rule(?:@$botname)?(?: (.*))?", function ($bot, $subject = null) {
    if ($bot->getUser()->getUsername()) {
        $userStr = '@'.$bot->getUser()->getUsername();
    } else {
        $userStr = "[{$bot->getUser()->getFirstName()}](tg://user?id={$bot->getUser()->getId()})";
    }

    if (is_null($subject)) {
        $msg = "$userStr, quel sujet recherches-tu?\n\n"
              ."- `audio`\n"
              ."- `politique`\n\n"
              ."Il te suffit de taper: /rule `sujet` pour avoir les détails";
    } elseif (in_array($subject, ['audio', 'voix'])) {
        $msg = 'Les *messages vocaux* ne sont pas autorisés dans ce groupe. Si vous tentez d\'en envoyer un, il sera supprimé. Plusieurs tentatives pourront donner lieu à une éjection du groupe.';
    } elseif ($subject == 'politique') {
        $msg = 'Nous ne faisons pas de *politique*, ces sujets sont interdits. Plusieurs tentatives pourront donner lieu à une éjection du groupe.';
    }

    $bot->reply($msg, ['parse_mode' => 'markdown']);
});

$botman->hears('/web', function ($bot) {
    $bot->reply("OSC242 se prépare. Nous avons une page d'accueil qui vous permet d'inscrire votre adresse e-mail pour être informé de l'ouverture de la communauté: http://osc.cg/.\n\nNous avons un wiki qui se construit tout doucement aussi: http://wiki.osc.cg/", ['parse_mode' => 'markdown']);
});
