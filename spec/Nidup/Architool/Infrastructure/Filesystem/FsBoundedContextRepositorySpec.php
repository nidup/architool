<?php

namespace spec\Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\Model\BoundedContext;
use Nidup\Architool\Domain\Model\BoundedContext\Layer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Filesystem\Filesystem;

class FsBoundedContextRepositorySpec extends ObjectBehavior
{
    function let(Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem, 'src');
    }

    function it_adds_a_bounded_context($filesystem, BoundedContext $context, Layer $layerDomain, Layer $layerInfra)
    {
        $context->getName()->willReturn('Akeneo/Pim/ProductEnrichment');
        $context->getLayers()->willReturn([$layerDomain, $layerInfra]);
        $layerDomain->getName()->willReturn('Domain');
        $layerInfra->getName()->willReturn('Infrastructure');

        $filesystem->mkdir('src/Akeneo/Pim/ProductEnrichment')->shouldBeCalled();
        $filesystem->mkdir('src/Akeneo/Pim/ProductEnrichment/Domain')->shouldBeCalled();
        $filesystem->mkdir('src/Akeneo/Pim/ProductEnrichment/Infrastructure')->shouldBeCalled();

        $this->add($context);
    }
}
