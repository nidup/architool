<?php

namespace spec\Nidup\Architool\Application\Refactor;

use Nidup\Architool\Application\Refactor\MoveLegacyClassFile;
use Nidup\Architool\Domain\FileStorage\ClassFile;
use Nidup\Architool\Domain\FileStorage\ClassFile\ClassNamespace;
use Nidup\Architool\Domain\FileStorage\ClassFileRepository;
use Nidup\Architool\Domain\FileStorage\File\Name;
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

        $repository->get(Argument::type(ClassNamespace::class), Argument::type(Name::class))
            ->willReturn($file);
        $file->move(Argument::type(ClassNamespace::class))->shouldBeCalled();
        $repository->update($file)->shouldBeCalled();

        $this->handle($command);
    }
}
