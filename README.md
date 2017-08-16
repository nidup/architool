# Architool

Experiments with Akeneo PIM code base, Hexagonal Architecture & Domain-Driven Design :rocket:

This tool helps to re-arrange our legacy Akeneo PIM code base by applying a set of commands.

The execution maintains a usable PIM with migrated unit tests (for now, no migration of integration, functional and system tests).

The purpose is to provide a support to discuss by demonstrating several working approaches.

These approaches are easier to understand thanks to the output of self-explained operations.

## Requirements

- Composer
- PHP 7.1
- Git
- Akeneo PIM dev version 1.8

## How To Install

Run `composer.phar update --prefer-dist` to install the tool's dependencies.

## How To Use

Run `bin/console nidup:architool:hexagonalize /home/nico/git/pim-ce-18 -v`

```
Hexagonalize it! (Ports & Adapter FTW)
      __
   __/  \__
  /  \__/  \
  \__/  \__/
  /  \__/  \
  \__/  \__/
     \__/

[x] Prepare project workspace "/home/nico/git/pim-ce-18/"

[x] Create bounded contexts Akeneo/Pim/ProductEnrichment, Akeneo/Pim/ProductStructure, Akeneo/Pim/CatalogSetup, Akeneo/Pim/UserManagement

[x] Move Akeneo components & bundles in Akeneo/Common
 - Extract namespace "Akeneo/Component" to "Akeneo/Common/Component" in order to "Move Akeneo common components"
 - Re-configure specs to match "Akeneo/Common/Component" in order to "Move Akeneo common components"
 - Extract namespace "Akeneo/Bundle" to "Akeneo/Common/Bundle" in order to "Move Akeneo common bundles"
 - Re-configure specs to match "Akeneo/Common/Bundle" in order to "Move Akeneo common bundles"

[x] Create product enrichment bounded context
 - Extract namespace "Pim/Component/Catalog" to "Akeneo/Pim/ProductEnrichment/Domain" in order to "Move catalog component as product enrichment domain (some parts will be extracted later in product structure & catalog structure)"
 - Re-configure specs to match "Akeneo/Pim/ProductEnrichment/Domain" in order to "Configure product enrichment specs"
 - Replace code fragment "/../../../../../../../../features/Context/fixtures/akeneo.jpg" in "Akeneo/Pim/ProductEnrichment/Domain/spec/Updater/Setter/MediaAttributeSetterSpec" in order to "Fix relative path use in MediaAttributeSetterSpec"
 - Extract namespace "Pim/Bundle/CatalogBundle/Elasticsearch" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Elasticsearch/Core" in order to "Extract ElasticSearch infrastructure"
 - Extract namespace "Pim/Bundle/CatalogBundle/spec/Elasticsearch" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Elasticsearch/Core/spec" in order to "Extract ElasticSearch infrastructure"
 - Configure specs to match "Akeneo/Pim/ProductEnrichment/Infrastructure/Elasticsearch/Core" in order to "Extract ElasticSearch infrastructure"
 - Extract namespace "Pim/Bundle/CatalogBundle/Doctrine" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Core" in order to "Extract Doctrine infrastructure"
 - Extract namespace "Pim/Bundle/CatalogBundle/spec/Doctrine" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Core/spec" in order to "Extract Doctrine infrastructure"
 - Configure specs to match "Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Core" in order to "Extract Doctrine infrastructure"
 - Replace code fragment "namespace spec\Akeneo\Pim\ProductEnrichment\Infrastructure\Doctrine\Core\Common\Saver" in "Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Core/spec/Common/Saver//ProductUniqueDataSynchronizerSpec" in order to "Fix namespace of ProductUniqueDataSynchronizerSpec misplaced on master"
 - Extract namespace "Pim/Bundle/CatalogBundle" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core" in order to "Extract Symfony infrastructure"
 - Re-configure specs to match "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core" in order to "Extract Symfony infrastructure"
 - Extract namespace "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core/Command" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/Core/Command" in order to "Extract Cli infrastructure"
 - Extract namespace "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core/spec/Command" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/Core/spec/Command" in order to "Extract Cli infrastructure"
 - Configure specs to match "Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/Core" in order to "Extract Cli infrastructure"
 - Extract namespace "Pim/Component/Api" to "Akeneo/Pim/ProductEnrichment/Application/Api" in order to "Extract API features in application"
 - Re-configure specs to match "Akeneo/Pim/ProductEnrichment/Application/Api" in order to "Extract API features in application"
 - Extract namespace "Pim/Bundle/ApiBundle" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api" in order to "Extract API Http infrastructure"
 - Re-configure specs to match "Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api" in order to "Extract API Http infrastructure"
 - Extract namespace "Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api/Command" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/Api/Command" in order to "Extract API Cli infrastructure"
 - Extract namespace "Akeneo/Pim/ProductEnrichment/Infrastructure/Http/Api/Doctrine" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/Api" in order to "Extract API Doctrine infrastructure"
 - Extract namespace "Pim/Component/Connector" to "Akeneo/Pim/ProductEnrichment/Application/ImportExport" in order to "Extract Import/Export features in application"
 - Re-configure specs to match "Akeneo/Pim/ProductEnrichment/Application/ImportExport" in order to "Extract Import/Export features in application"
 - Extract namespace "Pim/Bundle/ConnectorBundle" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport" in order to "Extract Import/Export symfony infrastructure"
 - Re-configure specs to match "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport" in order to "Extract Import/Export symfony infrastructure"
 - Extract namespace "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport/Command" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Cli/ImportExport/Command" in order to "Extract Import/Export Cli infrastructure"
 - Extract namespace "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport/Doctrine" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/ImportExport" in order to "Extract Import/Export Doctrine infrastructure"
 - Extract namespace "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/ImportExport/spec/Doctrine" to "Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/ImportExport/spec" in order to "Extract Import/Export Doctrine infrastructure"
 - Configure specs to match "Akeneo/Pim/ProductEnrichment/Infrastructure/Doctrine/ImportExport" in order to "Extract Import/Export Doctrine infrastructure"
 - Extract namespace "Pim/Component/Enrich" to "Akeneo/Pim/ProductEnrichment/Application/Edition" in order to "Extract Edition features in application"
 - Re-configure specs to match "Akeneo/Pim/ProductEnrichment/Application/Edition" in order to "Extract Edition features in application"

[x] Create product structure bounded context
 - Extract class "AttributeTypes" to "Akeneo/Pim/ProductStructure/Domain" in order to "Extract attribute types"
 - Extract class "AttributeTypeInterface" to "Akeneo/Pim/ProductStructure/Domain" in order to "Extract attribute types"
 - Extract class "AttributeTypeRegistry" to "Akeneo/Pim/ProductStructure/Domain" in order to "Extract attribute types"
 - Extract spec "AttributeTypeRegistrySpec" to "Akeneo/Pim/ProductStructure/Domain/spec" in order to "Extract attribute types"
 - Configure specs to match "Akeneo/Pim/ProductStructure/Domain" in order to "Configure Domain specs"
 - Extract namespace "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core/AttributeType" to "Akeneo/Pim/ProductStructure/Domain/AttributeType" in order to "Extract product attribute types"
 - Extract namespace "Akeneo/Pim/ProductEnrichment/Infrastructure/Symfony/Core/spec/AttributeType" to "Akeneo/Pim/ProductStructure/Domain/spec/AttributeType" in order to "Extract product attribute types"
 - Extract class "AttributeInterface" to "Akeneo/Pim/ProductStructure/Domain/Model" in order to "Extract attribute"
 - Extract class "AttributeGroupInterface" to "Akeneo/Pim/ProductStructure/Domain/Model" in order to "Extract attribute"
 - Extract class "AttributeGroupTranslationInterface" to "Akeneo/Pim/ProductStructure/Domain/Model" in order to "Extract attribute"
 - Extract class "AbstractAttribute" to "Akeneo/Pim/ProductStructure/Domain/Model" in order to "Extract attribute"
 - Extract class "AttributeOptionInterface" to "Akeneo/Pim/ProductStructure/Domain/Model" in order to "Extract attribute option"
 - Extract class "AttributeOptionValueInterface" to "Akeneo/Pim/ProductStructure/Domain/Model" in order to "Extract attribute option"
 - Extract class "AttributeRequirementInterface" to "Akeneo/Pim/ProductStructure/Domain/Model" in order to "Extract attribute requirements"
 - Extract class "AttributeTranslationInterface" to "Akeneo/Pim/ProductStructure/Domain/Model" in order to "Extract attribute"
 - Extract class "FamilyInterface" to "Akeneo/Pim/ProductStructure/Domain/Model" in order to "Extract family"
 - Extract class "FamilyTranslationInterface" to "Akeneo/Pim/ProductStructure/Domain/Model" in order to "Extract family"

[x] Create user management bounded context
 - Extract namespace "Pim/Component/User" to "Akeneo/Pim/UserManagement/Domain" in order to "Move user component as user management domain (some parts will be extracted later on)"

[x] Project cache has been cleaned

```
