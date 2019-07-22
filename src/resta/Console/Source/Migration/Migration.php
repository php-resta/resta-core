<?php

namespace Resta\Console\Source\Migration;

use Migratio\SchemaFacade;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Store\Services\DatabaseConnection;
use Resta\Foundation\PathManager\StaticPathModel;

class Migration extends ConsoleOutputter {

    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='migration';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates a migration file',
        'push'  => 'Executes as sql the generated migration file'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var $commandRule
     */
    public $commandRule=['create'=>[
        'name','table'
    ],
        'push'=>[]];

    /**
     *
     */
    public function pull()
    {
        $schema = $this->getSchema();

        $schema->pull();
    }

    /**
     * @return mixed
     */
    public function push()
    {
        $schema = $this->getSchema();

        $pushResult = $schema->push();

        echo $this->info('Migration Push Process :');

        $list = [];

        foreach ($pushResult as $key=>$value) {

            if(isset($value['success'])){

                $list[] = true;

                $pushResultFile = explode("/",$pushResult[$key]['file']);
                $file = end($pushResultFile);

                if($pushResult[$key]['success']===true){

                    $this->table->addRow([
                        $key,
                        $pushResult[$key]['table'],
                        $file,
                        $pushResult[$key]['type'],
                        'Success',
                        'Ok',
                        'No',
                    ]);
                }
                else{

                    $this->table->addRow([
                        $key,
                        $pushResult[$key]['table'],
                        $file,
                        $pushResult[$key]['type'],
                        'Fail!',
                        $pushResult[$key]['message'],
                        'No',

                    ]);
                }
            }
        }

        if(count($list)){
            $this->table->setHeaders(['id','table','file','type','status','message','seeder']);

            echo $this->table->getTable();
        }
        else{
            echo $this->classical('No migration was found to apply');
        }


    }

    /**
     * @return mixed
     */
    public function create()
    {
        $config = $this->getConfig();

        if(!isset($this->argument['group'])){
            $path = $config['paths'][0];
        }
        else{
            $path = $config['paths'][strtolower($this->argument['group'])];
        }


        //set type for stub
        $tablePath = $path.'/'.$this->argument['table'];

        $stubType = (!file_exists($tablePath)) ? 'create' : 'alter';

        if(!file_exists($path)){

            $this->file->fs->mkdir($path,0777);
            $this->file->fs->chmod($path,0777,000,true);
        }

        $migrationCreate = $this->getSchema()->stub($this->argument,$stubType);

        echo $this->info('Migration Create Process :');

        $this->table->setHeaders(['id','method','table','style','name','type','status','message']);

        foreach ($migrationCreate['directory'] as $key=>$data){

            $this->table->addRow([
                $key,
                'migration:create',
                strtolower($data['table']),
                'directory',
                $data['directory'],
                $data['type'],
                ($data['success']) ? 'Ok' : 'Fail',
                $data['message'],

            ]);
        }

        foreach ($migrationCreate['file'] as $key=>$data){

            $this->table->addRow([
                $key,
                'migration:create',
                strtolower($data['table']),
                'file',
                $data['file'],
                $data['type'],
                ($data['success']) ? 'Ok' : 'Fail',
                $data['message'],

            ]);
        }

        echo $this->table->getTable();

    }

    /**
     * @return array
     */
    private function getConfig()
    {
        $paths = ['paths'=>[
            path()->migration().''.config('database.migrations.default')
        ],
            'database'=>DatabaseConnection::getConfig(),
            'arguments' => $this->argument
        ];

        $paths['paths'] = array_merge($paths['paths'],[StaticPathModel::storeMigrationPath()]);

        foreach (config('database.migrations') as $key=>$item) {
            if($key!=='default'){

                $otherMigrationPath = path()->migration().''.$item;
                $paths['paths'] = array_merge($paths['paths'],[$key=>$otherMigrationPath]);
            }
        }

        return $paths;
    }

    /**
     * @return \Migratio\Contract\SchemaFacadeContract
     */
    private function getSchema()
    {
        return SchemaFacade::setConfig($this->getConfig());
    }
}