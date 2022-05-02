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

namespace PHPCR\Shell\Subscriber;

use PHPCR\Shell\Config\Profile;
use PHPCR\Shell\Config\ProfileLoader;
use PHPCR\Shell\Event\PhpcrShellEvents;
use PHPCR\Shell\Event\ProfileInitEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProfileLoaderSubscriber implements EventSubscriberInterface
{
    protected $profileLoader;
    protected $questionHelper;

    public static function getSubscribedEvents()
    {
        return [
            PhpcrShellEvents::PROFILE_INIT => 'handleProfileInit',
        ];
    }

    public function __construct(ProfileLoader $profileLoader, $questionHelper)
    {
        $this->profileLoader = $profileLoader;
        $this->questionHelper = $questionHelper;
    }

    public function handleProfileInit(ProfileInitEvent $e)
    {
        $profile = $e->getProfile();
        $input = $e->getInput();
        $output = $e->getOutput();
        $transport = $input->getOption('transport');
        $profileName = $input->getOption('profile');

        if ($profileName && null === $transport) {
            $profile->setName($profileName);
            $this->profileLoader->loadProfile($profile);
            $this->showProfile($output, $profile);
        }

        if (null === $profileName && null === $transport) {
            $profileNames = $this->profileLoader->getProfileNames();

            if (count($profileNames) === 0) {
                $output->writeln('<info>No transport specified, and no profiles available.</info>');
                $output->writeln(
                    <<<'EOT'

You must specify the connection parameters, for example:

    $ phpcrsh --transport=jackrabbit

Or:

    $ phpcrsh --transport=doctrine-dbal --db-name=mydb

You can create profiles by using the <info>--profile</info> option:
            }
    $ phpcrsh --profile=mywebsite --transport=doctrine-dbal --db-name=mywebsite

Profiles can then be used later on:

    $ phpcrsh --profile=mywebsite
EOT
                );

                exit(1);
            }

            $output->writeln('<info>No connection parameters, given. Select an existing profile:</info>');
            $output->writeln('');

            foreach ($profileNames as $i => $profileName) {
                $output->writeln(sprintf('  (%d) <comment>%s</comment>', $i, $profileName));
            }

            $output->writeln('');

            $selectedName = null;
            while (null === $selectedName) {
                $number = $this->questionHelper->ask($input, $output, new Question('<info>Enter profile number</info>: '));

                if (!isset($profileNames[$number])) {
                    $output->writeln('<error>Invalid selection!</error>');
                    continue;
                }

                $selectedName = $profileNames[$number];
            }

            $profile->setName($selectedName);
            $this->profileLoader->loadProfile($profile);
            $this->showProfile($output, $profile);
        }
    }

    protected function showProfile(OutputInterface $output, Profile $profile)
    {
        $output->writeln(sprintf('<comment>Using profile "%s"</comment>', $profile->getName()));
    }
}
