<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestDemoSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $server = $request->server;
        $remoteIp = $server->get('REMOTE_ADDR');

        // Si l'adresse ip de l'utilisateur qui a formulé la requête est dans une blacklist par exemple alors on peut lui interdire l'accès à nos pages en affichant un message d'erreur et en renvoyant un code 403
        if (in_array($remoteIp, ['189.10.20.6', '154.21.3.59'])) 
        {
            $response = new Response('Vous ne pouvez pas accèder à ce contenu.', 403);
            $event->setResponse($response);
        }
        // Sinon symfony continue là suivre les étapes pour afficher le contenu.
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}
