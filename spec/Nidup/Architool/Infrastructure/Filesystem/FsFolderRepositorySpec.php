<?php

namespace spec\Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\FileStorage\Folder;
use Nidup\Architool\Domain\FileStorage\Folder\FolderNamespace;
use Nidup\Architool\Infrastructure\Filesystem\FolderMover;
use Nidup\Architool\Infrastructure\Filesystem\FolderReferenceUpdater;
use PhpSpec\ObjectBehavior;

class FsFolderRepositorySpec extends ObjectBehavior
{
    function let(FolderMover $mover, FolderReferenceUpdater $updater)
    {
        $this->beConstructedWith($mover, $updater);
    }

    function it_get_a_class_file(FolderNamespace $namespace)
    {
        $this->get($namespace)->shouldReturnAnInstanceOf(Folder::class);
    }

    function it_updates_a_class_file($mover, $updater, Folder $folder)
    {
        $folder->hasMoved()->willReturn(true);
        $mover->move($folder)->shouldBeCalled();
        $updater->update($folder)->shouldBeCalled();

        $this->update($folder);
    }

    function it_throws_an_exception_when_trying_to_update_a_not_modified_class_file($mover, $updater, Folder $folder, Folder\FolderNamespace $namespace)
    {
        $folder->getOriginalNamespace()->willReturn($namespace);
        $namespace->getName()->willReturn('Pim/Component/Catalog');

        $folder->hasMoved()->willReturn(false);
        $mover->move($folder)->shouldNotBeCalled();
        $updater->update($folder)->shouldNotBeCalled();

        $this->shouldThrow(\LogicException::class)->during('update', [$folder]);
    }
}
