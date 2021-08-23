<?php
namespace App\DataFixtures;

use App\Entity\Character;
use App\Entity\Season;
use App\Entity\TvShow;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 tvshows
        for ($i= 0; $i < 20 ; $i++) {
            $tvShow = new TvShow();
            $tvShow->setTitle('Titre série ' .$i);
            $tvShow->setSynopsis("Dans le futur, les gens peuvent passer un test qui pourrait leur permettre de vivre dans un monde appelé la Haute Mer. Ce monde se définit comme un paradis, mais seulement 3 % des candidats seront retenus.");
            $tvShow->setNbLikes(150000);
            $tvShow->setPublishedAt(new DateTimeImmutable());
            $tvShow->setCreatedAt(new DateTimeImmutable());

            // Création de nouvelles saisons associées à tvShow
            $seasonOne = new Season();
            $seasonOne->setSeasonNumber(1);
            $seasonOne->setPublishedAt(new DateTimeImmutable());
            $seasonOne->setCreatedAt(new DateTimeImmutable());
            $tvShow->addSeason($seasonOne);

            $seasonTwo = new Season();
            $seasonTwo->setSeasonNumber(2);
            $seasonTwo->setPublishedAt(new DateTimeImmutable());
            $seasonTwo->setCreatedAt(new DateTimeImmutable());
            $tvShow->addSeason($seasonTwo);

            $seasonThree = new Season();
            $seasonThree->setSeasonNumber(3);
            $seasonThree->setPublishedAt(new DateTimeImmutable());
            $seasonThree->setCreatedAt(new DateTimeImmutable());
            $tvShow->addSeason($seasonThree);

            $manager->persist($tvShow);
            $manager->persist($seasonOne);
            $manager->persist($seasonTwo);
            $manager->persist($seasonThree);
        }

        // create 20 characters! Bam!
        for ($i = 0; $i < 20; $i++) {
            $character = new Character();
            $character->setFirstname('Prénom Perso '.$i);
            $character->setLastname('Nom Perso '.$i);
            $character->setGender(mt_rand(0, 1) ? 'Homme' : 'Femme');
            $character->setCreatedAt(new DateTimeImmutable());
            $manager->persist($character);
        }

        $manager->flush();
    }
}

