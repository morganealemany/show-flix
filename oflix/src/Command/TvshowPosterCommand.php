<?php

namespace App\Command;

use App\Repository\TvShowRepository;
use App\Service\OmdbApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TvshowPosterCommand extends Command
{
    // Nom de la commande à saisir dans le terminal
    // php bin/console tvshow:poster
    protected static $defaultName = 'tvshow:poster';

    // Permet d'afficher la description de la commande lorsqu'on la lance avec l'option --help
    // php bin/console tvshow:poster --help
    protected static $defaultDescription = 'Permet de mettre à jour les posters de toutes les séries à l\'aide d\'une API';

     // Lorsque l'on souhaite appeler un Service dans une classe
    // autre qu'un controleur, on est obligé  de créer un constructeur
    // pour effectuer l'injection de ce service
    private $omdbApi;
    private $tvShowRepository;
    private $manager;
    public function __construct(OmdbApi $omdbApi, TvShowRepository $tvShowRepository, EntityManagerInterface $manager)
    {
        // On va pouvoir récupérer les séries
        $this->tvShowRepository = $tvShowRepository;

        // On va récupérer les posters pour chaque série
        $this->omdbApi = $omdbApi;

        // On va sauvegarder nos séries en BDD
        $this->manager = $manager;

        // On appelle le constructeur de la classe parent Command
        parent::__construct();
    }

    protected function configure(): void
    {
        // Ici on met à jour uniquement la série dont l'id est égal à 2 et au passage on met à jour la colonne updatedAt
        // php bin/console tvhow:poster 2 --updatedAt
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        //On va mettre à jour la propriété `image` à partir des données issues de OmdbApi

        // Etape 1 : on récupère toutes les séries de notre BDD
        $tvShowList = $this->tvShowRepository->findAll();

        // dd($tvShowList);
        
        // Etape 2 : pour chaque série on va interroger OmdbApi et récupérer le poster
        foreach ($tvShowList as $tvShow) {
            // On récupère le titre de chaque série
            $title = $tvShow->getTitle();
            // Puis on récupère les infos de la série depuis l'api
            $tvShowData = $this->omdbApi->fetch($title);
            // On vérifie qu'il y a bien un poster associé à chaque série avant de changer les images.
            if (isset($tvShowData['Poster'])) {
                $tvShow->setImage($tvShowData['Poster']);
                $io->text('Mise à jour de la série' . $title . ' en cours...');
            } else {
                $io->text($title . 'n\a pas de poster correspondant.');
            }
        }

        // Etape 3 : on appelle le manager pour sauvegarder les séries en BDD
        $this->manager->flush();

        $io->success('Mise à jour de toutes les séries effectuée avec succès !');

        return Command::SUCCESS;
    }
}
