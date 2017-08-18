<?php

namespace spec\Nidup\Architool\Application\Refactor;

use Nidup\Architool\Application\Refactor\MoveLegacyFolder;
use Nidup\Architool\Domain\FolderRepository;
use Nidup\Architool\Domain\Model\Folder;
use Nidup\Architool\Domain\Model\Folder\FolderNamespace;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MoveLegacyFolderHandlerSpec extends ObjectBehavior
{
    function let(FolderRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_moves_a_legacy_class_file($repository, MoveLegacyFolder $command, Folder $folder)
    {
        $command->getLegacyNamespace()->willReturn('Pim/Catalog/Component');
        $command->getDestinationNamespace()->willReturn('Akeneo/Pim/ProductStructure/Domain');

        $repository->get(Argument::type(FolderNamespace::class))
            ->willReturn($folder);
        $folder->move(Argument::type(FolderNamespace::class))->shouldBeCalled();
        $repository->update($folder)->shouldBeCalled();

        $this->handle($command);
    }
}
