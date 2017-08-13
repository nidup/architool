<?php

namespace Nidup\Architool\Infrastructure\Cli;

use Nidup\Architool\Application\CreateBoundedContexts;
use Nidup\Architool\Application\CreateBoundedContextsHandler;
use Nidup\Architool\Application\MoveLegacyNamespace;
use Nidup\Architool\Application\MoveLegacyNamespaceHandler;
use Nidup\Architool\Infrastructure\Filesystem\FsBoundedContextRepository;
use Nidup\Architool\Infrastructure\Filesystem\FsNamespaceExtractor;
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

        $this->moveLegacyPimNamespaces($srcPath, $output);
    }

    private function moveLegacyPimNamespaces(string $path, OutputInterface $output)
    {
        $commands = [
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/Elasticsearch',
                'Pim/ProductEnrichment/Infrastructure/Elasticsearch',
                'Extract ElasticSearch infrastructure'
            ),
        ];

        $mover = new FsNamespaceExtractor($path);
        $handler = new MoveLegacyNamespaceHandler($mover);
        /** @var MoveLegacyNamespace $command */
        foreach ($commands as $command) {
            $handler->handle($command);
            $output->writeln(
                sprintf(
                    '<info>Legacy namespace "%s" has been extracted to "%s" in order to "%s"</info>',
                    $command->getLegacyNamespace(),
                    $command->getDestinationNamespace(),
                    $command->getDescription()
                )
            );
        }
    }

    private function createBoundedContexts(string $path, OutputInterface $output)
    {
        $contextNames = ['UserManagement', 'CatalogSetup', 'ProductStructure', 'ProductEnrichment'];
        $command = new CreateBoundedContexts($contextNames);
        $repository = new FsBoundedContextRepository($path);
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