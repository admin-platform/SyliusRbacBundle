<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\RbacBundle\Command;

use Sylius\Bundle\RbacBundle\Doctrine\RbacInitializer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class InitializeCommand extends Command
{
    /**
     * @var RbacInitializer
     */
    private $rbacInitializer;

    /**
     * InitializeCommand constructor.
     * @param null|string $name
     * @param RbacInitializer $rbacInitializer
     */
    public function __construct(RbacInitializer $rbacInitializer, string $name = null)
    {
        parent::__construct($name);
        $this->rbacInitializer = $rbacInitializer;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sylius:rbac:initialize')
            ->setDescription('Initialize default permissions & roles in the application.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command initializes default RBAC setup.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Initializing Sylius RBAC roles and permissions.');

        $this->rbacInitializer->initialize($output);
        $output->writeln('<info>Completed!</info>');

        return 0;
    }
}
