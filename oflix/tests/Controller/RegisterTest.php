<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTest extends WebTestCase
{

    /**
     * Test de la page en mode non connecté
     *
     * @return void
     */
    public function testRegisterPagePublic(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Hello World');
        $this->assertSelectorTextContains('h1', 'Création de compte');

    }
}
