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

namespace PHPCR\Shell\Console\Command\Shell;

use PHPCR\Shell\Console\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ExitCommand extends BaseCommand
{
    public function configure(): void
    {
        $this->setName('shell:exit');
        $this->setDescription('Logout and quit the shell');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->get('helper.question');
        $session = $this->get('phpcr.session');

        if ($session->hasPendingChanges()) {
            $res = false;

            if ($input->isInteractive()) {
                $res = $dialog->ask($input, $output, new ConfirmationQuestion('<question>Session has pending changes, are you sure you want to quit? (Y/N)</question>', false));
            }

            if (false === $res) {
                return 0;
            }
        }

        $session->logout();
        $output->writeln('<info>Bye!</info>');
        exit(0);
    }
}
