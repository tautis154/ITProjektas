<?php

namespace App\Command;

use App\Repository\SpecialistRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\User\UserInterface;

class PromoteUserCommand extends Command
{
    protected static $defaultName = 'app:promote-user';

    /** @var SpecialistRepository */
    private $userRepository;
    private $adminRole;

    /**
     * PromoteUserCommand constructor.
     * @param SpecialistRepository $userRepository
     * @param string $adminRole
     */
    public function __construct(SpecialistRepository $userRepository, $adminRole = 'ROLE_ADMIN')
    {
        $this->userRepository = $userRepository;
        $this->adminRole = $adminRole;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Make specialist admin')
            ->addArgument('username', InputArgument::REQUIRED, 'Username of existing user');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws NonUniqueResultException|ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Parsing input
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');

        // Getting user
        $this->info("Searching for user", $username, $io);
        $user = $this->userRepository->findByEmail($username);
        if (!$user) {
            $io->error("Cannot find user by username: " . $username);
            return;
        }

        if (in_array($this->adminRole, $user->getRoles())) {
            $this->printUserRoles($user, $io);
            $io->success('Admin role already exists');
            return;
        }

        // Adding admin role
        $this->info("Adding role: ", $this->adminRole, $io);
        $roles = $user->getRoles();
        $roles[] = $this->adminRole;
        $user->setRoles(array_unique($roles));

        // Storing user
        $this->userRepository->save($user);

        $this->printUserRoles($user, $output);
        $io->success('Admin role successfully added');
    }

    private function info($message, $value, OutputInterface $io)
    {
        $io->writeln(sprintf('<info>%s</info>: <comment>%s</comment>', $message, $value));
    }

    private function printUserRoles(UserInterface $user, OutputInterface $io)
    {
        $io->writeln(
            "<info>User roles</info>: <comment>" . join('</comment>, <comment>', $user->getRoles()) . '</comment>'
        );
    }
}