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

    public function __construct(Element $source, Element $target, $direct)
    {
        if ($direct === self::PLACE_TRANSITION) {
            $this->place = $source;
            $this->transition = $target;
        } elseif ($direct === self::TRANSITION_PLACE) {
            $this->transition = $source;
            $this->place = $target;
        } else {
            throw new \InvalidArgumentException('Invalid arc direct "'.$direct.'"');
        }
        $this->direct = $direct;
    }

    /**
     * @return Element
     */
    public function getSource()
    {
        return $this->direct === self::PLACE_TRANSITION ?
            $this->getPlace() :
            $this->getTransition();
    }

    /**
     * @return Element
     */
    public function getTarget()
    {
        return $this->direct === self::TRANSITION_PLACE ?
            $this->getPlace() :
            $this->getTransition();
    }

    /**
     * @return Place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @return Transition
     */
    public function getTransition()
    {
        return $this->transition;
    }

    /**
     * @return int
     */
    public function getDirect()
    {
        return $this->direct;
    }
}
