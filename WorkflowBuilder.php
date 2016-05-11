<?php

namespace ziguss\petrinet;

/**
 * @author ziguss <yudoujia@163.com>
 */
class WorkflowBuilder
{
    protected $builder;
    protected $transitions;

    public function __construct()
    {
        $this->builder = new PTSystemBuilder();
        $this->transitions = [];
    }

    public function start($t)
    {
        $this->builder->connect(
            $this->builder->place(1),
            $this->getTransition($t)
        );

        return $this;
    }

    public function sequence($t1, $t2)
    {
        $t1 = $this->getTransition($t1);
        $t2 = $this->getTransition($t2);

        $p = $this->builder->place();
        $this->builder->connect($t1, $p);
        $this->builder->connect($p, $t2);

        return $this;
    }

    public function andSplit($t1, $tList)
    {
        foreach ($tList as $t) {
            $this->sequence($t1, $t);
        }

        return $this;
    }

    public function andJoin($tList, $t2)
    {
        foreach ($tList as $t) {
            $this->sequence($t, $t2);
        }

        return $this;
    }

    public function orSplit($t1, $tList)
    {
        $t1 = $this->getTransition($t1);

        $p = $this->builder->place();
        $this->builder->connect($t1, $p);
        foreach ($tList as $t) {
            $this->builder->connect($p, $this->getTransition($t));
        }

        return $this;
    }

    public function get()
    {
        return $this->builder->getPTSystem();
    }

    protected function getTransition($t)
    {
        if (empty($this->transitions[$t])) {
            $this->transitions[$t] = $this->builder->transition()->setName($t);
        }

        return $this->transitions[$t];
    }
}
