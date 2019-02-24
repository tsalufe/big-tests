<?php
namespace BigTests;

abstract class AbstractBigTests implements BigTestsInterface
{
    public function runAll() : bool
    {
        $statuses = [];
        foreach($this->getBigDatas() as $bigData) {
            $statuses[$this->getIdentifier($bigData)] = $this->run($bigData);
        }
        $this->finish($statuses);
        return array_search(false, $statuses) !== false;
    }

    public function run($bigData) : bool
    {
        $status = $this->validate($this->processDataAndGetResult($bigData), $bigData);
        $this->output($bigData, $status);
        return $status;
    }

    public function finish(array $statuses) : void
    {
        var_dump(array_filter($statuses, function ($item) {
            return $item === false;
        }));
    }
}
