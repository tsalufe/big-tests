# big-tests
Easily test your model and logic against your big data set

# install
```
composer config repositories.big-tests vcs https://github.com/tsalufe/big-tests
composer require big-tests/big-tests
```

# example
To test if an simple example logic inside processDataAndGetResult returns an array(or json response) that contains the same email for all of the provided samples. It outputs every failed id during the runAll loop and dump all failed ids when it finishes.

This example requires illuminate/database >=5.2.
```
composer require illuminate/database
```
And "homestead" database with "users" table and mysql user "homestead" with password "secret"
```
mysql -e "grant all privileges on *.* to 'homestead'@'localhost' identified by 'secret'"
mysql -e "create database homestead"
mysql -e "create table homestead.users (id int, first_name varchar(256), last_name varchar(256), email varchar(256))"
mysql -e "insert into homestead.users values(1234, 'test first name', 'test last name', 'test-email@test-domain.com')"

```
Then run
```
mkdir examples/
cp vendor/big-tests/big-tests/examples/BillysMysqlBigTests.php examples/
php examples/BillysMysqlBigTests.php
```
The output is like,

```
array (
  'Total' => 1,
  'Success' => 1,
  'Error' => 0,
  'category1-count' => 0,
  'successes' => '["1234"]',
  'errors' => '[]',
  'category1-ids' => '[]',
)
```

# break down your statistics
Add the following to your child AbstractBigTests class,
```
protected $categories = [
    'scenario1'        => 'scenario1MethodName',
    'scenario1Success' => 'scenario1SuccessMethodName',
    'scenario1Fail'    => 'scenario1FailMethodName',
];

protected function scenario1MethodName($result, $bigData) : bool
{
    // your condition for scenario 1
}

protected function scenario1SuccessMethodName($result, $bigData) : bool
{
    return $this->scenario1MethodName($result, $bigData)
        && // your success check for scenario 1
}

protected function scenario1FailMethodName($result, $bigData) : bool
{
    return $this->scenario1MethodName($result, $bigData)
        && // your fail check for scenario 1
}
```
To get statistics of each category,
```
[
    'scenario1Total'        => $yourBigTestInstance->getCatgoryStatusCount('scenario1'),
    'scenario1SuccessTotal' => $yourBigTestInstance->getCategoryStatusCount('scenario1Success'),
    'scenario1FailTotal'    => $yourBigTestInstance->getCategoryStatusCount('scenario1Fail'),
]
```

To get detailed id's in each category,
```
[
    'scenario1Ids'        => $yourBigTestInstance->getCatgoryStatuses('scenario1'),
    'scenario1SuccessIds' => $yourBigTestInstance->getCategoryStatuses('scenario1Success'),
    'scenario1FailIds'    => $yourBigTestInstance->getCategoryStatuses('scenario1Fail'),
]
```
