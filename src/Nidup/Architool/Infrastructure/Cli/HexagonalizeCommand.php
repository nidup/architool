<?php

namespace Nidup\Architool\Infrastructure\Cli;

use Nidup\Architool\Application\CreateBoundedContexts;
use Nidup\Architool\Application\CreateBoundedContextsHandler;
use Nidup\Architool\Infrastructure\Filesystem\BoundedContextRepository;
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

        $srcPath = $path.DIRECTORY_SEPARATOR.'src';
        $pimNamespacePath = $srcPath.DIRECTORY_SEPARATOR.'Pim';
        $this->createBoundedContexts($pimNamespacePath, $output);
    }

    private function createBoundedContexts(string $path, OutputInterface $output)
    {
        $contextNames = ['UserManagement', 'ProductStructure', 'ProductEnrichment'];
        $command = new CreateBoundedContexts($contextNames);
        $repository = new BoundedContextRepository($path);
        $handler = new CreateBoundedContextsHandler($repository);
        $handler->handle($command);
        $output->writeln(sprintf("<info>Following bounded contexts have been created %s</info>", implode(', ', $contextNames)));
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