# big-tests
Easily test your model and logic against your big data set

# install
```
git clone https://github.com/tsalufe/big-tests.git
```

# example
To test if an simple example logic inside processDataAndGetResult returns an array(or json response) that contains the same email for all of the provided samples. It outputs every failed id during the runAll loop and dump all failed ids when it finishes.
This example requires illuminate/database >=5.2
```
composer install illuminate/database
```

```
<?php

require(realpath(__DIR__ . '/../vendor/autoload.php'));

use BigTests\AbstractMysqlBigTests;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\MySqlConnection;

class BillysMysqlBigTests extends AbstractMysqlBigTests
{
    protected $categories = [
        'category1' => 'category1Callback',
    ];

    public function processDataAndGetResult($bigData)
    {
        // call your test logic here and return the result from your test logic
        return [
            'email' => $bigData->email,
        ];
    }

    public function validate($result, $bigData) : bool
    {
        return $result['email'] == $bigData->email;
    }

    public function output($bigData, $status) : void
    {
        if(!$status) {
            echo "$bigData->id failed\n";
        }
    }

    protected function category1Callback($result, $bigData) : bool
    {
        $statusAfterCheckingSomeConditions = strlen($bigData->first_name) + strlen($bigData->last_name) > 20;
        return $statusAfterCheckingSomeConditions && !$this->validate($result, $bigData);
    }
}

$mysqlConnection = new MySqlConnection(new \Pdo('mysql:dbname=homestead;host=127.0.0.1', 'homestead', 'secret'), 'homestead');

$queryBuilder = (new Builder($mysqlConnection))->from('users');

$bigTest = (new BillysMysqlBigTests($queryBuilder));
$bigTest->runAll();
var_export([
    'Total'           => $bigTest->getTotalCount(),
    'Success'         => $bigTest->getTotalSuccessCount(),
    'Error'           => $bigTest->getTotalErrorCount(),
    'category1-count' => $bigTest->getCategoryErrorCount('category1'),
    'successes'       => json_encode($bigTest->getSuccesses()),
    'errors'          => json_encode($bigTest->getErrors()),
    'category1-ids'   => json_encode($bigTest->getCategoryErrors('category1')),
]);
```
