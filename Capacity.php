<?php

namespace ziguss\petrinet;

/**
 * Capacity Function.
 *
 * @author ziguss <yudoujia@163.com>
 */
class Capacity
{
    protected $places;

    public function __construct()
    {
        $this->places = new \SplObjectStorage();
    }

    /**
     * @param Place $place
     *
     * @return int
     */
    public function getCapacity(Place $place)
    {
        if ($this->places->contains($place)) {
            return $this->places[$place];
        } else {
            return PHP_INT_MAX;
        }
    }

    /**
     * @param Place $place
     * @param int   $capacity
     *
     * @return $this
     */
    public function setCapacity(Place $place, $capacity)
    {
        $capacity = (int) $capacity;
        if ($capacity <= 0) {
            throw new \InvalidArgumentException('The capacity must be a positive integer.');
        }

        if ($capacity !== PHP_INT_MAX) {
            $this->places[$place] = $capacity;
        }

        return $this;
    }
}
