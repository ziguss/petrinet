<?php

namespace ziguss\petrinet;

/**
 * Weight Function
 * 
 * @author yudoudou <yudoudou@baidu.com>
 */
class Weight
{
    protected $arcs;

    public function __construct()
    {
        $this->arcs = new \SplObjectStorage();
    }

    /**
     * @param Arc $arc
     * @return int
     */
    public function getWeight(Arc $arc)
    {
        if ($this->arcs->contains($arc)) {
            return $this->arcs[$arc];
        } else {
            return 1;
        }
    }

    /**
     * @param Arc $arc
     * @param int $weight
     * @return $this
     */
    public function setWeight(Arc $arc, $weight)
    {
        $weight = (int) $weight;
        if ($weight < 1) {
            throw new \InvalidArgumentException('The weight must be a positive integer.');
        }
        
        if ($weight === 1) {
            $this->arcs->detach($arc);
        } else {
            $this->arcs[$arc] = $weight;
        }
        
        $this->arcs[$arc] = $weight;
        return $this;
    }
}
