<?php

namespace spec\Nidup\Architool\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BoundedContextSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('ProductEnrichment');
    }

    function it_has_three_layers()
    {
        $this->getLayers()->shouldHaveCount(3);
    }
}
