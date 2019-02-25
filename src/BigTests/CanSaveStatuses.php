<?php
namespace BigTests;

trait CanSaveStatuses
{
    protected $statuses = [];

    protected $categories = [];

    protected $categoryStatuses = [];

    protected $errors = [];

    protected $successes = [];

    public function isAllSuccessful() : bool
    {
        return empty($this->errors);
    }

    public function saveStatus(bool $status, $result, $bigData) : void
    {
        $this->statuses[$this->getIdentifier($bigData)] = $status;
        if($status) {
            $this->successes[] = $this->getIdentifier($bigData);
        } else {
            $this->errors[] = $this->getIdentifier($bigData);
        }
        $this->categorize($result, $bigData);
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

    public function getCategoryErrorCount(string $category) : int
    {
        return count($this->getCategoryErrors($category));
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function getTotalErrorCount() : int
    {
        return count($this->errors);
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

    public function getUncategorizedErrorCount() : int
    {
        return count($this->getUncategorizedErrors());
    }

    public function getSuccesses() : array
    {
        return $this->successes;
    }

    public function getTotalSuccessCount() : int
    {
        return count($this->successes);
    }
}
