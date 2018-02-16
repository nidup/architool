<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

use Nidup\Architool\Domain\FileStorage\ClassFile\ClassNamespace;
use Nidup\Architool\Domain\FileStorage\ClassFileRepository;
use Nidup\Architool\Domain\FileStorage\File\Name;

final class MoveLegacyClassFileHandler
{
    private $repository;

    public function __construct(ClassFileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(MoveLegacyClassFile $command): void
    {
        $from = new ClassNamespace($command->getLegacyNamespace());
        $to = new ClassNamespace($command->getDestinationNamespace());
        $name = new Name($command->getClassName());

        $classFile = $this->repository->get($from, $name);
        $classFile->move($to);
        $this->repository->update($classFile);
    }
}
