<?php

namespace spec\Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\Model\ClassFile;
use Nidup\Architool\Infrastructure\Filesystem\ClassFileMover;
use Nidup\Architool\Infrastructure\Filesystem\ClassFileReferenceUpdater;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FsClassFileRepositorySpec extends ObjectBehavior
{
    function let(ClassFileMover $mover, ClassFileReferenceUpdater $updater)
    {
        $this->beConstructedWith($mover, $updater);
    }

    function it_get_a_class_file(ClassFile\ClassNamespace $namespace, ClassFile\ClassName $name)
    {
        $this->get($namespace, $name)->shouldReturnAnInstanceOf(ClassFile::class);
    }

    function it_updates_a_class_file($mover, $updater, ClassFile $classFile)
    {
        $classFile->hasMoved()->willReturn(true);
        $mover->move($classFile)->shouldBeCalled();
        $updater->update($classFile)->shouldBeCalled();

        $this->update($classFile);
    }

    function it_throws_an_exception_when_trying_to_update_a_not_modified_class_file($mover, $updater, ClassFile $classFile, ClassFile\ClassNamespace $namespace, ClassFile\ClassName $classname)
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
