<?php

namespace Nidup\Architool\Infrastructure\Cli;

use Nidup\Architool\Application\CreateBoundedContexts;
use Nidup\Architool\Application\CreateBoundedContextsHandler;
use Nidup\Architool\Application\InitializeWorkspace;
use Nidup\Architool\Application\InitializeWorkspaceHandler;
use Nidup\Architool\Application\MoveLegacyClass;
use Nidup\Architool\Application\MoveLegacyClassHandler;
use Nidup\Architool\Application\MoveLegacyNamespace;
use Nidup\Architool\Application\MoveLegacyNamespaceHandler;
use Nidup\Architool\Application\Project;
use Nidup\Architool\Infrastructure\Filesystem\FsBoundedContextRepository;
use Nidup\Architool\Infrastructure\Filesystem\FsClassExtractor;
use Nidup\Architool\Infrastructure\Filesystem\FsClassRenamer;
use Nidup\Architool\Infrastructure\Filesystem\FsNamespaceExtractor;
use Nidup\Architool\Infrastructure\Filesystem\FsNamespaceRenamer;
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

        $path = $input->getArgument('path');
        $output->writeln(sprintf("<info>PIM is installed in %s</info>", $path));

        $this->prepareWorkspace($path, $output);

        $project = new Project\Example();

        $srcPath = $path.DIRECTORY_SEPARATOR.'src';
        $pimNamespacePath = $srcPath.DIRECTORY_SEPARATOR.'Pim';
        $this->createBoundedContexts($pimNamespacePath, $output, $project);

        $this->moveLegacyPimNamespaces($path, $output, $project);
    }

    private function moveLegacyPimNamespaces(string $path, OutputInterface $output, Project $project)
    {
        $commands = $project->createReworkCodebaseCommands();

        $mover = new FsNamespaceExtractor($path);
        $renamer = new FsNamespaceRenamer($path);
        $namespaceHandler = new MoveLegacyNamespaceHandler($mover, $renamer);


        $mover = new FsClassExtractor($path);
        $renamer = new FsClassRenamer($path);
        $classHandler = new MoveLegacyClassHandler($mover, $renamer);

        /** @var MoveLegacyNamespace $command */
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