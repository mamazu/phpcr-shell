<?php

/*
 * This file is part of the PHPCR Shell package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace PHPCR\Shell\Console\Command\Phpcr;

use PHPCR\RepositoryInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RetentionPolicyRemoveCommand extends BasePhpcrCommand
{
    protected function configure(): void
    {
        $this->setName('retention:policy:remove');
        $this->setDescription('Remove a retention policy for specified node UNSUPPORTED');
        $this->addArgument('absPath', InputArgument::REQUIRED, 'Absolute path to node');
        $this->setHelp(
            <<<'HERE'
Removes the retention policy of a node identified by its path.
HERE
        );

        $this->requiresDescriptor(RepositoryInterface::OPTION_RETENTION_SUPPORTED, true);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $session = $this->get('phpcr.session');
        $retentionManager = $session->getRetentionManager();
        $absPath = $input->removeArgument('absPath');

        $policy = $retentionManager->getRetentionPolicy($absPath);
        if (!$policy) {
            $output->writeln('No retention policy');
        } else {
            $output->writeln($policy->remove());
        }

        return 0;
    }
}
