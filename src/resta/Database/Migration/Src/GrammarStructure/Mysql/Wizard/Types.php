<?php

namespace Migratio\GrammarStructure\Mysql\Wizard;

use Migratio\Contract\WizardContract;

class Types
{
    /**
     * @var $wizard WizardContract
     */
    protected $wizard;

    /**
     * Types constructor.
     * @param $wizard
     */
    public function __construct($wizard)
    {
       $this->wizard=$wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function bigint($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function binary($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function bit($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @return WizardContract
     */
    public function blob()
    {
        $this->wizard->setTypes(__FUNCTION__,null);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function char($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @return WizardContract
     */
    public function date()
    {
        $this->wizard->setTypes(__FUNCTION__,null);

        return $this->wizard;
    }

    /**
     * @return WizardContract
     */
    public function dateTime()
    {
        $this->wizard->setTypes(__FUNCTION__,null);

        return $this->wizard;
    }

    /**
     * @return WizardContract
     */
    public function decimal()
    {
        $this->wizard->setTypes(__FUNCTION__,null);

        return $this->wizard;
    }

    /**
     * @return WizardContract
     */
    public function double()
    {
        $this->wizard->setTypes(__FUNCTION__,null);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function enum($value=array())
    {
        $this->wizard->setTypes(__FUNCTION__,$value,'enum');

        return $this->wizard;
    }

    /**
     * @return WizardContract
     */
    public function float()
    {
        $this->wizard->setTypes(__FUNCTION__,null);

        return $this->wizard;
    }

    /**
     * @param int $value
     * @return WizardContract
     */
    public function int($value=11)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @return WizardContract
     */
    public function longtext()
    {
        $this->wizard->setTypes(__FUNCTION__,null);

        return $this->wizard;
    }

    /**
     * @return WizardContract
     */
    public function mediumtext()
    {
        $this->wizard->setTypes(__FUNCTION__,null);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function json()
    {
        $this->wizard->setTypes(__FUNCTION__,null);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function set($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function text($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function time($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function mediumint($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function timestamp($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @return WizardContract
     */
    public function real()
    {
        $this->wizard->setTypes(__FUNCTION__,null);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function smallint($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function tinyint($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function tinytext($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function varchar($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }

    /**
     * @param $value
     * @return WizardContract
     */
    public function year($value)
    {
        $this->wizard->setTypes(__FUNCTION__,$value);

        return $this->wizard;
    }
}

