<?php

namespace My;

class ServiceA extends ServiceAbstract
{

    /**
     * @var ResourceA
     */
    protected $res;

    public function __construct(ResourceA $res)
    {
        $this->res = $res;
    }

    public function update($id)
    {
        $ids = $this->getResource()->get();
        if (in_array($id, $ids)) {
            $this->getResource()->set();
            $this->log();
            return true;
        }
        return false;
    }

    /**
     * @return ResourceA
     */
    protected function getResource()
    {
        return $this->res;
    }

    protected function log()
    {
    }

}
