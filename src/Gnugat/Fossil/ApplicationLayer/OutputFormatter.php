<?php

/*
 * This file is part of the Fossil project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Fossil\ApplicationLayer;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;

/**
 * Formats the output with adequate colors.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class OutputFormatter extends LineFormatter
{
    const SIMPLE_FORMAT = "%start_tag%%message%%end_tag%\n";

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        if ($record['level'] >= Logger::ERROR) {
            $record['start_tag'] = '<error>';
            $record['end_tag']   = '</error>';
        } elseif ($record['level'] >= Logger::WARNING) {
            $record['start_tag'] = '<comment>';
            $record['end_tag']   = '</comment>';
        } elseif ($record['level'] >= Logger::INFO) {
            $record['start_tag'] = '<info>';
            $record['end_tag']   = '</info>';
        } else {
            $record['start_tag'] = '';
            $record['end_tag']   = '';
        }

        return parent::format($record);
    }
}
