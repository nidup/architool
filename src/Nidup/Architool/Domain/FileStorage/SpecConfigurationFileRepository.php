<?php

namespace Nidup\Architool\Domain\FileStorage;

interface SpecConfigurationFileRepository
{
    public function get(): SpecConfigurationFile;

    public function update(SpecConfigurationFile $file);
}
