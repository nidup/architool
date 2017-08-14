<?php

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactoring\ConfigureSpecNamespace;
use Nidup\Architool\Application\Refactoring\ReconfigureSpecNamespace;
use Nidup\Architool\Application\Refactoring\MoveLegacyClass;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespace;
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
            new MoveLegacyNamespace(
                'Pim/Component/Catalog',
                'Akeneo/Pim/ProductEnrichment/Domain',
                'Move catalog component as product enrichment domain (some parts will be extracted later on)'
            ),
            new ReconfigureSpecNamespace(
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

            // Infrastructure ElasticSearch
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/Elasticsearch',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Elasticsearch',
                'Extract ElasticSearch infrastructure'
            ),
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/spec/Elasticsearch',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Elasticsearch/spec',
                'Extract specs for ElasticSearch infrastructure'
            ),
            new ConfigureSpecNamespace(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Elasticsearch',
                'Configure ElasticSearch specs'
            ),

            // Infrastructure Doctrine
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/Doctrine',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine',
                'Extract Doctrine infrastructure'
            ),
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/spec/Doctrine',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/spec',
                'Extract specs for Doctrine infrastructure'
            ),
            new ConfigureSpecNamespace(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine',
                'Configure Doctrine specs'
            ),
            new ReplaceCodeInClass(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/spec/Common/Saver/',
                'ProductUniqueDataSynchronizerSpec',
                "namespace spec\\\\Pim\\\\Bundle\\\\CatalogBundle\\\\Saver\\\\Common",
                "namespace spec\Akeneo\Pim\ProductEnrichment\Infrastructure\Doctrine\Common\Saver",
                'Fix namespace of ProductUniqueDataSynchronizerSpec misplaced on master'
            ),

            // API Application
            new MoveLegacyNamespace(
                'Pim/Component/Api',
                'Akeneo/Pim/ProductEnrichment/Application/Api',
                'Extract API features in application'
            ),
            new ReconfigureSpecNamespace(
                'Pim/Component/Api',
                'Akeneo/Pim/ProductEnrichment/Application/Api',
                'Configure API specs'
            ),

            // API Infrastructure Http Web
            new MoveLegacyNamespace(
                'Pim/Bundle/ApiBundle',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api',
                'Extract API Http infrastructure'
            ),
            new ReconfigureSpecNamespace(
                'Pim/Bundle/ApiBundle',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api',
                'Configure API Http infrastructure specs'
            ),
            // API Infrastructure Cli
            new MoveLegacyNamespace(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api/Command',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/Api/Command',
                'Extract API Cli infrastructure'
            ),
            // API Infrastructure Doctrine
            new MoveLegacyNamespace(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api/Doctrine',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Api',
                'Extract API Doctrine infrastructure'
            ),

            // Import/Export Application
            new MoveLegacyNamespace(
                'Pim/Component/Connector',
                'Akeneo/Pim/ProductEnrichment/Application/ImportExport',
                'Extract Import/Export features in application'
            ),
            new ReconfigureSpecNamespace(
                'Pim/Component/Connector',
                'Akeneo/Pim/ProductEnrichment/Application/ImportExport',
                'Configure Import/Export specs'
            ),

            // Import/Export Infrastructure Symfony
            new MoveLegacyNamespace(
                'Pim/Bundle/ConnectorBundle',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport',
                'Extract Import/Export symfony infrastructure'
            ),
            new ReconfigureSpecNamespace(
                'Pim/Bundle/ConnectorBundle',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport',
                'Configure Import/Export symfony infrastructure specs'
            ),
            // Import/Export Infrastructure Cli
            new MoveLegacyNamespace(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport/Command',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/ImportExport/Command',
                'Extract Import/Export Cli infrastructure'
            ),
            // Import/Export Infrastructure Doctrine
            new MoveLegacyNamespace(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport/Doctrine',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/ImportExport',
                'Extract Import/Export Doctrine infrastructure'
            ),
            new MoveLegacyNamespace(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport/spec/Doctrine',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/ImportExport/spec',
                'Extract Import/Export Doctrine infrastructure spec'
            ),
            new ConfigureSpecNamespace(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/ImportExport',
                'Configure Import/Export Doctrine infrastructure spec'
            ),
        ];

        return $commands;
    }
}