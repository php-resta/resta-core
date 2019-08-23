<?php

namespace Resta\Console\Source\Track;

use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;

class Track extends ConsoleOutputter
{
    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type = 'track';

    /**
     * @var $define
     */
    public $define = 'returns track information for application';

    /**
     * @var $commandRule
     */
    public $commandRule = [];

    /**
     * @return mixed|void
     */
    public function log()
    {
        $logger = app()->path()->appLog().''.DIRECTORY_SEPARATOR.''.date('Y').''.DIRECTORY_SEPARATOR.''.date('m').''.DIRECTORY_SEPARATOR.''.date('d').'-access.log';

        $tailCommand = 'tail -n 1 -f '.escapeshellarg($logger).'';

        while (@ ob_end_flush()); // end all output buffers if any

        $proc = popen($tailCommand, 'r');

        $number = 0;
        while (!feof($proc))
        {
            $result = fread($proc, 4096);
            if(preg_match('@\{(.*)\}@',$result,$output)){
                $outputArray = json_decode($output[0],1);

                $outputArray['trackNumber'] = ++$number;

                if(app()->has('track.log')){

                    $track = app()->get('track.log');
                    echo $track($outputArray);
                }
            }
            @ flush();
        }

    }
}