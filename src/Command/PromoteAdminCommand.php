<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\RoleManager;

use phpDocumentor\Reflection\Types\Parent_;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


#[AsCommand(
    name: 'app:promote-admin',
    description: 'Promouvoir un utilisateur en ROLE_ADMIN',
)]
class PromoteAdminCommand extends Command
{

    private UserRepository $userRepository;
    private RoleManager $roleManager;

    public function __construct(UserRepository $userRepository, RoleManager $roleManager)
    {
        Parent::__construct();
        $this->userRepository = $userRepository;
        $this->roleManager = $roleManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'utilisateur à promouvoir');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Lire l'email fourni en argument
        $email = $input->getArgument('email');

        // Trouver l'utilisateur par email
        $user = $this->userRepository->findByOne($email);

        if (!$user) {
            $io->error("Aucun utilisateur trouvé avec l'email : {$email}.");
            return \Symfony\Component\Console\Command\Command::FAILURE;
        }

        try {
            // Promouvoir l'utilisateur en utilisant le `RoleManager`
            $this->roleManager->promoteToAdmin($user);
            $io->success("L'utilisateur {$email} a été promu au rôle ROLE_ADMIN avec succès.");
            return \Symfony\Component\Console\Command\Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Une erreur est survenue lors de la promotion : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}