<?php

namespace App\Command;

use App\Repository\TvShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\SluggerInterface;

class TvshowSluggerCommand extends Command
{
    // php bin/console tvshow:slugger
    protected static $defaultName = 'tvshow:slugger';

    // php bin/console tvshow:slugger --help
    protected static $defaultDescription = 'Permet de mettre à jour les slugs des séries de l\'entité tvshow.';

    // On déclare les propriétés des services dont nous allons avoir besoin
    private $tvShowRepository;
    private $slugger;
    private $manager;

    // On injecte également les services nécessaires dans le construct pour y avoir accès dans la class (Command)
    public function __construct(TvShowRepository $tvShowRepository, EntityManagerInterface $manager, SluggerInterface $slugger)
    {
        // On récupère les infos du sluggerinterface
        $this->slugger = $slugger;
        // On récupère les infos de toutes les séries
        $this->tvShowRepository = $tvShowRepository;
        // On va sauvegarder nos séries en BDD grâce au manager
        $this->manager = $manager;
        // On récupère le constructeur de la classe parent (Command)
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // php bin/console tvshow:slugger id
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // php bin/console tvshow:slugger id --option
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Etape 1 : on récupère la liste des séries
        $tvshowList = $this->tvShowRepository->findAll();

        // Etape 2 : pour chaque série on va créer le slug approprié et mettre les séries à jour
        foreach ($tvshowList as $tvshow) {
            // On crée le slug pour chaque série grâce à son titre (et on minifie les lettres grâce à strtolower)
            $tvshowSlug = $this->slugger->slug(strtolower($tvshow->getTitle()));
            // On met à jour la propriété slug de chaque série
            $tvshow->setSlug($tvshowSlug);
            $io->text('Mise à jour de la série ' . $tvshow->getTitle() . ' en cours.');
        }
        //Etape 3 : On sauvegarde les séries en BDD
        $this->manager->flush();
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        $io->success('La mise à jour de toutes les séries est un succès');
        // On fait savoir à Symfony que tout s'est bien passé.
        return Command::SUCCESS;
    }
}
