<?php

namespace spec\Nidup\Architool\Application\Refactor;

use Nidup\Architool\Application\Refactor\MoveLegacyClassFile;
use Nidup\Architool\Domain\ClassFileRepository;
use Nidup\Architool\Domain\Model\ClassFile;
use Nidup\Architool\Domain\Model\ClassFile\ClassName;
use Nidup\Architool\Domain\Model\ClassFile\ClassNamespace;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MoveLegacyClassFileHandlerSpec extends ObjectBehavior
{
    function let(ClassFileRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_moves_a_legacy_class_file($repository, MoveLegacyClassFile $command, ClassFile $file)
    {
        $command->getLegacyNamespace()->willReturn('Pim/Catalog/Component');
        $command->getClassName()->willReturn('AttributeType');
        $command->getDestinationNamespace()->willReturn('Akeneo/Pim/ProductStructure/Domain');

        $repository->get(Argument::type(ClassNamespace::class), Argument::type(ClassName::class))
            ->willReturn($file);
        $file->move(Argument::type(ClassNamespace::class))->shouldBeCalled();
        $repository->update($file)->shouldBeCalled();

        $this->handle($command);
    }
}
