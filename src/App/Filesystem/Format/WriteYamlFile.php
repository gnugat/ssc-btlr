<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Filesystem\Format;

use Ssc\Btlr\App\Filesystem\WriteFile;
use Symfony\Component\Yaml\Yaml;

class WriteYamlFile
{
    private const int EXPANDED_ARRAYS = 2;
    private const int INDENTATION = 4;

    public function __construct(
        private WriteFile $writeFile,
    ) {
    }

    /** @param array<string, mixed> $content */
    public function in(
        string $filename,
        array $content,
        bool $createParentDirectoriesIfTheyDontExist = true,
    ): void {
        $this->writeFile->in($filename, Yaml::dump(
            $content,
            self::EXPANDED_ARRAYS,
            self::INDENTATION,
            Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK,
        ), $createParentDirectoriesIfTheyDontExist);
    }
}
