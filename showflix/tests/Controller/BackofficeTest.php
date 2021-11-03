<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackofficeTest extends WebTestCase
{

    /** Test de la page en mode non connecté
     *
     */
    public function testBackofficePagePublic(): void
    {
        $client = static::createClient();
        $client->request('GET', '/backoffice/tvshow');

        // La page /tvshow n'étant accessible qu'aux personnes
        // connectées, on est censé être redirigé vers la page de login
        $this->assertResponseRedirects();
    }

    /**
     * Test de la page si un utilisateur ayant le role : ROLE_USER seulement est connecté
     *
     * @return void
     */
    public function testBackofficePageUserConnected()
    {
        // On créé le client
        $client = static::createClient();

        // Avanht de tester l'accès à la page on v d'abord se connecter en tant que tata@oclock.io

        $userRepository = static::getContainer()->get(UserRepository::class);
        
        // On récupère les infos du user testeur
        $testUser = $userRepository->findOneByEmail('morgane@oclock.io');

        // On simule l'authentification
        $client->loginUser($testUser);
        
        // On teste l'accès à la page si le user a uniquement le role ROLE_USER
        $client->request('GET', '/backoffice/tvshow');

        // Autre méthode :
        // $this->assertEquals(403, $client->getResponse()->getStatusCode());

        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * Test de la page si un utilisateur ayant le role : ROLE_ADMIN est connecté
     *
     * @return void
     */
    public function testBackofficePageAdminConnected()
    {

        // On créé le client
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);
        
        // On récupère les infos de l'admin testeur
        $testUser = $userRepository->findOneByEmail('tata@oclock.io');
        
        // On simule l'authentification
        $client->loginUser($testUser);
        
        // On teste l'accès à la page si le user a uniquement le role ROLE_USER
        $client->request('GET', '/backoffice/tvshow/');
        // dump(in_array("ROLE_ADMIN", $rolesUser));
        // Si l'utilisateur connecté possède le role ROLE_ADMIN
        $this->assertResponseIsSuccessful();
    }
}
