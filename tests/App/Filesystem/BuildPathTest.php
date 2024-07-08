<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\App\Filesystem;

use Ssc\Btlr\App\Filesystem\BuildPath;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class BuildPathTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_builds_a_path_by_joining_sub_paths(): void
    {
        self::assertSame('./composer.json', (new BuildPath())->joining('.', 'composer.json'));
        self::assertSame('./composer.json', (new BuildPath())->joining('./', '/composer.json'));
        self::assertSame('/home/user/docs', (new BuildPath())->joining('/home/', '/user/', 'docs'));
    }
}
