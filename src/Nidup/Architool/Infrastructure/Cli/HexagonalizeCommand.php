<?php

namespace Nidup\Architool\Infrastructure\Cli;

use Nidup\Architool\Application\CreateBoundedContexts;
use Nidup\Architool\Application\CreateBoundedContextsHandler;
use Nidup\Architool\Application\InitializeWorkspace;
use Nidup\Architool\Application\InitializeWorkspaceHandler;
use Nidup\Architool\Application\MoveLegacyNamespace;
use Nidup\Architool\Application\MoveLegacyNamespaceHandler;
use Nidup\Architool\Infrastructure\Filesystem\FsBoundedContextRepository;
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

        $srcPath = $path.DIRECTORY_SEPARATOR.'src';
        $pimNamespacePath = $srcPath.DIRECTORY_SEPARATOR.'Pim';
        $this->createBoundedContexts($pimNamespacePath, $output);

        $this->moveLegacyPimNamespaces($path, $output);
    }

    private function moveLegacyPimNamespaces(string $path, OutputInterface $output)
    {
        $commands = [
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/Elasticsearch',
                'Pim/ProductEnrichment/Core/Infrastructure/Elasticsearch',
                'Extract ElasticSearch infrastructure'
            ),
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/Doctrine',
                'Pim/ProductEnrichment/Core/Infrastructure/Doctrine',
                'Extract Doctrine infrastructure'
            ),

            new MoveLegacyNamespace(
                'Pim/Component/Api',
                'Pim/ProductEnrichment/WebApi/Application/Api',
                'Extract Web API application'
            ),
            new MoveLegacyNamespace(
                'Pim/Bundle/ApiBundle',
                'Pim/ProductEnrichment/WebApi/Infrastructure/Http',
                'Extract Web API HTTP infrastructure'
            ),
            new MoveLegacyNamespace(
                'Pim/ProductEnrichment/WebApi/Infrastructure/Http/Doctrine',
                'Pim/ProductEnrichment/WebApi/Infrastructure/Doctrine',
                'Extract Web API Doctrine infrastructure'
            ),
            new MoveLegacyNamespace(
                'Pim/ProductEnrichment/WebApi/Infrastructure/Http/Command',
                'Pim/ProductEnrichment/WebApi/Infrastructure/Cli/Command',
                'Extract Web API Cli infrastructure'
            ),
        ];

        $mover = new FsNamespaceExtractor($path);
        $renamer = new FsNamespaceRenamer($path);
        $handler = new MoveLegacyNamespaceHandler($mover, $renamer);
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
        $contextNames = [
            'UserManagement',
            'CatalogSetup',
            'ProductStructure',
            'ProductEnrichment/Core',
            'ProductEnrichment/WebApi',
            'ProductEnrichment/ImportExport',
        ];
        $command = new CreateBoundedContexts($contextNames);
        $repository = new FsBoundedContextRepository($path);
        $handler = new CreateBoundedContextsHandler($repository);
        $handler->handle($command);
        $output->writeln(sprintf("<info>Following bounded contexts have been created %s</info>", implode(', ', $contextNames)));
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