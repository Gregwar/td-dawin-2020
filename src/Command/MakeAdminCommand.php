<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeAdminCommand extends Command
{
    protected static $defaultName = 'app:make-admin';
    protected $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }

    protected function configure()
    {
        $this
            ->setDescription('Donner le role ADMIN à un utilisateur')
            ->addArgument('email', InputArgument::REQUIRED, 'E-mail')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $em = $this->doctrine->getManager();

        $user = $this->doctrine->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);

        if ($user) {
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            $em->flush();
            $io->success("Role admin donné");
        } else {
            $io->error("Impossible de trouver l'utilisateur");
        }

        return 0;
    }
}
