<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryTest extends WebTestCase
{
    /**
     * Test de l'ajout d'une catégorie dans le backoffice
     *
     * @return void
     */
    public function testCategoryAdd(): void
    {
        // Doc symfony : https://symfony.com/doc/current/testing.html#submitting-forms
        // On créé le client
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);
        
        // On récupère les infos de l'admin testeur
        $testUser = $userRepository->findOneByEmail('tata@oclock.io');
        
        // On simule l'authentification
        $client->loginUser($testUser);
        
        // On accède au formulaire d'ajout d'une catégorie
        $crawler = $client->request('GET', '/backoffice/category/add');

        // On simule le submit du formulaire
        // Ici la méthode submitForm va chercher le bouton submit du formulaire contenant le texte "Valider"
        $crawler = $client->submitForm('Valider', [
            // Ici on sélectionne le name de l'input du formulaire et on lui affecte un contenu
            'category[name]' => 'Drame',
        ]);

        // On vérifie qu'au submit, on est redirigé vers la page /backoffice/category
        $this->assertResponseRedirects('/backoffice/category/');    }
}
