<?php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Character;
use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\TvShow;
use App\Repository\TvShowRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager; 

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $tvShowRepository = new TvShowRepository()->findAll();
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Character($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\TvShow($faker));
      
        for ($categoryId = 0; $categoryId < 4; $categoryId++) {
            $category = new Category();
            $category->setname($faker->movieGenre);
            $manager->persist($category);

            for ($tvShowId = 0; $tvShowId < mt_rand(2, 4); $tvShowId++) {
                $tvShow = new TvShow();
                $tvShow->setTitle($faker->tvShow);
                $tvShow->setSynopsis($faker->overview);
                $tvShow->setPublishedAt( new DateTimeImmutable());
                $tvShow->addCategory($category);
                $manager->persist($tvShow);

                for ($sesonId = 1; $sesonId < mt_rand(3, 8); $sesonId++) {
                    $season = new Season();
                    $season->setSeasonNumber($sesonId);
                    $season->setTvShow($tvShow);
                    $season->setCreatedAt(new DateTimeImmutable());
                    $manager->persist($season);

                    for ($episodeId = 1; $episodeId < mt_rand(7, 15); $episodeId++) {
                        $episode = new Episode();
                        $episode->setEpisodeNumber($episodeId);
                        $episode->setTitle($faker->movie);
                        $episode->setSeason($season);
                        $episode->setPublishedAt(new DateTimeImmutable());
                        $episode->setCreatedAt(new DateTimeImmutable());
                        $manager->persist($episode);
                    }
                }

                for ($characterId = 0; $characterId < mt_rand(8, 18); $characterId++) {
                    $gender = mt_rand(0, 1) ? 'male' : 'female';
                    $fullNameArray = explode(" ", $faker->character($gender));
                    $character = new Character();

                    $character->setFirstname($fullNameArray[0]);
                    $character->setLastname($fullNameArray[1]?? 'Doe');
                    $character->setGender($gender == 'male' ? 'Homme' : 'Femme');
                    $character->addTvShow($tvShow);
                    $manager->persist($character);
                }
            }
        }
        
        $manager->flush();
    }
}