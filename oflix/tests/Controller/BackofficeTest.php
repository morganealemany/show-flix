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

        $this->assertResponseStatusCodeSame(302);
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
        $userRepository = static::getContainer()->get(UserRepository::class);
        
        // On récupère les infos du user testeur
        $testUser = $userRepository->findOneByEmail('toto@oclock.io');
        // dump($testUser->getRoles());
        // On récupère les roles du user
        $rolesUser = $testUser->getRoles();

        // On simule l'authentification
        $client->loginUser($testUser);
        
        // On teste l'accès à la page si le user a uniquement le role ROLE_USER
        $client->request('GET', '/backoffice/tvshow');

        // Si l'utilisateur connecté n'a qu'un seul role : le role par défaut qui est ROLE_USER
        if (count($rolesUser) == 1) {
            $this->assertResponseStatusCodeSame(403);
        }
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
        $testUser = $userRepository->findOneByEmail('toto@oclock.io');
        // dump($testUser->getRoles());
        // On récupère les roles du user
        $rolesUser = $testUser->getRoles();

        // On simule l'authentification
        $client->loginUser($testUser);
        
        // On teste l'accès à la page si le user a uniquement le role ROLE_USER
        $client->request('GET', '/backoffice/tvshow');
        // dump(in_array("ROLE_ADMIN", $rolesUser));
        // Si l'utilisateur connecté possède le role ROLE_ADMIN
        if (in_array("ROLE_ADMIN", $rolesUser)) {
            $this->assertEquals(200, $client->getResponse()->getStatusCode());       
        }

        $this->assertResponseStatusCodeSame(403);
    }
}
