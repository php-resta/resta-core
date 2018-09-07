<?php

namespace Resta\Console\Source\Migration;

use Migratio\SchemaFacade;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;

class Migration extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='migration';

    /**
     * @var $define
     */
    public $define='creating migration on console';

    /**
     * @var $commandRule
     */
    public $commandRule=[];

    /**
     *
     */
    public function pull()
    {
        //
    }

    /**
     *
     */
    public function push()
    {
        $config = ['paths'=>[app()->path()->migration()],'database'=>config('database')];

        $schema = SchemaFacade::setConfig($config);

        dd($schema->push());
    }

    /**
     *
     */
    public function create()
    {
        //
    }
}