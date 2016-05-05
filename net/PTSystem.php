<?php

namespace ziguss\petrinet\net;

use ziguss\petrinet\Place;
use ziguss\petrinet\Transition;
use ziguss\petrinet\Arc;
use ziguss\petrinet\Capacity;
use ziguss\petrinet\Marking;
use ziguss\petrinet\Nameable;
use ziguss\petrinet\Weight;
use ziguss\petrinet\exception\TransitionNotEnabledException;

/**
 * place/transition system.
 *
 * @author ziguss <yudoujia@163.com>
 */
class PTSystem
{
    use Nameable;

    protected $directedNet;
    protected $capacity;
    protected $weight;
    protected $marking;
    protected $zeroInputTransitions;

    /**
     * PTSystem constructor.
     *
     * @param DirectedNet $directedNet
     * @param Capacity    $capacity
     * @param Weight      $weight
     * @param Marking     $marking
     */
    public function __construct(
        DirectedNet $directedNet,
        Capacity $capacity,
        Weight $weight,
        Marking $marking
    ) {
        $this->directedNet = $directedNet;
        $this->capacity = $capacity;
        $this->weight = $weight;
        $this->marking = $marking;
    }

    /**
     * @return Place[]
     */
    public function getPlaces()
    {
        return $this->directedNet->getPlaces();
    }

    /**
     * @return Transition[]
     */
    public function getTransitions()
    {
        return $this->directedNet->getTransitions();
    }

    /**
     * @return Arc[]
     */
    public function getArcs()
    {
        return $this->directedNet->getArcs();
    }

    /**
     * @param Place $place
     *
     * @return int
     */
    public function getTokens(Place $place)
    {
        return $this->marking->getTokens($place);
    }

    /**
     * @param Place $place
     *
     * @return int
     */
    public function getCapacity(Place $place)
    {
        return $this->capacity->getCapacity($place);
    }

    /**
     * @param Arc $arc
     *
     * @return int
     */
    public function getWeight(Arc $arc)
    {
        return $this->weight->getWeight($arc);
    }

    /**
     * Get all enabled transitions.
     *
     * @return Transition[]
     */
    public function getEnabledTransitions()
    {
        $transitions = $this->getZeroInputTransitions();

        foreach ($this->marking->getPlaces() as $place) {
            foreach ($place->getOutputArcs() as $arc) {
                if (array_search($arc->getTransition(), $transitions, true) === false) {
                    $transitions[] = $arc->getTransition();
                }
            }
        }

        return array_filter($transitions, [$this, 'isEnabled']);
    }

    /**
     * Check if a given transition is enabled.
     *
     * @param Transition $transition
     *
     * @return bool
     */
    public function isEnabled(Transition $transition)
    {
        $inputPlaces = [];
        $inputArcWeight = [];
        foreach ($transition->getInputArcs() as $arc) {
            if ($this->marking->getTokens($arc->getPlace()) < $this->weight->getWeight($arc)) {
                return false;
            } else {
                $inputPlaces[] = $arc->getPlace();
                $inputArcWeight[] = $this->weight->getWeight($arc);
            }
        }

        foreach ($transition->getOutputArcs() as $arc) {
            $key = array_search($arc->getPlace(), $inputPlaces, true);

            // also is input place
            if ($key !== false) {
                if ($this->capacity->getCapacity($arc->getPlace()) <
                    $this->marking->getTokens($arc->getPlace()) + $this->weight->getWeight($arc) - $inputArcWeight[$key]
                ) {
                    return false;
                }
            } else {
                if ($this->capacity->getCapacity($arc->getPlace()) <
                    $this->marking->getTokens($arc->getPlace()) + $this->weight->getWeight($arc)
                ) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Fire a transition.
     *
     * @param Transition $transition
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function fire(Transition $transition)
    {
        if (!$this->isEnabled($transition)) {
            throw new TransitionNotEnabledException('Cannot fire a not enabled transition.');
        }

        foreach ($transition->getInputArcs() as $arc) {
            $this->marking->setTokens(
                $arc->getPlace(),
                $this->marking->getTokens($arc->getPlace()) - $this->weight->getWeight($arc)
            );
        }

        foreach ($transition->getOutputArcs() as $arc) {
            $this->marking->setTokens(
                $arc->getPlace(),
                $this->marking->getTokens($arc->getPlace()) + $this->weight->getWeight($arc)
            );
        }

        return $this;
    }

    /**
     * @return Transition[]
     */
    protected function getZeroInputTransitions()
    {
        if (null === $this->zeroInputTransitions) {
            $this->zeroInputTransitions = [];
            foreach ($this->getTransitions() as $transition) {
                if (count($transition->getInputArcs()) === 0) {
                    $this->zeroInputTransitions[] = $transition;
                }
            }
        }

        return $this->zeroInputTransitions;
    }
}
