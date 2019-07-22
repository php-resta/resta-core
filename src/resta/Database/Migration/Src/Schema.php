<?php

namespace Migratio;

use Migratio\Resource\SqlDefinitor;
use Migratio\Contract\SchemaContract;
use Migratio\Resource\PullManager\Pulling;
use Migratio\Resource\PushManager\Pushing;
use Migratio\Resource\StubManager\Stubber;
use Resta\Exception\FileNotFoundException;

class Schema extends SchemaHelper implements SchemaContract
{
    /**
     * @var array $params
     */
    public $params = array();

    /**
     * @var string
     */
    protected $config;

    /**
     * @var null|object
     */
    protected $connection;

    /**
     * @var string
     */
    protected $driver;

    /**
     * @var string
     */
    protected $grammarPath = 'Migratio\GrammarStructure';

    /**
     * @var null|array
     */
    protected $arguments;

    /**
     * Schema constructor.
     * @param null|mixed $config
     */
    public function __construct($config=null)
    {
        $this->config           = $config;
        $this->driver           = $this->config['database']['driver'];
        $this->grammarPath      = $this->grammarPath.'\\'.ucfirst($this->driver);
        $this->connection       = (new SqlDefinitor($this->config))->getConnection();
        $this->arguments        = $this->config['arguments'];
    }

    /**
     * migration pull
     *
     * @return Pulling|mixed
     *
     * @throws FileNotFoundException
     */
    public function pull()
    {
        $pulling = new Pulling($this);

        return $pulling->get();
    }

    /**
     * migration push
     *
     * @return Pushing|mixed
     */
    public function push()
    {
        $pushing = new Pushing($this);

        return $pushing->handle();
    }

    /**
     * migration stub
     *
     * @return mixed|void
     */
    public function stub(...$params)
    {
        $stubber = new Stubber($this);

        return $stubber->get(current($params));
    }
}