<?php

namespace Resta\Console\Source\Project;

use const Grpc\STATUS_ABORTED;
use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class Project extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='project';

    /**
     * @var $define
     */
    public $define='Project Set';

    /**
     * @var $command_create
     */
    public $command_create='php api project create [projectName]';

    /**
     * @method create
     * @return mixed
     */
    public function create(){

        //get project directory all path
        $this->directory['kernelDir']               = $this->kernel();
        $this->directory['repositoryDir']           = $this->repository();
        $this->directory['middleWareDir']           = $this->middleware();
        $this->directory['nodeDir']                 = $this->node();
        $this->directory['onceDir']                 = $this->once();
        $this->directory['commandDir']              = $this->command();
        $this->directory['stubDir']                 = $this->stub();
        $this->directory['storageDir']              = $this->storage();
        $this->directory['logDir']                  = $this->log();
        $this->directory['resourceDir']             = $this->resource();
        $this->directory['resourceCacheDir']        = $this->resource().'/'.StaticPathModel::$cache;
        $this->directory['languageDir']             = $this->language();
        $this->directory['sessionDir']              = $this->session();
        $this->directory['versionDir']              = $this->version();
        $this->directory['callDir']                 = $this->controller();
        $this->directory['modelDir']                = $this->model();
        $this->directory['builderDir']              = $this->builder();
        $this->directory['migrationDir']            = $this->migration();
        $this->directory['configDir']               = $this->config();
        $this->directory['optionalDir']             = $this->optional();
        $this->directory['sourceDir']               = $this->sourceDir();
        $this->directory['sourceEndpointDir']       = $this->sourceEndpointDir();
        $this->directory['sourceRequestDir']        = $this->sourceRequestDir();
        $this->directory['sourceSupportDir']        = $this->sourceSupportDir();
        $this->directory['sourceSupportTraitDir']   = $this->sourceSupportDir().'/Traits';
        $this->directory['jobsDir']                 = $this->job();
        $this->directory['webserviceDir']           = $this->webservice();

        //set project directory
        $this->file->makeDirectory($this);


        //get project file all path
        $this->touch['ignore']                      = $this->project.'/.gitignore';
        $this->touch['publish']                     = $this->project.'/publish.php';
        $this->touch['main/version']                = $this->project.'/version.php';
        $this->touch['repository/index']            = $this->repository().'/index.html';
        $this->touch['source/index']                = $this->sourceDir().'/index.html';
        $this->touch['source/index1']               = $this->sourceEndpointDir().'/index.html';
        $this->touch['source/index2']               = $this->sourceRequestDir().'/index.html';
        $this->touch['source/index3']               = $this->sourceSupportDir().'/index.html';
        $this->touch['kernel/kernel']               = $this->kernel().'/Kernel.php';
        $this->touch['middleware/validation']       = $this->middleware().'/Validation.php';
        $this->touch['middleware/clientToken']      = $this->middleware().'/ClientApiToken.php';
        $this->touch['middleware/settimezone']      = $this->middleware().'/SetClientTimezone.php';
        $this->touch['once/index']                  = $this->once().'/index.html';
        $this->touch['command/index']               = $this->command().'/index.html';
        $this->touch['node/index']                  = $this->node().'/index.html';
        $this->touch['stub/index']                  = $this->stub().'/index.html';
        $this->touch['language/index']              = $this->language().'/index.html';
        $this->touch['log/index']                   = $this->log().'/index.html';
        $this->touch['resource/index']              = $this->resource().'/index.html';
        $this->touch['resource/index']              = $this->resource().'/'.StaticPathModel::$cache.'/index.html';
        $this->touch['session/index']               = $this->session().'/index.html';
        $this->touch['controller/index']            = $this->controller().'/index.html';
        $this->touch['config/hateoas']              = $this->config().'/Hateoas.php';
        $this->touch['config/redis']                = $this->config().'/Redis.php';
        $this->touch['config/database']             = $this->config().'/Database.php';
        $this->touch['job/index']                   = $this->job().'/index.html';
        $this->touch['migration/index']             = $this->migration().'/index.html';
        $this->touch['builder/index']               = $this->builder().'/index.html';
        $this->touch['webservice/index']            = $this->webservice().'/index.html';
        $this->touch['version/annotations']         = $this->version().'/ServiceAnnotationsController.php';
        $this->touch['version/servicecontainer']    = $this->version().'/ServiceContainerController.php';
        $this->touch['version/servicemiddleware']   = $this->version().'/ServiceMiddlewareController.php';
        $this->touch['version/base']                = $this->version().'/ServiceBaseController.php';
        $this->touch['version/base']                = $this->version().'/ServiceBaseController.php';
        $this->touch['version/log']                 = $this->version().'/ServiceLogController.php';
        $this->touch['version/tool']                = $this->version().'/ServiceToolsController.php';
        $this->touch['source/apitokentrait']        = $this->sourceSupportDir().'/Traits/ClientApiTokenTrait.php';

        //set project touch
        $this->file->touch($this);

        Utils::chmod($this->project);


        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->yellowPrint('Project Named ['.$this->argument['project'].'] Has Been Successfully Created');
        echo $this->classical('---------------------------------------------------------------------------');
        echo $this->cyan('   You can see in src/app your project   ');
        echo $this->classical('---------------------------------------------------------------------------');
    }
}