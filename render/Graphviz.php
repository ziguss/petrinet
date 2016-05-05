<?php

namespace ziguss\petrinet\render;

use ziguss\petrinet\net\PTSystem;

/**
 * @author ziguss <yudoujia@163.com>
 * @date 16/4/24
 */
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
                    '('.$system->getTokens($place).' token)' :
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
            $elements[] = sprintf(
                '"%s" -> "%s" [label="%s"]',
                $this->getName($arc->getSource()),
                $this->getName($arc->getTarget()),
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
     *
     * @return string
     */
    protected function getName($nameable)
    {
        $name = method_exists($nameable, 'getName') ? $nameable->getName() : '';

        return $name ?: spl_object_hash($nameable);
    }
}
