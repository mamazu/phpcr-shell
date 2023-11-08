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

use PHPCR\Shell\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorkspaceListCommand extends BasePhpcrCommand
{
    protected function configure(): void
    {
        $this->setName('workspace:list');
        $this->setDescription('Lists workspaces in the current repository');
        $this->addArgument('srcWorkspace', InputArgument::OPTIONAL, 'If specified, clone from this workspace');
        $this->setHelp(
            <<<'HERE'
Lists the workspaces accessible to the current user.

The current workspace is indicated by an asterix (*).

Lists the names of all workspaces in this
repository that are accessible to this user, given the Credentials that
were used to get the Session to which this Workspace is tied.
In order to access one of the listed workspaces, the user performs
another <info>session:login</info>, specifying the name of the desired
workspace, and receives a new Session object.
HERE
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $session = $this->get('phpcr.session');

        $workspace = $session->getWorkspace();
        $availableWorkspaces = $workspace->getAccessibleWorkspaceNames();

        $table = new Table($output);
        $table->setHeaders(['Name']);
        foreach ($availableWorkspaces as $availableWorkspace) {
            if ($availableWorkspace == $workspace->getName()) {
                $availableWorkspace .= ' *';
            }
            $table->addRow([$availableWorkspace]);
        }

        $table->render($output);

        return 0;
    }
}
