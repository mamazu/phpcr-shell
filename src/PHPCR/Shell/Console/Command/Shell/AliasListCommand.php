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
use PHPCR\Shell\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AliasListCommand extends BaseCommand
{
    public function configure()
    {
        $this->setName('shell:alias:list');
        $this->setDescription('List all the registered aliases');
        $this->setHelp(
            <<<'EOT'
List the aliases as defined in <info>~/.phpcrsh/aliases.yml</info>.
EOT
        );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->get('config.manager');
        $aliases = $config->getConfig('alias');

        $table = new Table($output);
        $table->setHeaders(['Alias', 'Command']);

        foreach ($aliases as $alias => $command) {
            $table->addRow([$alias, $command]);
        }

        $table->render($output);

        return 0;
    }
}
