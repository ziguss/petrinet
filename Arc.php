<?php

namespace ziguss\petrinet;

/**
 * @author ziguss <yudoujia@163.com>
 */
class Arc
{
    const PLACE_TRANSITION = 0;
    const TRANSITION_PLACE = 1;
    
    protected $place;
    protected $transition;
    protected $direct;

    /**
     * @return Place
     */
    public function getPlace()
    {
        return $this->place;
    }
    
    /**
     * @param Place $place
     * @return $this
     */
    public function setPlace(Place $place)
    {
        $this->place = $place;
        return $this;
    }

    /**
     * @return Transition
     */
    public function getTransition()
    {
        return $this->transition;
    }

    /**
     * @param Transition $transition
     * @return $this
     */
    public function setTransition(Transition $transition)
    {
        $this->transition = $transition;
        return $this;
    }

    /**
     * @return int
     */
    public function getDirect()
    {
        return $this->direct;
    }
    
    /**
     * @param int $direct
     * @return $this
     */
    public function setDirect($direct)
    {
        $this->direct = $direct;
        return $this;
    }
}
