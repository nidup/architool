<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactor\ConfigureSpecFolder;
use Nidup\Architool\Application\Refactor\ReconfigureSpecFolder;
use Nidup\Architool\Application\Refactor\MoveLegacyFolder;
use Nidup\Architool\Application\Refactoring\ReplaceCodeInClass;

class CreateProductEnrichment implements Step
{

    public function getDescription(): string
    {
        return 'Create product enrichment bounded context';
    }

    public function createReworkCodebaseCommands() : array
    {
        $commands = [

            // Domain
            new MoveLegacyFolder(
                'Pim/Component/Catalog',
                'Akeneo/Pim/ProductEnrichment/Domain',
                'Move catalog component as product enrichment domain (some parts will be extracted later in product structure & catalog structure)'
            ),
            new ReconfigureSpecFolder(
                'Pim/Component/Catalog',
                'Akeneo/Pim/ProductEnrichment/Domain',
                'Configure product enrichment specs'
            ),
            new ReplaceCodeInClass(
                'Akeneo/Pim/ProductEnrichment/Domain/spec/Updater/Setter',
                'MediaAttributeSetterSpec',
                "/../../../../../../../features/Context/fixtures/akeneo.jpg",
                "/../../../../../../../../features/Context/fixtures/akeneo.jpg",
                'Fix relative path use in MediaAttributeSetterSpec'
            ),
            /*
             * Needs to register through a ResolveDoctrineTargetModelPass
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/Entity',
                'Akeneo/Pim/ProductEnrichment/Domain/Entity',
                'Extract anemic models'
            ),
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/spec/Entity',
                'Akeneo/Pim/ProductEnrichment/Domain/spec/Entity',
                'Extract anemic models'
            ),*/

            // Infrastructure ElasticSearch
            new MoveLegacyFolder(
                'Pim/Bundle/CatalogBundle/Elasticsearch',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Elasticsearch/Core',
                'Extract ElasticSearch infrastructure'
            ),
            new MoveLegacyFolder(
                'Pim/Bundle/CatalogBundle/spec/Elasticsearch',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Elasticsearch/Core/spec',
                'Extract ElasticSearch infrastructure'
            ),
            new ConfigureSpecFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Elasticsearch/Core',
                'Extract ElasticSearch infrastructure'
            ),

            // Infrastructure Doctrine
            new MoveLegacyFolder(
                'Pim/Bundle/CatalogBundle/Doctrine',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Core',
                'Extract Doctrine infrastructure'
            ),
            new MoveLegacyFolder(
                'Pim/Bundle/CatalogBundle/spec/Doctrine',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Core/spec',
                'Extract Doctrine infrastructure'
            ),
            new ConfigureSpecFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Core',
                'Extract Doctrine infrastructure'
            ),
            new ReplaceCodeInClass(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Core/spec/Common/Saver/',
                'ProductUniqueDataSynchronizerSpec',
                "namespace spec\\\\Pim\\\\Bundle\\\\CatalogBundle\\\\Saver\\\\Common",
                "namespace spec\Akeneo\Pim\ProductEnrichment\Infrastructure\Doctrine\Core\Common\Saver",
                'Fix namespace of ProductUniqueDataSynchronizerSpec misplaced on master'
            ),

            // Infrastructure Symfony
            new MoveLegacyFolder(
                'Pim/Bundle/CatalogBundle',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core',
                'Extract Symfony infrastructure'
            ),
            new ReconfigureSpecFolder(
                'Pim/Bundle/CatalogBundle',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core',
                'Extract Symfony infrastructure'
            ),

            // Infrastructure Cli
            new MoveLegacyFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core/Command',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/Core/Command',
                'Extract Cli infrastructure'
            ),
            new MoveLegacyFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core/spec/Command',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/Core/spec/Command',
                'Extract Cli infrastructure'
            ),
            new ConfigureSpecFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/Core',
                'Extract Cli infrastructure'
            ),

            // API Application
            new MoveLegacyFolder(
                'Pim/Component/Api',
                'Akeneo/Pim/ProductEnrichment/Application/Api',
                'Extract API features in application'
            ),
            new ReconfigureSpecFolder(
                'Pim/Component/Api',
                'Akeneo/Pim/ProductEnrichment/Application/Api',
                'Extract API features in application'
            ),

            // API Infrastructure Http Web
            new MoveLegacyFolder(
                'Pim/Bundle/ApiBundle',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api',
                'Extract API Http infrastructure'
            ),
            new ReconfigureSpecFolder(
                'Pim/Bundle/ApiBundle',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api',
                'Extract API Http infrastructure'
            ),
            // API Infrastructure Cli
            new MoveLegacyFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api/Command',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/Api/Command',
                'Extract API Cli infrastructure'
            ),
            // API Infrastructure Doctrine
            new MoveLegacyFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api/Doctrine',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Api',
                'Extract API Doctrine infrastructure'
            ),

            // Import/Export Application
            new MoveLegacyFolder(
                'Pim/Component/Connector',
                'Akeneo/Pim/ProductEnrichment/Application/ImportExport',
                'Extract Import/Export features in application'
            ),
            new ReconfigureSpecFolder(
                'Pim/Component/Connector',
                'Akeneo/Pim/ProductEnrichment/Application/ImportExport',
                'Extract Import/Export features in application'
            ),

            // Import/Export Infrastructure Symfony
            new MoveLegacyFolder(
                'Pim/Bundle/ConnectorBundle',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport',
                'Extract Import/Export symfony infrastructure'
            ),
            new ReconfigureSpecFolder(
                'Pim/Bundle/ConnectorBundle',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport',
                'Extract Import/Export symfony infrastructure'
            ),
            // Import/Export Infrastructure Cli
            new MoveLegacyFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport/Command',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/ImportExport/Command',
                'Extract Import/Export Cli infrastructure'
            ),
            // Import/Export Infrastructure Doctrine
            new MoveLegacyFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport/Doctrine',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/ImportExport',
                'Extract Import/Export Doctrine infrastructure'
            ),
            new MoveLegacyFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport/spec/Doctrine',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/ImportExport/spec',
                'Extract Import/Export Doctrine infrastructure'
            ),
            new ConfigureSpecFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/ImportExport',
                'Extract Import/Export Doctrine infrastructure'
            ),


            // Edition Application
            new MoveLegacyFolder(
                'Pim/Component/Enrich',
                'Akeneo/Pim/ProductEnrichment/Application/Edition',
                'Extract Edition features in application'
            ),
            new ReconfigureSpecFolder(
                'Pim/Component/Enrich',
                'Akeneo/Pim/ProductEnrichment/Application/Edition',
                'Extract Edition features in application'
            ),
        ];

        return $commands;
    }
}