<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Cli;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactoring\ConfigureSpecNamespace;
use Nidup\Architool\Application\Refactoring\ConfigureSpecNamespaceHandler;
use Nidup\Architool\Application\Refactoring\MoveLegacySpec;
use Nidup\Architool\Application\Refactoring\MoveLegacySpecHandler;
use Nidup\Architool\Application\Refactoring\ReconfigureSpecNamespace;
use Nidup\Architool\Application\Refactoring\ReconfigureSpecNamespaceHandler;
use Nidup\Architool\Application\Refactoring\ReplaceCodeInClass;
use Nidup\Architool\Application\Refactoring\ReplaceCodeInClassHandler;
use Nidup\Architool\Application\Refactoring\MoveLegacyClass;
use Nidup\Architool\Application\Refactoring\MoveLegacyClassHandler;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespace;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespaceHandler;
use Nidup\Architool\Infrastructure\Filesystem\FsClassExtractor;
use Nidup\Architool\Infrastructure\Filesystem\FsClassRenamer;
use Nidup\Architool\Infrastructure\Filesystem\FsCodeReplacer;
use Nidup\Architool\Infrastructure\Filesystem\FsNamespaceExtractor;
use Nidup\Architool\Infrastructure\Filesystem\FsNamespaceRenamer;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecNamespaceConfigurator;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecRenamer;
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

        $mover = new FsClassExtractor($path);
        $renamer = new FsClassRenamer($path);
        $classHandler = new MoveLegacyClassHandler($mover, $renamer);

        $mover = new FsClassExtractor($path);
        $renamer = new FsSpecRenamer($path);
        $specHandler = new MoveLegacySpecHandler($mover, $renamer);

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

            } else if ($command instanceof MoveLegacyClass) {
                $classHandler->handle($command);
                $output->writeln(
                    sprintf(
                        ' - Extract class "%s" to "%s" in order to "%s"',
                        $command->getClassName(),
                        $command->getDestinationNamespace(),
                        $command->getDescription()
                    )
                );
            } else if ($command instanceof MoveLegacySpec) {
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
                throw new \Exception(printf("Unknown command %s", get_class($command)));
            }
        }
        $output->writeln("");
    }

}