<?php

namespace Mvkasatkin\mocker;

use Psr\Container\ContainerInterface;

class MockerContainer implements ContainerInterface
{
    /**
     * @var array
     */
    private $configItems;
    /**
     * @var null
     */
    private $args;

    /**
     * @param array $configItems
     * @param null $args
     */
    public function __construct(array $configItems = [], $args = null)
    {
        $this->configItems = $configItems;
        $this->args = $args;
    }

    /**
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed Mock Entry.
     */
    public function get($id)
    {
        return Mocker::create($id, $this->configItems, $this->args);
    }

    /**
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return (bool)$id;
    }
}
