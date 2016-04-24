<?php

namespace ziguss\petrinet;

use ziguss\petrinet\net\DirectedNet;
use ziguss\petrinet\net\PTSystem;

/**
 * Help building place/transition system
 *
 * @author yudoudou <yudoudou@baidu.com>
 */
class PTSystemBuilder
{
    protected $places;
    protected $transitions;
    protected $arcs;
    protected $capacity;
    protected $weight;
    protected $marking;
    
    public function __construct()
    {
        $this->places = [];
        $this->transitions = [];
        $this->arcs = [];
        $this->capacity = new Capacity();
        $this->weight = new Weight();
        $this->marking = new Marking();
    }

    /**
     * @param int $tokens
     * @param int $capacity
     * @return Place
     */
    public function place($tokens = 0, $capacity = PHP_INT_MAX)
    {
        if ($capacity < $tokens) {
            throw new \InvalidArgumentException('The place is not enough capacity.');
        }
        
        $place = new Place();
        if ($tokens != 0) {
            $this->marking->setTokens($place, $tokens);
        }
        if ($capacity != PHP_INT_MAX) {
            $this->capacity->setCapacity($place, $capacity);
        }
        
        return $this->places[] = $place;
    }

    /**
     * @return Transition
     */
    public function transition()
    {
        return $this->transitions[] = new Transition();
    }

    /**
     * @param Element $source
     * @param Element $target
     * @param int $weight
     * @return $this
     */
    public function connect(Element $source, Element $target, $weight = 1)
    {
        if ($source instanceof Place && $target instanceof Transition) {
            $arc = (new Arc())
                ->setPlace($source)
                ->setTransition($target)
                ->setDirect(Arc::PLACE_TRANSITION);
        } elseif ($source instanceof Transition && $target instanceof Place) {
            $arc = (new Arc())
                ->setTransition($source)
                ->setPlace($target)
                ->setDirect(Arc::TRANSITION_PLACE);
        } else {
            throw new \InvalidArgumentException('An arc must connect a place to a transition or vice-versa.');
        }
        
        $source->addOutputArc($arc);
        $target->addInputArc($arc);
        
        if ($weight !== 1) {
            $this->weight->setWeight($arc, $weight);
        }
        
        return $this->arcs[] = $arc;
    }

    /**
     * @return PTSystem
     */
    public function getPTSystem()
    {
        return new PTSystem(
            new DirectedNet($this->places, $this->transitions, $this->arcs),
            $this->capacity,
            $this->weight,
            $this->marking
        );
    }
}
