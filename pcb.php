<?php

use ziguss\petrinet\WorkflowBuilder;

require __DIR__ . '/vendor/autoload.php';

$builder = new WorkflowBuilder();

$builder->start('CopyTask')
    ->andSplit('CopyTask', ['JoinFullTask', 'JoinPointTask'])
    ->andJoin(['JoinFullTask', 'JoinPointTask'], 'VerifyTask')
    ->orSplit('VerifyTask', ['MergeTask', 'PreProcessingTask'])
    ->sequence('MergeTask', 'PreProcessingTask');

$render = new \ziguss\petrinet\render\Graphviz();
echo $render->render($builder->get());

__halt_compiler();
start CopyTask

CopyTask and-split [JoinFullTask, JoinPointTask]

[JoinFullTask, JoinPointTask] and-join VerifyTask

VerifyTask or-split [MergeTask, PreProcessingTask]

MergeTask -> PreProcessingTask

PreProcessingTask or-split [AccessTask, RepairTask]

RepairTask -> PreProcessingTask
