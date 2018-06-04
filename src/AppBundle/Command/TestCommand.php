<?php
// src/Command/CreateUserCommand.php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command{
	
    protected function configure(){
        $this
        // the name of the command (the part after "bin/console")
        ->setName('app:test')

        // the short description shown while running "php bin/console list"
        ->setDescription('Creates a new user.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $chemin ='/applications/Geoffrey/emma/CreationViews.log';
		$fichier_log = fopen($chemin, 'w+');
		fwrite($fichier_log,"Nombre de vues générées : ");
		fclose($fichier_log);
    }
}