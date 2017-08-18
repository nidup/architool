<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\Model;

class SpecConfigurationFile
{
    private $configuredFolders;

    public function __construct()
    {
        $this->configuredFolders = [];
    }

    /**
     * @param Folder $folder
     */
    public function configure(Folder $folder)
    {
        $this->configuredFolders[]= $folder;
    }

    /**
     * @param Folder $folder
     */
    public function reconfigure(Folder $folder)
    {
        $this->configuredFolders[]= $folder;
    }

    /**
     * @return array
     */
    public function getConfiguredFolders(): array
    {
        return $this->configuredFolders;
    }
}
