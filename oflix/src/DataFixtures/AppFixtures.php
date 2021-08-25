<?php
namespace App\DataFixtures;

use App\Entity\Character;
use App\Entity\Season;
use App\Entity\TvShow;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Création de l'instance Faker Generator
        $faker = Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Character($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\TvShow($faker));


        // create 20 tvshows
        for ($i= 0; $i < 20 ; $i++) {
            $tvShow = new TvShow();
            $tvShow->setTitle($faker->tvShow);
            $tvShow->setSynopsis($faker->overview);

            // Création de nouvelles saisons associées à tvShow
            $seasonOne = new Season();
            $seasonOne->setSeasonNumber(1);
            $tvShow->addSeason($seasonOne);

            $seasonTwo = new Season();
            $seasonTwo->setSeasonNumber(2);
            $tvShow->addSeason($seasonTwo);

            $seasonThree = new Season();
            $seasonThree->setSeasonNumber(3);
            $tvShow->addSeason($seasonThree);

            $manager->persist($tvShow);
            $manager->persist($seasonOne);
            $manager->persist($seasonTwo);
            $manager->persist($seasonThree);
        }

        // create 20 characters! Bam!
        for ($i = 0; $i < 20; $i++) {
            $gender = mt_rand(0, 1) ? 'male' : 'female';
            $fullNameArray = explode(" ", $faker->character($gender));
            $character = new Character();
            $character->setFirstname($fullNameArray[0]);
            $character->setLastname($fullNameArray[1] ?? 'Doe' .$i);
            $character->setGender($gender == 'male' ? 'Homme' : 'Femme');
            $character->setBio('Aliquam porttitor nisl at ante mattis tempus. Fusce sed enim tincidunt, elementum odio ac, facilisis purus. Sed gravida pulvinar quam, at bibendum purus aliquam et. Phasellus eu felis ipsum. Aenean vitae aliquam metus. Nam placerat bibendum lectus eget ultricies. Etiam non dapibus ante. Mauris eget massa eget nulla facilisis varius. Vestibulum hendrerit neque eget mi pretium tincidunt. Sed non feugiat lorem.');
            $character->setAge(mt_rand(10, 90));

            // On met le personnage en liste d'attente
            $manager->persist($character);
        }

        // On sauvegarde/créé les nouvelles infos en BDD
        $manager->flush();
    }

}

