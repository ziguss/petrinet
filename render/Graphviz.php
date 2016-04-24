<?php

/**
 * @author ziguss <yudoujia@163.com>
 * @date 16/4/24
 */

namespace ziguss\petrinet\render;

use ziguss\petrinet\Arc;
use ziguss\petrinet\net\PTSystem;

class Graphviz
{
    public function render(PTSystem $system)
    {
        $elements = [];
        foreach ($system->getPlaces() as $place) {
            $elements[] = sprintf(
                '"%s" [label="%s"]',
                $this->getName($place),
                $system->getTokens($place) ?
                    '(' . $system->getTokens($place) . ' token)' :
                    ''
            );
        }

        foreach ($system->getTransitions() as $transition) {
            $elements[] = sprintf(
                '"%s" [label="%s" shape=box]',
                $this->getName($transition),
                $transition->getName() ?: ''
            );
        }
        
        foreach ($system->getArcs() as $arc) {
            $source = $arc->getDirect() == Arc::PLACE_TRANSITION ? $arc->getPlace() : $arc->getTransition();
            $target = $arc->getDirect() == Arc::TRANSITION_PLACE ? $arc->getPlace() : $arc->getTransition();
            $elements[] = sprintf(
                '"%s" -> "%s" [label="%s"]',
                $this->getName($source),
                $this->getName($target),
                $system->getWeight($arc) != 1 ? $system->getWeight($arc) : ''
            );
        }
        
        return sprintf(
            "digraph \"%s\" {\n%s\n}",
            $this->getName($system),
            implode("\n", $elements)
        );
    }

    /**
     * @param $nameable
     * @return string
     */
    protected function getName($nameable)
    {
        $name = method_exists($nameable, 'getName') ? $nameable->getName() : '';
        return $name ?: spl_object_hash($nameable);
    }
}
