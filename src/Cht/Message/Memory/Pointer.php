<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Memory;

use Ssc\Btlr\App\Filesystem\FileExists;
use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;
use Ssc\Btlr\Cht\Message\Memory\Pointer\Make;

class Pointer
{
    public function __construct(
        private FileExists $fileExists,
        private Make $make,
        private ReadYamlFile $readYamlFile,
    ) {
    }

    public function get(
        array $withConfig,
    ): array {
        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.yaml";
        if (false === $this->fileExists->in($memoryPointerFilename)) {
            return $this->make->brandNew($withConfig);
        }

        return $this->readYamlFile->in($memoryPointerFilename);
    }
}
