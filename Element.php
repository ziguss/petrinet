<?php

namespace ziguss\petrinet;

/**
 * @author ziguss <yudoujia@163.com>
 */
abstract class Element
{
    use Nameable;
    
    protected $inputArcs = [];
    protected $outputArcs = [];

    /**
     * @return Arc[]
     */
    public function getInputArcs()
    {
        return $this->inputArcs;
    }
    
    /**
     * @param Arc $inputArc
     * @return $this
     */
    public function addInputArc(Arc $inputArc)
    {
        $this->inputArcs[] = $inputArc;
        return $this;
    }
    
    /**
     * @return Arc[]
     */
    public function getOutputArcs()
    {
        return $this->outputArcs;
    }
    
    /**
     * @param Arc $outputArc
     * @return $this
     */
    public function addOutputArc(Arc $outputArc)
    {
        $this->outputArcs[] = $outputArc;
        return $this;
    }
}
