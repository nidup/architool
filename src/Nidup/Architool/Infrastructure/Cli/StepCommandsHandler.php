<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Cli;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactor\MoveLegacyClassFile;
use Nidup\Architool\Application\Refactor\MoveLegacyClassFileHandler;
use Nidup\Architool\Application\Refactoring\ConfigureSpecNamespace;
use Nidup\Architool\Application\Refactoring\ConfigureSpecNamespaceHandler;
use Nidup\Architool\Application\Refactor\MoveLegacySpecFile;
use Nidup\Architool\Application\Refactor\MoveLegacySpecFileHandler;
use Nidup\Architool\Application\Refactoring\ReconfigureSpecNamespace;
use Nidup\Architool\Application\Refactoring\ReconfigureSpecNamespaceHandler;
use Nidup\Architool\Application\Refactoring\ReplaceCodeInClass;
use Nidup\Architool\Application\Refactoring\ReplaceCodeInClassHandler;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespace;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespaceHandler;
use Nidup\Architool\Domain\ClassFileRepository;
use Nidup\Architool\Domain\SpecFileRepository;
use Nidup\Architool\Infrastructure\Filesystem\FsClassFileMover;
use Nidup\Architool\Infrastructure\Filesystem\FsClassFileReferenceUpdater;
use Nidup\Architool\Infrastructure\Filesystem\FsCodeReplacer;
use Nidup\Architool\Infrastructure\Filesystem\FsNamespaceExtractor;
use Nidup\Architool\Infrastructure\Filesystem\FsNamespaceRenamer;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecFileMover;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecFileReferenceUpdater;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecNamespaceConfigurator;
use Symfony\Component\Console\Output\OutputInterface;

class StepCommandsHandler
{
    public function handle(Step $step, OutputInterface $output, string $path)
    {
        $output->writeln(
            sprintf(
                '[x] %s',
                $step->getDescription()
            )
        );

        $commands = $step->createReworkCodebaseCommands();

        $mover = new FsNamespaceExtractor($path);
        $renamer = new FsNamespaceRenamer($path);
        $namespaceHandler = new MoveLegacyNamespaceHandler($mover, $renamer);

        $repo = new ClassFileRepository(new FsClassFileMover($path), new FsClassFileReferenceUpdater($path));
        $classFileHandler = new MoveLegacyClassFileHandler($repo);

        $repo = new SpecFileRepository(new FsSpecFileMover($path), new FsSpecFileReferenceUpdater($path));
        $specHandler = new MoveLegacySpecFileHandler($repo);

        $configurator = new FsSpecNamespaceConfigurator($path);
        $specReconfigHandler = new ReconfigureSpecNamespaceHandler($configurator);
        $specConfigHandler = new ConfigureSpecNamespaceHandler($configurator);

        $codeReplacer = new FsCodeReplacer($path);
        $codeReplacerHandler = new ReplaceCodeInClassHandler($codeReplacer);

        foreach ($commands as $command) {
            if ($command instanceof MoveLegacyNamespace) {
                $namespaceHandler->handle($command);
                $output->writeln(
                    sprintf(
                        ' - Extract namespace "%s" to "%s" in order to "%s"',
                        $command->getLegacyNamespace(),
                        $command->getDestinationNamespace(),
                        $command->getDescription()
                    )
                );

            } else if ($command instanceof MoveLegacyClassFile) {
                $classFileHandler->handle($command);
                $output->writeln(
                    sprintf(
                        ' - Extract class "%s" to "%s" in order to "%s"',
                        $command->getClassName(),
                        $command->getDestinationNamespace(),
                        $command->getDescription()
                    )
                );
            } else if ($command instanceof MoveLegacySpecFile) {
                $specHandler->handle($command);
                $output->writeln(
                    sprintf(
                        ' - Extract spec "%s" to "%s" in order to "%s"',
                        $command->getClassName(),
                        $command->getDestinationNamespace(),
                        $command->getDescription()
                    )
                );
            } else if ($command instanceof ConfigureSpecNamespace) {
                $specConfigHandler->handle($command);
                $output->writeln(
                    sprintf(
                        ' - Configure specs to match "%s" in order to "%s"',
                        $command->getNamespace(),
                        $command->getDescription()
                    )
                );
            } else if ($command instanceof ReconfigureSpecNamespace) {
                $specReconfigHandler->handle($command);
                $output->writeln(
                    sprintf(
                        ' - Re-configure specs to match "%s" in order to "%s"',
                        $command->getDestinationNamespace(),
                        $command->getDescription()
                    )
                );
            } else if ($command instanceof ReplaceCodeInClass) {
                $codeReplacerHandler->handle($command);
                $output->writeln(
                    sprintf(
                        ' - Replace code fragment "%s" in "%s" in order to "%s"',
                        $command->getReplacementCode(),
                        $command->getNamespace().DIRECTORY_SEPARATOR.$command->getClassName(),
                        $command->getDescription()
                    )
                );
            } else {
                throw new \Exception(sprintf("Unknown command %s", get_class($command)));
            }
        }
        $output->writeln("");
    }

}