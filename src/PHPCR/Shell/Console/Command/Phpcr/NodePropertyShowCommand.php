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

use PHPCR\PathNotFoundException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NodePropertyShowCommand extends BasePhpcrCommand
{
    protected function configure(): void
    {
        $this->setName('node:property:show');
        $this->setDescription('Show the property at the given path');
        $this->addArgument('path', InputArgument::REQUIRED, 'Path to property (can include wildcards)');
        $this->setHelp(
            <<<'HERE'
Show the full value of a property at the given path
HERE
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $session = $this->get('phpcr.session');
        $path = $session->getAbsPath($input->getArgument('path'));
        $resultFormatHelper = $this->get('helper.result_formatter');
        $pathHelper = $this->get('helper.path');
        $resultFormatHelper = $this->get('helper.result_formatter');

        $parentPath = $pathHelper->getParentPath($path);
        $filter = $pathHelper->getNodeName($path);
        $nodes = $session->findNodes($parentPath);

        if (0 === count($nodes)) {
            throw new \Exception('Could not find property(s) at path '.$path);
        }

        foreach ($nodes as $node) {
            try {
                $properties = [$node->getProperty($filter)];
            } catch (PathNotFoundException $e) {
                $properties = $node->getProperties($filter);
            }

            if (0 === count($properties)) {
                throw new \Exception('Could not find property(s) at path '.$path);
            }

            foreach ($properties as $property) {
                $output->writeln(sprintf(
                    '<pathbold>%s%s</pathbold><localname>%s</localname>: %s',
                    $parentPath = $pathHelper->getParentPath($property->getPath()),
                    $parentPath != '/' ? '/' : '',
                    $pathHelper->getNodeName($property->getPath()),
                    $resultFormatHelper->formatValue($property, true, false)
                ));
            }
        }

        return 0;
    }
}
