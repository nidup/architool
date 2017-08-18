<?php

namespace spec\Nidup\Architool\Application\Refactor;

use Nidup\Architool\Application\Refactor\MoveLegacySpecFile;
use Nidup\Architool\Domain\Model\SpecFile;
use Nidup\Architool\Domain\Model\SpecFile\SpecName;
use Nidup\Architool\Domain\Model\SpecFile\SpecNamespace;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecFileRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MoveLegacySpecFileHandlerSpec extends ObjectBehavior
{
    function let(FsSpecFileRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_moves_a_legacy_class_file($repository, MoveLegacySpecFile $command, SpecFile $file)
    {
        $command->getLegacyNamespace()->willReturn('Pim/Catalog/Component');
        $command->getClassName()->willReturn('AttributeType');
        $command->getDestinationNamespace()->willReturn('Akeneo/Pim/ProductStructure/Domain');

        $repository->get(Argument::type(SpecNamespace::class), Argument::type(SpecName::class))
            ->willReturn($file);
        $file->move(Argument::type(SpecNamespace::class))->shouldBeCalled();
        $repository->update($file)->shouldBeCalled();

        $this->handle($command);
    }
}
