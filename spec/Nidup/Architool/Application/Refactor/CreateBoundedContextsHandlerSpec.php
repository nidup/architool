<?php

namespace spec\Nidup\Architool\Application\Refactor;

use Nidup\Architool\Application\Refactor\CreateBoundedContexts;
use Nidup\Architool\Domain\BoundedContext\BoundedContext;
use Nidup\Architool\Domain\BoundedContext\BoundedContextRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CreateBoundedContextsHandlerSpec extends ObjectBehavior
{
    function let(BoundedContextRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_creates_several_bounded_contexts(CreateBoundedContexts $command, $repository)
    {
        $command->getNames()->willReturn(['ProductStructure', 'ProductEnrichment']);
        $repository->add(Argument::type(BoundedContext::class))->shouldBeCalled();

        $this->handle($command);
    }
}
