<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OflixCreatingfilesCommand extends Command
{
    protected static $defaultName = 'oflix:creatingfiles';
    protected static $defaultDescription = 'Permet la création de deux dossiers : public/images et public/css2';

    protected function configure(): void
    {
        $this
            // InputArgument::REQUIRED : signifie que l'on ne pourra pas 
            // lancer la commande sans préciser l'argument folder
            // php bin/console oflix:assets folder

            // InputArgument::OPTIONAL : signifie que l'on peux
            // lancer la commande sans préciser l'argument folder
            // php bin/console oflix:assets

            ->addArgument('folder', InputArgument::OPTIONAL, 'Le dossier que l\'on souhaite créer')
            ->addOption('addtogit', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $folder = $input->getArgument('folder');

        if ($folder) {
            // Un argument a été saisie dans la commande
            // php bin/console oflix:creatingfiles argument
            //si $folder = javascript on va créer le dossier public/javascript
            $folderToCreate = 'public/' . $folder;
            mkdir($folderToCreate);

            // Si l'option addtogit est présent
            // alors on créé un fichier .keep
            $optionGit = $input->getOption('addtogit');
            
            if ($optionGit) {
                // public/js/.keep
                $keepFile = $folderToCreate . '/.keep';

                // Function file_put_contents : https://www.php.net/manual/fr/function.file-put-contents.php
                file_put_contents($keepFile, '<?php echo "Je suis ton père!";');
            }

            $io->success('Le dossier ' . $folder . ' a bien été créé');
        }
        // dd('stop');


        // On veut créer 2 dossiers :
        // Fonction mkdir : https://www.php.net/manual/fr/function.mkdir.php

        // // - public/css2
        // mkdir('public/css2');
        // // - public/images
        // mkdir('public/images');

        $io->success('Les dossiers ont bien été créés');

        return Command::SUCCESS;
    }
}
