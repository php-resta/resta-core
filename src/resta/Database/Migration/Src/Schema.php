<?php

namespace Migratio;

use Migratio\Resource\SqlDefinitor;
use Migratio\Contract\SchemaContract;
use Migratio\Resource\PullManager\Pulling;
use Migratio\Resource\PushManager\Pushing;
use Migratio\Resource\StubManager\Stubber;

class Schema extends SchemaHelper implements SchemaContract
{
    /**
     * @var array $params
     */
    public $params = array();

    /**
     * @var $config
     */
    protected $config;

    /**
     * @var $connection
     */
    protected $connection;

    /**
     * @var $driver
     */
    protected $driver;

    /**
     * @var $grammarPath
     */
    protected $grammarPath = 'Migratio\GrammarStructure';

    /**
     * Schema constructor.
     * @param null $config
     */
    public function __construct($config=null)
    {
        $this->config           = $config;
        $this->driver           = $this->config['database']['driver'];
        $this->grammarPath      = $this->grammarPath.'\\'.ucfirst($this->driver);
        $this->connection       = (new SqlDefinitor($this->config))->getConnection();
    }

    /**
     * @return Pulling|mixed
     */
    public function pull()
    {
        $pulling = new Pulling($this);

        return $pulling->get();
    }

    /**
     * @return Pushing|mixed
     */
    public function push()
    {
        $pushing = new Pushing($this);

        return $pushing->handle();
    }

    /**
     * @return mixed|void
     */
    public function stub(...$params)
    {
        $stubber = new Stubber($this);

        return $stubber->get($params[0]);
    }
}