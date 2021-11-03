<?php

namespace App\Command;

use App\Entity\Character;
use App\Repository\TvShowRepository;
use App\Service\BetaserieApi;
use App\Service\OmdbApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TvShowDataCommand extends Command
{
    protected static $defaultName = 'tvshow:data';
    protected static $defaultDescription = 'Permet de mettre à jour les données de toutes les séries à l\'aide d\'une API';

    private $betaserieApi;
    private $tvShowRepository;
    private $manager;
    public function __construct(OmdbApi $omdbApi, BetaserieApi $betaserieApi, TvShowRepository $tvShowRepository, EntityManagerInterface $manager)
    {
        $this->tvShowRepository = $tvShowRepository;

        $this->betaserieApi = $betaserieApi;

        $this->omdbApi = $omdbApi;

        $this->manager = $manager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
       
        $tvShowList = $this->tvShowRepository->findAll();

        foreach ($tvShowList as $tvShow) {

            $title = $tvShow->getTitle();

            //Using omdb API to find actors 
            $tvShowSearchFromOmdbApi = $this->omdbApi->fetch($title);

            if ($tvShowSearchFromOmdbApi) {
                $tvShowActorsArray = explode(", ", $tvShowSearchFromOmdbApi['Actors']);
                // dump($tvShowActorsArray);
                foreach ($tvShowActorsArray as $actor) {
                    $actorArray = explode(" ", $actor);
                    dump($actorArray);
                    $character = new Character();
                }
            }
            // dump($tvShowSearchFromOmdbApi);s
            // Using betaserie API to find the french description and posters
            $tvShowSearchArray = $this->betaserieApi->fetchTvShowId($title);
            // dump($tvShowId);
            // If the tvshow id was found by betaserieApi:fetchTvShowId
            if (array_key_exists(0, ($tvShowSearchArray['shows']))) {
                $tvShowId = $tvShowSearchArray['shows'][0]['id'];
                // Search all datas for the tv show byt its id
                $tvShowDataArray = $this->betaserieApi->fetchTvShowData($tvShowId);
                // dump($title, $tvShowDataArray['show']['description']);
                // If there is a description in tv show datas
                if (isset($tvShowDataArray['show']['description'])) {
                    // Update the database with the description
                    $tvShow->setSynopsis($tvShowDataArray['show']['description']);
                    $io->text('Mise à jour de la description de la série' . $title . ' en cours...');
                }
                if (isset($tvShowDataArray['show']['images']['poster'])) {
                    // Update the database with the description
                    $tvShow->setImage($tvShowDataArray['show']['images']['poster']);
                    $io->text('Mise à jour de l\'image de la série' . $title . ' en cours...');
                }
           
            }

        }

        // $this->manager->flush();

        $io->success('Les données ont bien été mises à jour');

        return Command::SUCCESS;
    }
}
