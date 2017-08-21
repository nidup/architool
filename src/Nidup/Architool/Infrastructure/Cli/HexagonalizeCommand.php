<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Cli;

use Nidup\Architool\Application\Refactor\CreateBoundedContextsHandler;
use Nidup\Architool\Application\Project\Pim\CommunityProject;
use Nidup\Architool\Application\Workspace\FinalizeWorkspace;
use Nidup\Architool\Application\Workspace\FinalizeWorkspaceHandler;
use Nidup\Architool\Application\Workspace\InitializeWorkspace;
use Nidup\Architool\Application\Workspace\InitializeWorkspaceHandler;
use Nidup\Architool\Application\Project\Project;
use Nidup\Architool\Infrastructure\Filesystem\FsBoundedContextRepository;
use Nidup\Architool\Infrastructure\Filesystem\FsCacheCleaner;
use Nidup\Architool\Infrastructure\Filesystem\FsWorkspaceCleaner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

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
        $stepCommandsHandler = new StepCommandsHandler();
        foreach ($steps as $step) {
            $stepCommandsHandler->handle($step, $output, $path);
        }
    }

    private function createBoundedContexts(string $path, OutputInterface $output, Project $project)
    {
        $command = $project->createBoundedContextsCommand();
        $repository = new FsBoundedContextRepository(new Filesystem(), $path);
        $handler = new CreateBoundedContextsHandler($repository);
        $handler->handle($command);
        $output->writeln(sprintf("[x] Create bounded contexts %s\n", implode(', ', $command->getNames())));
    }

    private function prepareWorkspace(string $projectPath, OutputInterface $output)
    {
        $command = new InitializeWorkspace();
        $cleaner = new FsWorkspaceCleaner($projectPath);
        $handler = new InitializeWorkspaceHandler($cleaner);
        $handler->handle($command);
        $output->writeln(sprintf("[x] Prepare project workspace \"%s\"\n", $projectPath));
    }

    private function finalizeWorkspace(string $projectPath, OutputInterface $output)
    {
        $command = new FinalizeWorkspace();
        $cleaner = new FsCacheCleaner($projectPath);
        $handler = new FinalizeWorkspaceHandler($cleaner);
        $handler->handle($command);
        $output->writeln(sprintf('[x] Project cache has been cleaned %s', $projectPath));

        $output->writeln(sprintf('[ ] Refresh your frontend assets with bin/docker/pim-front.sh or equivalent in %s', $projectPath));
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