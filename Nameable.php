<?php

namespace ziguss\petrinet;

/**
 * @author ziguss <yudoujia@163.com>
 */
trait Nameable
{
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
