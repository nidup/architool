<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

use Nidup\Architool\Domain\Model\SpecFile\SpecName;
use Nidup\Architool\Domain\Model\SpecFile\SpecNamespace;
use Nidup\Architool\Domain\SpecFileRepository;

final class MoveLegacySpecFileHandler
{
    private $repository;

    public function __construct(SpecFileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(MoveLegacySpecFile $command): void
    {
        $from = new SpecNamespace($command->getLegacyNamespace());
        $to = new SpecNamespace($command->getDestinationNamespace());
        $name = new SpecName($command->getClassName());

        $classFile = $this->repository->get($from, $name);
        $classFile->move($to);
        $this->repository->update($classFile);
    }
}