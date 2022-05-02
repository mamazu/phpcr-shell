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

namespace spec\PHPCR\Shell\Event;

use PHPCR\Shell\Config\Profile;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProfileInitEventSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PHPCR\Shell\Event\ProfileInitEvent');
    }

    public function let(
        Profile $profile,
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->beConstructedWith(
            $profile,
            $input,
            $output
        );
    }

    public function it_should_have_getters(
        Profile $profile,
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->getProfile()->shouldReturn($profile);
        $this->getInput()->shouldReturn($input);
        $this->getOutput()->shouldReturn($output);
    }
}
