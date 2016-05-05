<?php

namespace ziguss\petrinet\net;

use ziguss\petrinet\Arc;
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
     * @param Place[]      $places
     * @param Transition[] $transitions
     * @param Arc[]        $arcs
     */
    public function __construct(array $places, array $transitions, array $arcs)
    {
        $count = count($places) + count($transitions);

        if ($count === 0) {
            throw new \InvalidArgumentException('A net must not be empty.');
        } elseif ($count !== 1) {
            $elements = new \SplObjectStorage();
            foreach ($arcs as $arc) {
                $elements->attach($arc->getSource());
                $elements->attach($arc->getTarget());
            }

            foreach (array_merge($places, $transitions) as $element) {
                if ($elements->contains($element)) {
                    $elements->detach($element);
                } else {
                    throw new \InvalidArgumentException('A place or a transition must not be isolated.');
                }
            }

            if ($elements->count() !== 0) {
                throw new \InvalidArgumentException('Invalid flow relation');
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
