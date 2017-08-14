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


        /*
            new ConfigureSpecNamespace(
                'Pim/Bundle/CatalogBundle/Elasticsearch',
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Elasticsearch',
                'Configure ElasticSearch specs'
            ),

            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/Elasticsearch',
                'Pim/ProductEnrichment/Core/Infrastructure/Elasticsearch',
                'Extract ElasticSearch infrastructure'
            ),
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/spec/Elasticsearch',
                'Pim/ProductEnrichment/Core/Infrastructure/spec/Elasticsearch',
                'Extract ElasticSearch infrastructure'
            ),

            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/Doctrine',
                'Pim/ProductEnrichment/Core/Infrastructure/Doctrine',
                'Extract Doctrine infrastructure'
            ),
            // move its specs too!
            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/spec/Doctrine',
                'Pim/ProductEnrichment/Core/Infrastructure/spec/Doctrine',
                'Extract Doctrine infrastructure specs'
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
            ),*/

        ];

        return $commands;
    }
}