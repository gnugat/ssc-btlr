<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Filesystem\Format;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Symfony\Component\Yaml\Yaml;

class ReadYamlFile
{
    public function __construct(
        private ReadFile $readFile,
    ) {
    }

    public function in(string $filename): array
    {
        return Yaml::parse($this->readFile->in($filename));
    }
}
