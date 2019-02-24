<?php
namespace BigTests;

abstract class AbstractBigTests implements BigTestsInterface
{
    protected $categories = [];

    protected $statuses = [];

    protected $categoryStatuses = [];

    protected $errors = [];

    public function runAll() : bool
    {
        $this->statuses = [];
        foreach($this->getBigDatas() as $bigData) {
            $this->statuses[$this->getIdentifier($bigData)] = $this->run($bigData);
        }
        $this->errors = array_filter($this->statuses, function($item) {
            return $item === false;
        });
        return empty($this->errors);
    }

    public function run($bigData) : bool
    {
        $result = $this->processDataAndGetResult($bigData);
        $status = $this->validate($result, $bigData);
        $this->categorize($result, $bigData);
        $this->output($bigData, $status);
        return $status;
    }

    public function categorize($result, $bigData) {
        foreach($this->categories as $categoryKey => $categoryCallback) {
            if($this->$categoryCallback($result, $bigData)) {
                $this->categoryStatuses[$categoryKey][] = $this->getIdentifier($bigData);
            }
        }
    }

    public function getCategoryErrors(string $category) : array
    {
        return $this->categoryStatuses[$category] ?? [];
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function getUncategorizedErrors() : array
    {
        if(empty($this->categories) || empty($this->categoryStatuses)) {
            return $this->getErrors();
        }
        $all = $this->categoryStatuses;
        array_unshift($all, array_keys($this->getErrors()));
        return call_user_func_array('array_diff', $all);
    }
}
