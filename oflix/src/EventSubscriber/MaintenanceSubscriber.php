<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(ResponseEvent $event)
    {     
            // On récupère la réponse de l'event
            $response = $event->getResponse();
            // On récupère son contenu HTML
            $content = $response->getContent();
            // dd($response, $content);
            // On créé le texte voulu dans le contenu grâce à str_replace
            $newBody = str_replace("<body>", "<body><div class=\"alert alert-danger\">Maintenance prévue mardi 7 septembre à 17h00</div>", $content);
            // Puis on l'enregistre dans la variable $content
            $newResponse = $response->setContent($newBody);
            // Enfin on affecte les changements à response
            $event->setResponse($newResponse);
            // return $response->getContent();
            // dd($newResponse);
    
    }

    public static function getSubscribedEvents()
    {
        if ($_ENV['IN_MAINTENANCE']) {
            return [
            'kernel.response' => 'onKernelResponse',
        ];
        }
    }
}
