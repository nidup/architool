<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Cli;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactor\MoveLegacyClassFile;
use Nidup\Architool\Application\Refactor\MoveLegacyClassFileHandler;
use Nidup\Architool\Application\Refactor\ConfigureSpecFolder;
use Nidup\Architool\Application\Refactor\ConfigureSpecFolderHandler;
use Nidup\Architool\Application\Refactor\MoveLegacySpecFile;
use Nidup\Architool\Application\Refactor\MoveLegacySpecFileHandler;
use Nidup\Architool\Application\Refactor\ReconfigureSpecFolder;
use Nidup\Architool\Application\Refactor\ReconfigureSpecFolderHandler;
use Nidup\Architool\Application\Refactoring\ReplaceCodeInClass;
use Nidup\Architool\Application\Refactoring\ReplaceCodeInClassHandler;
use Nidup\Architool\Application\Refactor\MoveLegacyFolder;
use Nidup\Architool\Application\Refactor\MoveLegacyFolderHandler;
use Nidup\Architool\Infrastructure\Filesystem\FsClassFileRepository;
use Nidup\Architool\Infrastructure\Filesystem\FsFolderRepository;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecConfigurationFileRepository;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecFileRepository;
use Nidup\Architool\Infrastructure\Filesystem\ClassFileMover;
use Nidup\Architool\Infrastructure\Filesystem\ClassFileReferenceUpdater;
use Nidup\Architool\Infrastructure\Filesystem\FsCodeReplacer;
use Nidup\Architool\Infrastructure\Filesystem\FolderMover;
use Nidup\Architool\Infrastructure\Filesystem\FolderReferenceUpdater;
use Nidup\Architool\Infrastructure\Filesystem\SpecFileMover;
use Nidup\Architool\Infrastructure\Filesystem\SpecFileReferenceUpdater;
use Nidup\Architool\Infrastructure\Filesystem\SpecConfigurationUpdater;
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

        $repo = new FsFolderRepository(new FolderMover($path), new FolderReferenceUpdater($path));
        $namespaceHandler = new MoveLegacyFolderHandler($repo);

        $repo = new FsClassFileRepository(new ClassFileMover($path), new ClassFileReferenceUpdater($path));
        $classFileHandler = new MoveLegacyClassFileHandler($repo);

        $repo = new FsSpecFileRepository(new SpecFileMover($path), new SpecFileReferenceUpdater($path));
        $specHandler = new MoveLegacySpecFileHandler($repo);

        $configurator = new SpecConfigurationUpdater($path);
        $repo = new FsSpecConfigurationFileRepository($configurator);
        $specReconfigHandler = new ReconfigureSpecFolderHandler($repo);
        $specConfigHandler = new ConfigureSpecFolderHandler($repo);

        $codeReplacer = new FsCodeReplacer($path);
        $codeReplacerHandler = new ReplaceCodeInClassHandler($codeReplacer);

        foreach ($commands as $command) {
            if ($command instanceof MoveLegacyFolder) {
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
            } else if ($command instanceof ConfigureSpecFolder) {
                $specConfigHandler->handle($command);
                $output->writeln(
                    sprintf(
                        ' - Configure specs to match "%s" in order to "%s"',
                        $command->getNamespace(),
                        $command->getDescription()
                    )
                );
            } else if ($command instanceof ReconfigureSpecFolder) {
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