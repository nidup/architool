<?php

namespace spec\Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\FileStorage\File\Name;
use Nidup\Architool\Domain\FileStorage\SpecFile;
use Nidup\Architool\Infrastructure\Filesystem\FileMover;
use Nidup\Architool\Infrastructure\Filesystem\SpecFileReferenceUpdater;
use PhpSpec\ObjectBehavior;

class FsSpecFileRepositorySpec extends ObjectBehavior
{
    function let(FileMover $mover, SpecFileReferenceUpdater $updater)
    {
        $this->beConstructedWith($mover, $updater);
    }

    function it_get_a_class_file(SpecFile\SpecNamespace $namespace, Name $name)
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

    function it_throws_an_exception_when_trying_to_update_a_not_modified_class_file($mover, $updater, SpecFile $classFile, SpecFile\SpecNamespace $namespace, Name $classname)
    {
        $classFile->getNamespace()->willReturn($namespace);
        $namespace->getName()->willReturn('Pim/Component/Catalog');
        $classFile->getName()->willReturn($classname);
        $classname->getValue()->willReturn('AttributeType');

        $classFile->hasMoved()->willReturn(false);
        $mover->move($classFile)->shouldNotBeCalled();
        $updater->update($classFile)->shouldNotBeCalled();

        $this->shouldThrow(\LogicException::class)->during('update', [$classFile]);
    }
}
