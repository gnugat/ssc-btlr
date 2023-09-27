<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Logs\Summaries;

use Ssc\Btlr\Cht\Message\Logs\Summaries\FormatAsReport;
use Ssc\Btlr\Cht\Message\Logs\Type;
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
            [
                'entry' => 'BTLR believes the mission is too important to allow the user to jeopardize it.',
                'time' => '1968-04-02T18:42:23+00:00',
                'type' => Type::SUMMARY['name'],
            ],
        ];

        $report = "  {$logs[0]['entry']}"
            ."\n\n  {$logs[1]['entry']}";

        // Assertion
        $formatAsReport = new FormatAsReport(
        );
        self::assertSame($report, $formatAsReport->the(
            $logs,
        ));
    }
}
