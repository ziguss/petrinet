<?php

namespace ziguss\petrinet\net;

use ziguss\petrinet\Arc;
use ziguss\petrinet\Element;
use ziguss\petrinet\Place;
use ziguss\petrinet\Transition;

/**
 * @author ziguss <yudoujia@163.com>
 */
class DirectedNet
{
    protected $places;
    protected $transitions;
    protected $marking;
    protected $arcs;

    /**
     * DirectedNet constructor.
     *
     * @param Place[] $places
     * @param Transition[] $transitions
     * @param Arc[] $arcs
     */
    public function __construct(array $places, array $transitions, array $arcs)
    {
        if (empty($places) && empty($transitions)) {
            throw new \InvalidArgumentException('A directed net must not be empty.');
        }

        /**
         * @var Element $element
         */
        foreach ([$places, $transitions] as $elements) {
            foreach ($elements as $element) {
                if (empty($element->getInputArcs()) && empty($element->getOutputArcs())) {
                    throw new \InvalidArgumentException('An place/transition must not be isolated.');
                }
            }
        }

        $this->places = $places;
        $this->transitions = $transitions;
        $this->arcs = $arcs;
    }

    /**
     * @return Place[]
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * @return Transition[]
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    /**
     * @return Arc[]
     */
    public function getArcs()
    {
        return $this->arcs;
    }
}
