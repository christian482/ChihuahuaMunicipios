<?php
/////////LINK PAGINA ->https://guadalajara.herokuapp.com/
////////////////
///////////
//Este archivo importa todos los drivers necesarios para imoprtarlos con "use"
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/botman/botman/src/Interfaces/UserInterface.php';
require __DIR__ . '/vendor/botman/driver-facebook/src/FacebookDriver.php';
require __DIR__ . '/vendor/botman/botman/src/BotMan.php';
require __DIR__ . '/vendor/botman/botman/src/Drivers/Tests/ProxyDriver.php';
//Importando archivos necesarios
require_once __DIR__."/src/Constantes.php";
require_once __DIR__."/src/conversations/instituciones/TipoInstitucionConversation.php";
require_once __DIR__."/src/conversations/MenuConversation.php";
require_once __DIR__."/src/conversations/SalidaConversation.php";

//Extra Facebook Drivers
//require_once __DIR__."/vendor/botman/driver-facebook/src/FacebookDriver.php";
require_once __DIR__."/vendor/botman/driver-facebook/src/FacebookImageDriver.php";
require_once __DIR__."/vendor/botman/driver-facebook/src/FacebookFileDriver.php";

//Configurando namespace de clases de botman (Plantillas de facebook)
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;

use BotMan\BotMan\Drivers\DriverManager;

use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\Message;
use BotMan\Drivers\Facebook\Extensions\Element;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

use BotMan\BotMan\Cache\DoctrineCache;
use Doctrine\Common\Cache\FilesystemCache;

//Configurando namespace de clases personalizadas
use BotCredifintech\Constantes;
use BotCredifintec\Conversations\Instituciones\TipoInsitucionConversation;
use BotCredifintech\Conversations\MenuConversation;
use BotCredifintech\Conversations\SalidaConversation;

//Ca,
// Driver de chatbot para Facebook
DriverManager::loadDriver(\BotMan\Drivers\Facebook\FacebookDriver::class);
DriverManager::loadDriver(\Botman\Drivers\Facebook\FacebookImageDriver::class);
DriverManager::loadDriver(\Botman\Drivers\Facebook\FacebookFileDriver::class);

$config = [
    'facebook' =>
	//VILLAHERMOSA
	[
  	'token' => 'EAAlSMcRWR2MBABt9yBCKVKJhqbWPgSNJaafy0FMNeAZBmJFifBnCMmAzgcaEJdcide7D278Li9jUVgcC7do0sk5egdaVJ1gQVKhQOrpO8DBZAuvxg6Wd9CUflCZBYZBdrnvW2AcbreJuqBGRztcHmw6sLxviOVAIBMzJLVLB8AQbxiOMmZCwz',
	'app_secret' => '6d63eba0552f2b24b7a7447f98df19a6',
    'verification'=>'d8wkg9wkflaaeha54qyhf5yadfjaibs3iwro203852',
	]
];

$doctrineCacheDriver = new FilesystemCache(__DIR__);
$botman = BotManFactory::create($config, new DoctrineCache($doctrineCacheDriver));

$botman->hears('^(?!.*\basesor|ASESOR|Asesor\b).*$', function (BotMan $bot) {
/*
  $nombre = $bot->getUser()->getFirstName();
  $incomingMessageText = $bot->getMessage()->getText();
  $nombre = $bot->getUser()->getFirstName();
  */
  $bot -> reply("Mucho gusto");
  $bot -> reply(Constantes::EXPLICAR_SERVICIO);
  $bot->reply("Para regresar a este menú, escriba la palabra 'menu' en cualquier parte de la conversación");
  $bot -> startConversation(new MenuConversation(""));
});


 $botman->hears('no', function (BotMan $bot) {
     $bot->reply('no 🤘');
 });

$botman->hears('.*(Menu|menu|Menú|MENU|menú).*', function(BotMan $bot) {

})->stopsConversation();

$botman->hears('.*(asesor|ASESOR|Asesor).*', function(BotMan $bot) {
    $bot->reply(Constantes::MENSAJE_AYUDA_ASESOR);
})->stopsConversation();

$botman->listen();
//echo "This is botman running";
?>
