<?php

namespace Resta\Console\Source\Project;

use Resta\Support\Utils;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathModel;

class Project extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='project';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates Application Skeleton'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var $commandRule
     */
    public $commandRule=[];

    /**
     * @method create
     * @return mixed
     */
    public function create()
    {
        $this->argument['kernelDir']            = Utils::getNamespace($this->kernel());
        $this->argument['kernelProviderDir']    = Utils::getNamespace($this->provider());
        $this->argument['factoryDir']           = app()->namespace()->factory();
        $this->directory['projectDir']          = $this->projectPath();

        $recursiveDefaultDirectory = explode("\\",$this->argument['project']);
        $this->argument['applicationName'] = pos($recursiveDefaultDirectory);
        $recursiveDefaultDirectory[] = 'V1';
        $recursiveDefaultDirectoryList = [];

        foreach (array_slice($recursiveDefaultDirectory,1) as $defaultDirectory){

            $recursiveDefaultDirectoryList[]=$defaultDirectory;
            $this->directory[$defaultDirectory.'Path'] = $this->projectPath().''.implode("/",$recursiveDefaultDirectoryList);
        }

        $this->directory['optionalDir'] = $this->optional();
        
        //get project directory all path
        $this->directory['kernelDir']               = $this->kernel();
        $this->directory['middleWareDir']           = $this->middleware();
        $this->directory['factoryDir']              = app()->path()->factory();
        $this->directory['nodeDir']                 = $this->node();
        $this->directory['webservice']              = $this->webservice();
        $this->directory['stubDir']                 = $this->stub();
        $this->directory['providerDir']             = $this->provider();
        $this->directory['storageDir']              = $this->storage();
        $this->directory['logDir']                  = $this->log();
        $this->directory['resourceDir']             = $this->resource();
        $this->directory['resourceCacheDir']        = $this->resource().'/'.StaticPathModel::$cache;
        $this->directory['languageDir']             = $this->language();
        $this->directory['languageEnDir']           = $this->language().'/en';
        $this->directory['sessionDir']              = $this->session();
        $this->directory['callDir']                 = $this->controller();
        $this->directory['configDir']               = $this->config();
        $this->directory['sourceDir']               = $this->sourceDir();
        $this->directory['sourceSupportDir']        = $this->sourceSupportDir();
        $this->directory['sourceSupportTraitDir']   = $this->sourceSupportDir().'/Traits';

        //set project directory
        $this->file->makeDirectory($this);

        //get project file all path
        //$this->touch['publish']                     = $this->project.'/publish.php';
        //$this->touch['main/version']                = $this->project.'/version.php';
        $this->touch['kernel/kernel']               = $this->kernel().'/Kernel.php';
        $this->touch['kernel/eloquent']             = $this->provider().'/EloquentServiceProvider.php';
        $this->touch['kernel/app']                  = $this->provider().'/AppServiceProvider.php';
        $this->touch['kernel/route']                = $this->provider().'/RouteServiceProvider.php';
        $this->touch['kernel/annotations']          = $this->kernel().'/AppAnnotations.php';
        $this->touch['middleware/authenticate']     = $this->middleware().'/Authenticate.php';
        $this->touch['middleware/ratelimit']        = $this->middleware().'/RateLimit.php';
        $this->touch['middleware/clientToken']      = $this->middleware().'/ClientApiToken.php';
        $this->touch['middleware/settimezone']      = $this->middleware().'/SetClientTimezone.php';
        $this->touch['middleware/trustedproxies']   = $this->middleware().'/TrustedProxies.php';
        $this->touch['factory/factory']             = $this->factory().'/Factory.php';
        $this->touch['factory/factorymanager']      = $this->factory().'/FactoryManager.php';
        $this->touch['node/index']                  = $this->node().'/index.html';
        $this->touch['webservice/index']            = $this->webservice().'/index.html';
        $this->touch['language/index']              = $this->language().'/index.html';
        $this->touch['language/exception']          = $this->language().'/en/exception.yaml';
        $this->touch['language/default']            = $this->language().'/en/default.yaml';
        $this->touch['log/index']                   = $this->log().'/index.html';
        $this->touch['resource/index']              = $this->resource().'/index.html';
        $this->touch['resource/index']              = $this->resource().'/'.StaticPathModel::$cache.'/index.html';
        $this->touch['stub/index']                  = $this->stub().'/index.html';
        $this->touch['session/index']               = $this->session().'/index.html';
        $this->touch['service/index']               = $this->controller().'/index.html';
        $this->touch['config/hateoas']              = $this->config().'/Hateoas.php';
        //$this->touch['config/response']             = $this->config().'/Response.php';
        $this->touch['config/redis']                = $this->config().'/Redis.php';
        $this->touch['config/app']                  = $this->config().'/App.php';
        $this->touch['config/autoservice']          = $this->config().'/AutoServices.php';
        $this->touch['config/aliasgroup']           = $this->config().'/AliasGroup.php';
        $this->touch['config/cors']                 = $this->config().'/Cors.php';
        $this->touch['config/database']             = $this->config().'/Database.php';
        $this->touch['config/authenticate']         = $this->config().'/Authenticate.php';
        $this->touch['config/slack']                = $this->config().'/Slack.php';
        $this->touch['version/annotations']         = $this->version().'/ServiceAnnotationsController.php';
        $this->touch['version/servicedispatcher']   = $this->version().'/ServiceEventDispatcherController.php';
        $this->touch['version/servicemiddleware']   = $this->version().'/ServiceMiddlewareController.php';
        $this->touch['version/base']                = $this->version().'/ServiceBaseController.php';
        $this->touch['version/base']                = $this->version().'/ServiceBaseController.php';
        $this->touch['version/log']                 = $this->version().'/ServiceLogController.php';
        $this->touch['source/apitokentrait']        = $this->sourceSupportDir().'/Traits/ClientApiTokenTrait.php';
        $this->touch['app/readme']                  = $this->projectPath().'/README.md';
        $this->touch['app/gitignore']               = $this->projectPath().'/.gitignore';
        $this->touch['app/composer']                = $this->projectPath().'/composer.json';
        $this->touch['test/index']                  = $this->storage().'/index.html';

        //set project touch
        $this->file->touch($this);

        echo $this->classical(' > Application called as "'.$this->projectName().'" has been successfully created in the '.$this->projectPath().'');
    }
}