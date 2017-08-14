<?php

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactoring\MoveLegacyClass;
use Nidup\Architool\Application\Refactoring\MoveLegacyNamespace;

class OneStep implements Step
{

    public function getDescription(): string
    {
        return 'my step name';
    }

    public function createReworkCodebaseCommands() : array
    {
        $commands = [

            new MoveLegacyNamespace(
                'Pim/Component/Catalog',
                'Pim/ProductEnrichment/Core/Domain',
                'Extract product enrichment core business'
            ),

            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain',
                'Pim/ProductStructure/Domain',
                'AttributeTypes',
                'Extract attribute types'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain',
                'Pim/ProductStructure/Domain',
                'AttributeTypeInterface',
                'Extract attribute types'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain',
                'Pim/ProductStructure/Domain',
                'AttributeTypeRegistry',
                'Extract attribute types'
            ),

            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'AttributeGroupInterface',
                'Extract attribute in product structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'AttributeGroupTranslationInterface',
                'Extract attribute in product structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'AttributeInterface',
                'Extract attribute in product structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'AbstractAttribute',
                'Extract attribute in product structure'
            ),

            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'AttributeOptionInterface',
                'Extract attribute option in product structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'AttributeOptionValueInterface',
                'Extract attribute option in product structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'AttributeRequirementInterface',
                'Extract attribute in product structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'AttributeTranslationInterface',
                'Extract attribute in product structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'FamilyInterface',
                'Extract family in product structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'FamilyTranslationInterface',
                'Extract family in product structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'CompletenessInterface',
                'Extract completeness in product structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/ProductStructure/Domain/Model',
                'Completeness',
                'Extract completeness in product structure'
            ),


            new MoveLegacyNamespace(
                'Pim/Bundle/CatalogBundle/AttributeType',
                'Pim/ProductStructure/Domain/AttributeType',
                'Extract product attribute types'
            ),


            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/CatalogStructure/Domain/Model',
                'ChannelInterface',
                'Extract channel in catalog structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/CatalogStructure/Domain/Model',
                'ChannelTranslationInterface',
                'Extract channel in catalog structure'
            ),
            new MoveLegacyClass(
                'Pim/ProductEnrichment/Core/Domain/Model',
                'Pim/CatalogStructure/Domain/Model',
                'LocaleInterface',
                'Extract Locale in catalog structure'
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
            ),

        ];

        return $commands;
    }
}