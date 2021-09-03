<?php

namespace App\Command;

use App\Repository\TvShowRepository;
use DateTimeImmutable;
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
            // php bin/console tvshow:slugger 2
            ->addArgument('id', InputArgument::OPTIONAL, 'Identifant de la série à modifier')
            // php bin/console tvshow:slugger id --updatedAt
            ->addOption('updatedAt', null, InputOption::VALUE_NONE, 'Option de mise à jour de la propriété updatedAt')
        ;
    }

    /**
     * C'est dans cette méthode qu'on va coder toute la logique
     * métier de notre commande
     * 
     * On va générer les slugs de toutes nos séries O'flix
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $tvshowId = $input->getArgument('id');
        $updatedAtOption = $input->getOption('updatedAt');

        if ($tvshowId) {
            $io->note(sprintf('You passed an argument: %s', $tvshowId));
            $tvshow = $this->tvShowRepository->find($tvshowId);
            if ($tvshow) {

                $this->createSlug($tvshow, $io, $updatedAtOption);
            } else {
                $io->error('La série dont l\'id est ' . $tvshowId . ' n\'est pas définie.');
                return Command::INVALID;
            }
        }else {
            // Etape 1 : on récupère la liste des séries
            $tvshowList = $this->tvShowRepository->findAll();

            // Etape 2 : pour chaque série on va créer le slug approprié et mettre les séries à jour
            foreach ($tvshowList as $tvshow) {
                $this->createSlug($tvshow, $io, $updatedAtOption);
            }
        }

        if ($input->getOption('updatedAt')) {
            // ...
        }

        //Etape 3 : On sauvegarde les séries en BDD
        $this->manager->flush();

        $io->success('La mise à jour de toutes les séries est un succès');
        // On fait savoir à Symfony que tout s'est bien passé.
        return Command::SUCCESS;
    }

    /**
     * Factorisation du code permettant de gérer la mise à jour du slug
     */
    private function createSlug($tvshow, $io, $updatedAt)
    {
        // On crée le slug 
        $tvshowSlug = $this->slugger->slug(strtolower($tvshow->getTitle()));
        $io->text('Mise à jour de la série ' . $tvshow->getTitle() . ' en cours.');
        // On met à jour la propriété slug de la série
        $tvshow->setSlug($tvshowSlug);

        if($updatedAt) {
            // Si l'option updatedAt est définie dans la commande, alors on met à jour la propriété.
            $tvshow->setUpdatedAt(new DateTimeImmutable());
        }
    }
}
