<?php

namespace Nidup\Architool\Infrastructure\Cli;

use Nidup\Architool\Application\BoundedContext\CreateBoundedContextsHandler;
use Nidup\Architool\Application\Project\Pim\CommunityProject;
use Nidup\Architool\Application\Refactoring\ConfigureSpecNamespace;
use Nidup\Architool\Application\Refactoring\ConfigureSpecNamespaceHandler;
use Nidup\Architool\Application\Refactoring\MoveLegacySpec;
use Nidup\Architool\Application\Refactoring\MoveLegacySpecHandler;
use Nidup\Architool\Application\Refactoring\ReconfigureSpecNamespace;
use Nidup\Architool\Application\Refactoring\ReconfigureSpecNamespaceHandler;
use Nidup\Architool\Application\Refactoring\ReplaceCodeInClass;
use Nidup\Architool\Application\Refactoring\ReplaceCodeInClassHandler;
use Nidup\Architool\Application\Workspace\FinalizeWorkspace;
use Nidup\Architool\Application\Workspace\FinalizeWorkspaceHandler;
use Nidup\Architool\Application\Workspace\InitializeWorkspace;
use Nidup\Architool\Application\Workspace\InitializeWorkspaceHandler;
use Nidup\Architool\Application\Refactoring\MoveLegacyClass;
use Nidup\Architool\Application\Refactoring\MoveLegacyClassHandler;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespace;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespaceHandler;
use Nidup\Architool\Application\Project\Project;
use Nidup\Architool\Infrastructure\Filesystem\FsBoundedContextRepository;
use Nidup\Architool\Infrastructure\Filesystem\FsCacheCleaner;
use Nidup\Architool\Infrastructure\Filesystem\FsClassExtractor;
use Nidup\Architool\Infrastructure\Filesystem\FsClassRenamer;
use Nidup\Architool\Infrastructure\Filesystem\FsCodeReplacer;
use Nidup\Architool\Infrastructure\Filesystem\FsNamespaceExtractor;
use Nidup\Architool\Infrastructure\Filesystem\FsNamespaceRenamer;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecNamespaceConfigurator;
use Nidup\Architool\Infrastructure\Filesystem\FsSpecRenamer;
use Nidup\Architool\Infrastructure\Filesystem\FsWorkspaceCleaner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HexagonalizeCommand extends Command
{
    protected function configure()
    {
        $this->setName('nidup:architool:hexagonalize')
            ->setDescription('Re-organize pim community code base')
            ->addArgument('path', InputArgument::REQUIRED, 'PIM community dev absolute path');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->printTitle($output);

        $projectPath = $input->getArgument('path');
        $this->prepareWorkspace($projectPath, $output);

        $project = new CommunityProject();

        $srcPath = $projectPath.DIRECTORY_SEPARATOR.'src';
        $this->createBoundedContexts($srcPath, $output, $project);

        $this->moveLegacyPimNamespaces($projectPath, $output, $project);

        $this->finalizeWorkspace($projectPath, $output);
    }

    private function moveLegacyPimNamespaces(string $path, OutputInterface $output, Project $project)
    {
        $steps = $project->createOrderedSteps();
        foreach ($steps as $step) {

            $commands = $step->createReworkCodebaseCommands();

            $output->writeln(
                sprintf(
                    '<info>>> %s</info>',
                    $step->getDescription()
                )
            );

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
                            '<info>Legacy namespace "%s" has been extracted to "%s" in order to "%s"</info>',
                            $command->getLegacyNamespace(),
                            $command->getDestinationNamespace(),
                            $command->getDescription()
                        )
                    );

                } else if ($command instanceof MoveLegacyClass) {
                    $classHandler->handle($command);
                    $output->writeln(
                        sprintf(
                            '<info>Legacy class "%s" has been extracted to "%s" in order to "%s"</info>',
                            $command->getClassName(),
                            $command->getDestinationNamespace(),
                            $command->getDescription()
                        )
                    );
                } else if ($command instanceof MoveLegacySpec) {
                    $specHandler->handle($command);
                    $output->writeln(
                        sprintf(
                            '<info>Legacy spec "%s" has been extracted to "%s"</info>',
                            $command->getClassName(),
                            $command->getDestinationNamespace()
                        )
                    );
                } else if ($command instanceof ConfigureSpecNamespace) {
                    $specConfigHandler->handle($command);
                    $output->writeln(
                        sprintf(
                            '<info>Specs have been configured to match "%s"</info>',
                            $command->getNamespace()
                        )
                    );
                } else if ($command instanceof ReconfigureSpecNamespace) {
                    $specReconfigHandler->handle($command);
                    $output->writeln(
                        sprintf(
                            '<info>Specs have been re-configured to match "%s"</info>',
                            $command->getDestinationNamespace()
                        )
                    );
                } else if ($command instanceof ReplaceCodeInClass) {
                    $codeReplacerHandler->handle($command);
                    $output->writeln(
                        sprintf(
                            '<info>Code fragment has been replaced by "%s"</info>',
                            $command->getReplacementCode()
                        )
                    );
                } else {
                    throw new \Exception(printf("Unknown command %s", get_class($command)));
                }
            }
        }
    }

    private function createBoundedContexts(string $path, OutputInterface $output, Project $project)
    {
        $command = $project->createBoundedContextsCommand();
        $repository = new FsBoundedContextRepository($path);
        $handler = new CreateBoundedContextsHandler($repository);
        $handler->handle($command);
        $output->writeln(sprintf("<info>Following bounded contexts have been created %s</info>", implode(', ', $command->getNames())));
    }

    private function prepareWorkspace(string $projectPath, OutputInterface $output)
    {
        $command = new InitializeWorkspace();
        $cleaner = new FsWorkspaceCleaner($projectPath);
        $handler = new InitializeWorkspaceHandler($cleaner);
        $handler->handle($command);
        $output->writeln(sprintf('<info>Project workspace "%s" is ready</info>', $projectPath));
    }

    private function finalizeWorkspace(string $projectPath, OutputInterface $output)
    {
        $command = new FinalizeWorkspace();
        $cleaner = new FsCacheCleaner($projectPath);
        $handler = new FinalizeWorkspaceHandler($cleaner);
        $handler->handle($command);
        $output->writeln(sprintf('<info>Project cache has been cleaned</info>', $projectPath));
    }

    private function printTitle(OutputInterface $output)
    {
        $output->writeln("<info>Hexagonalize it! (Ports & Adapter FTW)</info>");
        $hexagon = "      __
   __/  \__
  /  \__/  \
  \__/  \__/
  /  \__/  \
  \__/  \__/
     \__/
";
        $lines = explode('\n', $hexagon);
        foreach ($lines as $line) {
            $output->writeln(sprintf("<info>%s</info>", $line));
        }
    }
}