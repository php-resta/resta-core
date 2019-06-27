<?php

namespace Migratio;

use Migratio\Contract\SchemaCapsuleContract;

class SchemaCapsule implements SchemaCapsuleContract
{
    /**
     * @var $config
     */
    protected $config;

    /**
     * @var $file
     */
    protected $file;

    /**
     * @var $table
     */
    protected $table;

    /**
     * @var $wizardNamespace
     */
    protected $wizardNamespace;

    /**
     * @var $wizardAlterNamespace
     */
    protected $wizardAlterNamespace;

    /**
     * SchemaCapsule constructor.
     * @param $config
     * @param $file
     * @param $table
     */
    public function __construct($config,$file,$table)
    {
        $this->config                       = $config;
        $this->file                         = $file;
        $this->table                        = $table;
        $this->wizardNamespace              = $this->getWizardNamespace().'\Wizard';
        $this->wizardAlterGroupNamespace    = $this->getWizardNamespace().'\WizardAlterGroup';
    }

    /**
     * @param callable $callback
     * @return mixed|string
     */
    public function alter(callable $callback)
    {
        return $this->callbackWizardInstance(__FUNCTION__,$callback);
    }

    /**
     * @param callable $callback
     * @return mixed|string
     */
    public function create(callable $callback)
    {
        return $this->callbackWizardInstance(__FUNCTION__,$callback);
    }

    /**
     * @param $wizard
     */
    private function checkErrors($wizard)
    {
        if(count($wizard->getNames())!==count($wizard->getTypes())){
            $wizard->setError('name and types are not equal');
        }
    }

    /**
     * @return string
     */
    private function getWizardNamespace()
    {
        return 'Migratio\GrammarStructure\\'.ucfirst($this->config['database']['driver']).'\Wizard';
    }

    /**
     * @param $type
     * @return string
     */
    private function getWizardInstance($type)
    {
        $wizard = ($type=="create") ? $this->wizardNamespace : $this->wizardAlterGroupNamespace;

        $wizard = new $wizard;

        $wizard->schemaType($type);

        $wizard->setTable($this->table);

        $wizard->setFile($this->file);

        return $wizard;
    }

    /**
     * @param $method
     * @return string
     */
    private function callbackWizardInstance($method,callable $callback)
    {
        $wizardInstance = $this->getWizardInstance($method);

        $callbackReturn = call_user_func_array($callback,[$wizardInstance]);

        $this->checkErrors($wizardInstance);

        return $wizardInstance;
    }
}

