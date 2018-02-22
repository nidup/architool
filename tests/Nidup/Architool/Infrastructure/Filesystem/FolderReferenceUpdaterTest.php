<?php
use Nidup\Architool\Domain\Model\Folder;
use Nidup\Architool\Domain\Model\Folder\FolderNamespace;
use Nidup\Architool\Infrastructure\Filesystem\FolderReferenceUpdater;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;

class FolderReferenceUpdaterTest extends TestCase
{
    private $fsRoot;

    public function setUp()
    {
        vfsStreamWrapper::register();

        $fsRootStructure = [
            'My' => [
                'Project' => [
                    'Dir' => [
                        'features' => [],
                        'tests' => [],
                        'app' => [
                            'config' => []
                        ],
                        'src' => [
                            'Akeneo' => [
                                'Component' => [
                                    'Batch' => [],
                                    'BatchQueue' => [],
                                ],
                                'Pim' => [
                                    'Platform' => [
                                        'Domain' => [
                                            'Batch' => []
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->fsRoot = vfsStream::setup('root', null, $fsRootStructure);
    }

    public function test_when_i_move_a_namespace_i_update_the_declarations()
    {
        $filePath =  $this->fsRoot->url().'/My/Project/Dir/src/Akeneo/Component/Batch/MyClassInBatchComponent.php';
        file_put_contents(
            $filePath,
            'namespace Akeneo\Component\Batch;'
        );

        $folder = new Folder(new FolderNamespace('Akeneo/Component/Batch'));
        $folder->move(new FolderNamespace('Akeneo/Pim/Platform/Domain/Batch'));

        $updater = new FolderReferenceUpdater($this->fsRoot->url().'/My/Project/Dir');

        $updater->update($folder);

        $this->assertEquals(
            'namespace Akeneo\Pim\Platform\Domain\Batch;',
            file_get_contents($filePath)
        );
    }

    /**
     * Bug found: moving folder MyDir/Batch to MyDir2/Batch changes
     * "namespace MyDir/BatchQueue" to "namespace MyDir2/BatchQueue",
     * whereas it shouldn't have been changed.
     */
    public function test_when_i_move_a_namespace_i_dont_update_the_declaration_of_a_different_namespace_with_the_same_beginning()
    {
        $filePath =  $this->fsRoot->url().'/My/Project/Dir/src/Akeneo/Component/BatchQueue/MyClassInBatchQueueComponent.php';
        $namespaceDeclaration = 'namespace Akeneo\Component\BatchQueue;';
        file_put_contents(
            $filePath,
            $namespaceDeclaration
        );

        $folder = new Folder(new FolderNamespace('Akeneo/Component/Batch'));
        $folder->move(new FolderNamespace('Akeneo/Pim/Platform/Domain/Batch'));

        $updater = new FolderReferenceUpdater($this->fsRoot->url().'/My/Project/Dir');

        $updater->update($folder);

        $this->assertEquals(
            $namespaceDeclaration,
            file_get_contents($filePath)
        );
    }

    public function test_when_i_move_a_namespace_I_update_usè_statements()
    {
        $filePath =  $this->fsRoot->url().'/My/Project/Dir/src/MyFileUsingBatch.php';
        file_put_contents(
            $filePath,
            'use Akeneo\Component\Batch\SomeClassFromBatchComponent;'
        );

        $folder = new Folder(new FolderNamespace('Akeneo/Component/Batch'));
        $folder->move(new FolderNamespace('Akeneo/Pim/Platform/Domain/Batch'));

        $updater = new FolderReferenceUpdater($this->fsRoot->url().'/My/Project/Dir');

        $updater->update($folder);

        $this->assertEquals(
            'use Akeneo\Pim\Platform\Domain\Batch\SomeClassFromBatchComponent;',
            file_get_contents($filePath)
        );
    }

    /**
     * Bug found: moving folder MyDir/Batch to MyDir2/Batch changes
     * "use MyDir/BatchQueue" to "use MyDir2/BatchQueue",
     * whereas it shouldn't have been changed.
     */
    public function test_when_i_move_a_namespace_i_dont_update_use_statement_of_a_different_namespace_with_the_same_beginning()
    {
        $filePath =  $this->fsRoot->url().'/My/Project/Dir/src/MyFileUsingBatchQueue.php';
        $namespaceReference = 'use Akeneo\Component\BatchQueue\SomeClassFromBatchQueueComponent;';
        file_put_contents(
            $filePath,
            $namespaceReference
        );

        $folder = new Folder(new FolderNamespace('Akeneo/Component/Batch'));
        $folder->move(new FolderNamespace('Akeneo/Pim/Platform/Domain/Batch'));

        $updater = new FolderReferenceUpdater($this->fsRoot->url().'/My/Project/Dir');

        $updater->update($folder);

        $this->assertEquals(
            $namespaceReference,
            file_get_contents($filePath)
        );
    }
}
