<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
{

    /**
     * Test de la page d'accueil en mode non connecté (public)
     *
     * @return void
     */
    public function testHomePagePublic(): void
    {
        // On va se mettre dans le peau d'un navigateur et tenté d'accèder à la page d'accueil ("/")
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // On vérifie ensuite si la page existe bien.
        // Si c'est OK alors la page est potentiellement fonctionnelle
        $this->assertResponseIsSuccessful();
        // On vérifie qu'il existe une balise h1 avec le contenu: "Séries TV et bien plus en illimité."
        $this->assertSelectorTextContains('h1', 'Séries TV et bien plus en illimité.');
    }

    /**
     * Test de la page en mode connecté
     *
     * @return void
     */
    public function testHomePageConnected()
    {
        // Etape 1 : on créé le client
        $client= static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // Etape 2 : On choisit un utilisateur et on récupère ses infos

        // $testUser = $userRepository->findOneByFirstName('Charles');
        // findOneBy(["email" => "charles@oclock.io"])
        $testUser = $userRepository->findOneByEmail(('tata@oclock.io'));

        // Etape 3 : On simule une authentification
        // Simulation de la saisie d'un login + mot de passe
        $client->loginUser(($testUser));

        // Etape 4 : on teste l'accès à la page d'accueil en tant qu'utilisateur connecté
        $crawler = $client->request('GET', '/');

        // On vérifie que la page est dispo
        $this->assertResponseIsSuccessful();

        // On vérifie que dans la page d'accueil on retrouve bien le texte suivant : Bienvenue leprénom
        $this->assertSelectorTextContains('p.welcome_user', 'Bienvenue ' . $testUser->getEmail());
    }
}
