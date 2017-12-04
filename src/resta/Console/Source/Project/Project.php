<?php

namespace Resta\Console\Source\Project;

use Resta\Console\ConsoleListAccessor;
use Resta\Console\ConsoleOutputter;

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
        $this->directory['kernelDir']        = $this->kernel();
        $this->directory['repositoryDir']    = $this->repository();
        $this->directory['middleWareDir']    = $this->middleware();
        $this->directory['nodeDir']          = $this->node();
        $this->directory['onceDir']          = $this->once();
        $this->directory['stubDir']          = $this->stub();
        $this->directory['storageDir']       = $this->storage();
        $this->directory['logDir']           = $this->log();
        $this->directory['resourceDir']      = $this->resource();
        $this->directory['languageDir']      = $this->language();
        $this->directory['sessionDir']       = $this->session();
        $this->directory['versionDir']       = $this->version();
        $this->directory['callDir']          = $this->controller();
        $this->directory['modelDir']         = $this->model();
        $this->directory['builderDir']       = $this->builder();
        $this->directory['migrationDir']     = $this->migration();
        $this->directory['configDir']        = $this->config();
        $this->directory['optionalDir']      = $this->optional();
        $this->directory['jobsDir']          = $this->job();
        $this->directory['webserviceDir']    = $this->webservice();

        //set project directory
        $this->file->makeDirectory($this);


        //get project file all path
        $this->touch['ignore']                      = $this->project.'/.gitignore';
        $this->touch['publish']                     = $this->project.'/publish.php';
        $this->touch['version']                     = $this->project.'/version.php';
        $this->touch['repository/index']            = $this->repository().'/index.html';
        $this->touch['kernel/kernel']               = $this->kernel().'/Kernel.php';
        $this->touch['middleware/index']            = $this->middleware().'/index.html';
        $this->touch['once/index']                  = $this->once().'/index.html';
        $this->touch['node/index']                  = $this->node().'/index.html';
        $this->touch['stub/index']                  = $this->stub().'/index.html';
        $this->touch['language/index']              = $this->language().'/index.html';
        $this->touch['log/index']                   = $this->log().'/index.html';
        $this->touch['resource/index']              = $this->resource().'/index.html';
        $this->touch['session/index']               = $this->session().'/index.html';
        $this->touch['controller/index']            = $this->controller().'/index.html';
        $this->touch['config/index']                = $this->config().'/index.html';
        $this->touch['config/hateoas']              = $this->config().'/Hateoas.php';
        $this->touch['config/database']             = $this->config().'/Database.php';
        $this->touch['job/index']                   = $this->job().'/index.html';
        $this->touch['migration/index']             = $this->migration().'/index.html';
        $this->touch['builder/index']               = $this->builder().'/index.html';
        $this->touch['webservice/index']            = $this->webservice().'/index.html';
        $this->touch['version/annotations']         = $this->version().'/ServiceAnnotationsController.php';
        $this->touch['version/serviceboot']         = $this->version().'/ServiceBootController.php';
        $this->touch['version/base']                = $this->version().'/ServiceBaseController.php';
        $this->touch['version/base']                = $this->version().'/ServiceBaseController.php';
        $this->touch['version/token']               = $this->version().'/ServiceTokenController.php';
        $this->touch['version/log']                 = $this->version().'/ServiceLogController.php';
        $this->touch['version/tool']                = $this->version().'/ServiceToolsController.php';

        //set project touch
        $this->file->touch($this);


        return $this->blue('Project Has Been Successfully Created');
    }
}