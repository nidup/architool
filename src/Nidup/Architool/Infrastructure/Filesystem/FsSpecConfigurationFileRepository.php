<?php

declare(strict_types=1);

namespace Nidup\Architool\Infrastructure\Filesystem;

use Nidup\Architool\Domain\Model\Folder;
use Nidup\Architool\Domain\Model\SpecConfigurationFile;
use Nidup\Architool\Domain\SpecConfigurationFileRepository;

class FsSpecConfigurationFileRepository implements SpecConfigurationFileRepository
{
    private $configurator;
    
    public function __construct(SpecConfigurationUpdater $configurator)
    {
        $this->configurator = $configurator;
    }

    public function get(): SpecConfigurationFile
    {
        return new SpecConfigurationFile();
    }

    public function update(SpecConfigurationFile $file)
    {
        /**
         * @var Folder $folder
         */
        foreach ($file->getConfiguredFolders() as $folder) {
            if ($folder->getNewNamespace() !== null) {
                $this->configurator->reconfigure($folder);
            } else {
                $this->configurator->configure($folder);
            }
        }
    }
}
