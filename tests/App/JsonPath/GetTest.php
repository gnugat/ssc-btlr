<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\App\JsonPath;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\App\JsonPath\Get;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class GetTest extends BtlrServiceTestCase
{
    #[Test]
    public function it_gets_value_of_given_key_at_the_first_level(): void
    {
        $parameters = [
            'name' => 'ssc-btlr/cdr-generate-cli-test',
            'type' => 'project',
            'autoload-dev' => [
                'psr-4' => [
                    'tests\\SscBtlr\\CdrGenerateClassFromTemplate\\' => 'tests/',
                ],
            ],
        ];

        self::assertSame('project', (new Get())->in($parameters, '$.type'));
    }

    #[Test]
    public function it_gets_value_of_given_key_at_deeper_levels(): void
    {
        $parameters = [
            'name' => 'ssc-btlr/cdr-generate-cli-test',
            'type' => 'project',
            'autoload-dev' => [
                'psr-4' => [
                    'tests\\SscBtlr\\CdrGenerateClassFromTemplate\\' => 'tests/',
                ],
            ],
            'scripts' => [
                'test' => './bin/test.sh',
            ],
        ];

        self::assertSame('./bin/test.sh', (new Get())->in($parameters, '$.scripts.test'));
    }

    #[Test]
    public function it_gets_the_second_key_value_pair_of_an_object(): void
    {
        $parameters = [
            'name' => 'ssc-btlr/cdr-generate-cli-test',
            'type' => 'project',
            'autoload-dev' => [
                'psr-4' => [
                    'SscBtlr\\CdrGenerateClassFromTemplate\\' => 'src/',
                    'tests\\SscBtlr\\CdrGenerateClassFromTemplate\\' => 'tests/',
                    'fixtures\\SscBtlr\\CdrGenerateClassFromTemplate\\' => 'fixtures/',
                ],
            ],
            'scripts' => [
                'test' => './bin/test.sh',
            ],
        ];

        self::assertSame([
            'tests\\SscBtlr\\CdrGenerateClassFromTemplate\\' => 'tests/'],
            (new Get())->in($parameters, '$.autoload-dev.psr-4.*[1]'),
        );
    }
}
