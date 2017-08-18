<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Project\Pim;

use Nidup\Architool\Application\Project\Step;
use Nidup\Architool\Application\Refactor\MoveLegacyClassFile;
use Nidup\Architool\Application\Refactor\ConfigureSpecFolder;
use Nidup\Architool\Application\Refactor\MoveLegacyFolder;
use Nidup\Architool\Application\Refactor\MoveLegacySpecFile;

class CreateProductStructureStep implements Step
{

    public function getDescription(): string
    {
        return 'Create product structure bounded context';
    }

    public function createReworkCodebaseCommands() : array
    {
        $commands = [

            // Product Structure Domain: attribute type
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain',
                'Akeneo/Pim/ProductStructure/Domain',
                'AttributeTypes',
                'Extract attribute types'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain',
                'Akeneo/Pim/ProductStructure/Domain',
                'AttributeTypeInterface',
                'Extract attribute types'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain',
                'Akeneo/Pim/ProductStructure/Domain',
                'AttributeTypeRegistry',
                'Extract attribute types'
            ),
            new MoveLegacySpecFile(
                'Akeneo/Pim/ProductEnrichment/Domain/spec',
                'Akeneo/Pim/ProductStructure/Domain/spec',
                'AttributeTypeRegistrySpec',
                'Extract attribute types'
            ),
            new ConfigureSpecFolder(
                'Akeneo/Pim/ProductStructure/Domain',
                'Configure Domain specs'
            ),
            new MoveLegacyFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core/AttributeType',
                'Akeneo/Pim/ProductStructure/Domain/AttributeType',
                'Extract product attribute types'
            ),
            new MoveLegacyFolder(
                'Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core/spec/AttributeType',
                'Akeneo/Pim/ProductStructure/Domain/spec/AttributeType',
                'Extract product attribute types'
            ),

            // Product Structure Domain: attribute
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain/Model',
                'Akeneo/Pim/ProductStructure/Domain/Model',
                'AttributeInterface',
                'Extract attribute'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain/Model',
                'Akeneo/Pim/ProductStructure/Domain/Model',
                'AttributeGroupInterface',
                'Extract attribute'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain/Model',
                'Akeneo/Pim/ProductStructure/Domain/Model',
                'AttributeGroupTranslationInterface',
                'Extract attribute'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain/Model',
                'Akeneo/Pim/ProductStructure/Domain/Model',
                'AbstractAttribute',
                'Extract attribute'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain/Model',
                'Akeneo/Pim/ProductStructure/Domain/Model',
                'AttributeOptionInterface',
                'Extract attribute option'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain/Model',
                'Akeneo/Pim/ProductStructure/Domain/Model',
                'AttributeOptionValueInterface',
                'Extract attribute option'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain/Model',
                'Akeneo/Pim/ProductStructure/Domain/Model',
                'AttributeRequirementInterface',
                'Extract attribute requirements'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain/Model',
                'Akeneo/Pim/ProductStructure/Domain/Model',
                'AttributeTranslationInterface',
                'Extract attribute'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain/Model',
                'Akeneo/Pim/ProductStructure/Domain/Model',
                'FamilyInterface',
                'Extract family'
            ),
            new MoveLegacyClassFile(
                'Akeneo/Pim/ProductEnrichment/Domain/Model',
                'Akeneo/Pim/ProductStructure/Domain/Model',
                'FamilyTranslationInterface',
                'Extract family'
            ),
        ];

        return $commands;
    }
}