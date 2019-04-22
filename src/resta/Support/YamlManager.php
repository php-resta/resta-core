<?php

namespace Resta\Support;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class YamlManager
{
    /**
     * @var $path
     */
    protected $path;

    /**
     * @var $yaml
     */
    protected $yaml;

    /**
     * YamlManager constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->yaml = $this->getYamlParse();
    }

    /**
     * @return mixed
     */
    public function get()
    {
        //get yaml as parsed
        return $this->yaml;
    }

    public function set($data=array())
    {
        //we merge the data in yaml with new data.
        $newYamlData = array_merge($this->yaml,$data);

        //we dump new data.
        $yaml = Yaml::dump($newYamlData);

        //we export the dumped data to the existing file.
        return file_put_contents($this->path, $yaml);
    }

    /**
     * @return mixed
     */
    private function getYamlParse()
    {
        //get yaml as parsed
        try {
            return Yaml::parse(file_get_contents($this->path));
        } catch (ParseException $exception) {
            return [];
        }
    }

}