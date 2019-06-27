<?php

namespace Migratio\GrammarStructure\Mysql\Wizard;

class EngineProperties
{
    /**
     * @var $wizard WizardContract
     */
    protected $wizard;

    /**
     * Types constructor.
     * @param $wizard WizardContract
     * @param $wizard
     */
    public function __construct($wizard)
    {
        $this->wizard=$wizard;
    }

    /**
     * @return void
     */
    public function InnoDB()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function MyISAM()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function ndbcluster()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function MEMORY()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function EXAMPLE()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function FEDERATED()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function ARCHIVE()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function CSV()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function BLACKHOLE()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function infinidb()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function IBMDB2I()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function Brighthouse()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function KFDB()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function ScaleDB()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function TokuDB()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function XtraDB()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function Spider()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function MRG_MyISAM()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function Aria()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }

    /**
     * @return void
     */
    public function PBXT()
    {
        $this->wizard->setEngine(__FUNCTION__);
    }
}

