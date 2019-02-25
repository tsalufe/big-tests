<?php
namespace BigTests;

abstract class AbstractBigTests implements BigTestsInterface
{
    use CanSaveStatuses;

    public function runAll() : bool
    {
        foreach($this->getBigDatas() as $bigData) {
            $this->run($bigData);
        }
        return $this->isAllSuccessful();
    }

    public function run($bigData) : bool
    {
        $result = $this->processDataAndGetResult($bigData);
        if($this->skip($result)) {
            return true;
        }
        $status = $this->validate($result, $bigData);
        $this->saveStatus($status, $result, $bigData);
        $this->output($bigData, $status);
        return $status;
    }

    public function skip($result) : bool
    {
        return false;
    }
}
