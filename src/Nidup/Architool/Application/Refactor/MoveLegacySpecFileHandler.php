<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

use Nidup\Architool\Domain\Model\File\Name;
use Nidup\Architool\Domain\Model\SpecFile\SpecNamespace;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecFileRepository;

final class MoveLegacySpecFileHandler
{
    private $repository;

    public function __construct(FsSpecFileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(MoveLegacySpecFile $command): void
    {
        $from = new SpecNamespace($command->getLegacyNamespace());
        $to = new SpecNamespace($command->getDestinationNamespace());
        $name = new Name($command->getClassName());

        $classFile = $this->repository->get($from, $name);
        $classFile->move($to);
        $this->repository->update($classFile);
    }
}
