<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs;

use Ssc\Btlr\App\Filesystem\Format\WriteYamlFile;
use Ssc\Btlr\App\Identifier\Uuid;
use Ssc\Btlr\App\Time\Clock;

class WriteLog
{
    public function __construct(
        private Clock $clock,
        private MakeFilename $makeFilename,
        private Uuid $uuid,
        private WriteYamlFile $writeYamlFile,
    ) {
    }

    public function for(
        array $data,
        array $forType,
        array $withConfig,
    ): void {
        $data['time'] = $this->clock->inFormat('Y-m-d\TH:i:sP');
        $data['id'] = $this->uuid->make();
        $data['priority'] = $forType['priority'];
        $data['type'] = $forType['name'];

        $filename = $this->makeFilename->for($data, $withConfig);
        $this->writeYamlFile->in($filename, $data);
    }
}
