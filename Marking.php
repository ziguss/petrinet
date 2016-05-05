<?php

namespace ziguss\petrinet;

/**
 * @author ziguss <yudoujia@163.com>
 */
class Marking
{
    protected $places;

    public function __construct()
    {
        $this->places = new \SplObjectStorage();
    }

    /**
     * @return Place[]
     */
    public function getPlaces()
    {
        $places = [];
        foreach ($this->places as $place) {
            $places[] = $place;
        }

        return $places;
    }

    /**
     * @param Place $place
     *
     * @return int
     */
    public function getTokens(Place $place)
    {
        if ($this->places->contains($place)) {
            return $this->places[$place];
        } else {
            return 0;
        }
    }

    /**
     * @param Place $place
     * @param int   $tokens
     *
     * @return $this
     */
    public function setTokens(Place $place, $tokens)
    {
        $tokens = (int) $tokens;
        if ($tokens < 0) {
            throw new \InvalidArgumentException('The tokens must be a non-negative integer.');
        }

        if ($tokens === 0) {
            $this->places->detach($place);
        } else {
            $this->places[$place] = $tokens;
        }

        return $this;
    }
}
