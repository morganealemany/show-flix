<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{

    /**
     * Test de la page d'accueil en mode non connecté
     *
     * @return void
     */
    public function testLoginPagePublic(): void
    {
        // On simule l'accès à la page /login via un navigateur intégré
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Est ce que la page répond correctement
        $this->assertResponseIsSuccessful();

        // Ici 2 assertions : vérification de la présence du h1 et vérification de son contenu 'Merci de vous connecter'
        $this->assertSelectorTextContains('h1', 'Merci de vous connecter');
    }
}
