<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(ResponseEvent $event)
    {     
        // On récupère la réponse que le serveur s'apprête à retourner au client
        $request = $event->getRequest();

        // Possibilité de changer la variable d'environnement (2 façons)
        // $_ENV['MAINTENANCE_MSG'] = 'toto';
        // $request->server->set('MAINTENANCE_MSG', 'toto');
        // -----------------------------------------------------
        
        // Utilisation de la variable d'environnement pour générer ou non le bandeau de maintenance.
        $onMaintenance = $request->server->get('IN_MAINTENANCE');
        $maintenanceMsg = $request->server->get('MAINTENANCE_MSG');

        if ($onMaintenance == 1) {
            // On récupère la réponse de l'event
            $response = $event->getResponse();
            
            // On récupère son contenu HTML
            $content = $response->getContent();
            // dd($response, $content);
            // On créé le texte voulu dans le contenu grâce à str_replace
            $newBody = str_replace('<body>', '<body><div class="mb-0 alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>' . $maintenanceMsg .'</div>', $content);
            
            // Puis on l'enregistre dans la variable $content
            $newResponse = $response->setContent($newBody);
            
            // Enfin on affecte les changements à response
            $event->setResponse($newResponse);
            // dd($newResponse);
        }
    }

    public static function getSubscribedEvents()
    {
            return [
            // Lorsque l'événement kernel.response est déclenché, Symfony va appeller la méthode onKernelResponse
            'kernel.response' => 'onKernelResponse',
        ];
        
    }
}
