<?php

namespace spec\Nidup\Architool\Domain;

use Nidup\Architool\Domain\SpecFileMover;
use Nidup\Architool\Domain\SpecFileReferenceUpdater;
use Nidup\Architool\Domain\SpecFileRepository;
use Nidup\Architool\Domain\Model\SpecFile;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SpecFileRepositorySpec extends ObjectBehavior
{
    function let(SpecFileMover $mover, SpecFileReferenceUpdater $updater)
    {
        $this->beConstructedWith($mover, $updater);
    }

    function it_get_a_class_file(SpecFile\SpecNamespace $namespace, SpecFile\SpecName $name)
    {
        $this->get($namespace, $name)->shouldReturnAnInstanceOf(SpecFile::class);
    }

    function it_updates_a_class_file($mover, $updater, SpecFile $classFile)
    {
        $classFile->hasMoved()->willReturn(true);
        $mover->move($classFile)->shouldBeCalled();
        $updater->update($classFile)->shouldBeCalled();

        $this->update($classFile);
    }

    function it_throws_an_exception_when_trying_to_update_a_not_modified_class_file($mover, $updater, SpecFile $classFile, SpecFile\SpecNamespace $namespace, SpecFile\SpecName $classname)
    {
        $classFile->getOriginalNamespace()->willReturn($namespace);
        $namespace->getName()->willReturn('Pim/Component/Catalog');
        $classFile->getName()->willReturn($classname);
        $classname->getName()->willReturn('AttributeType');

        $classFile->hasMoved()->willReturn(false);
        $mover->move($classFile)->shouldNotBeCalled();
        $updater->update($classFile)->shouldNotBeCalled();

        $this->shouldThrow(\LogicException::class)->during('update', [$classFile]);
    }
}
