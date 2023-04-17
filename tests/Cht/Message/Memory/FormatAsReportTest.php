<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Memory;

use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Memory\FormatAsReport;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class FormatAsReportTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_formats_logs_as_report(): void
    {
        // Fixtures
        $logs = [
            [
                'entry' => 'User requested code, BTLR seemed unresponsive yet acknowledged user.',
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::SUMMARY['name'],
            ],
        ];

        $report = "{$logs[0]['entry']}\n";

        // Assertion
        $formatAsReport = new FormatAsReport(
        );
        self::assertSame($report, $formatAsReport->the(
            $logs,
        ));
    }
}
