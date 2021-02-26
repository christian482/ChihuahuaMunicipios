<?php

namespace BotCredifintech\Conversations;

require __DIR__ . './../../vendor/autoload.php';

require_once __DIR__ . "./../Constantes.php";
require_once __DIR__ . "/SolicitarDatosConversation.php";
require_once __DIR__."/SalidaConversation.php";

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\Extensions\Message;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

use Mpociot\BotMan\Cache\DoctrineCache;

use BotCredifintech\Constantes;
use BotCredifintech\Conversations\SalidaConversation;
use BotCredifintech\Conversations\SolicitarDatosConversation;

class MenuConversation extends Conversation
{
  protected $tipoInstitucion;

  protected $errores = 0;

  protected $conversation;

  public function menu()
  {

      $question = Question::create(Constantes::SELECCIONE_OPCION)
        ->callbackId('ask_institucion')
        ->addButtons([
            Button::create('Soy Jubilado IMSS')->value('JubiladoIMSS'),
            Button::create('Soy Pensionado IMSS')->value('PensionadoIMSS'),
            Button::create('No soy pensionado ni jubilado imss')->value('Ninguno'),
        ]);
      //$this->say("Para regresar a este menú, escriba la palabra 'menu' en cualquier parte de la conversación");
      //$this->say(Constantes::PREGUNTAR_TE_URGE_UN_PRESTAMO);
      $this->ask($question, function(Answer $answer) {
          if ($answer->isInteractiveMessageReply()) {
            $this->errores = 0;
            $selectedValue = $answer->getValue();

            if($selectedValue=="No"){
              $this->bot->startConversation(new SalidaConversation());
            }
            $this->bot->startConversation(new SolicitarDatosConversation($selectedValue));


          } else {
              $this->errores += 1;
              if($this->errores >= 3){
                $this->llamarAsesor();
              } else {
                $this->say(Constantes::MENSAJE_NAVEGACION_BOTONES);
                $this->menu();
              }

          }
      });
  }


  //Funciones para llamar al asesor

  public function llamarAsesor(){
    $this->say(Constantes::MENSAJE_AYUDA_ASESOR);
  }

  public function stopsConversation(IncomingMessage $message)
	{
		if (strcasecmp($message->getText(), 'asesor') == 0) {
      $this->say("La conversación se ha detenido, espere al asesor");
			return true;
		}
		return false;
	}

  public function run()
  {
      // This will be called immediately
      $this->menu();
  }
}

?>
