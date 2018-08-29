<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Logger\LoggerService;
use Resta\Contracts\BootContracts;

class LogProvider extends ApplicationProvider implements BootContracts {

    /**
     * @return mixed|void
     */
    public function boot(){

        // to help you learn more about what's happening within your application,
        // resta provides robust logging services that allow you to log messages to files,
        // the system error log, and even to Slack to notify your entire team.
        $this->app->bind('logger',function(){
            return LoggerService::class;
        });
    }

}