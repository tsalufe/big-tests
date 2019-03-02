<?php
namespace BigTests;

trait CanSaveStatuses
{
    protected $statuses = [];

    protected $categories = [];

    protected $categoryStatuses = [];

    protected $errors = [];

    protected $successes = [];

    public function getStatuses() : array
    {
        return $this->statuses;
    }

    public function getTotalCount() : int
    {
        return count($this->statuses);
    }

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

    public function getCategoryStatuses(string $category = null) : array
    {
        if(is_null($category)) {
            return $this->categoryStatuses;
        }
        return $this->categoryStatuses[$category] ?? [];
    }

    public function getCategoryStatusCount(string $category) : int
    {
        return count($this->getCategoryStatuses($category));
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
        $all = array_values($this->categoryStatuses);
        array_unshift($all, $this->getErrors());
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
