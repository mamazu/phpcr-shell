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

use PHPCR\SimpleCredentials;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SessionImpersonateCommand extends BasePhpcrCommand
{
    protected function configure(): void
    {
        $this->setName('session:impersonate');
        $this->setDescription('Impersonate the given user');
        $this->addArgument('username', InputArgument::REQUIRED, 'Username of user to impersonate');
        $this->setHelp(
            <<<'HERE'
Note: This command is not implemented by any of the transports currently.

Returns a new session in accordance with the specified (new)
Credentials.
HERE
        );

        $this->dequiresDescriptor('jackalope.not_implemented.session.impersonate');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $session = $this->get('phpcr.session');
        $username = $input->getArgument('username');

        $credentials = new SimpleCredentials($username, '');
        $session->impersonate($credentials);

        return 0;
    }
}
