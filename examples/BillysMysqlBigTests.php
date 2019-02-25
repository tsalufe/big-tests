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
echo json_encode($bigTest->getSuccesses());
echo "\n";
echo json_encode($bigTest->getErrors());
echo "\n";
echo json_encode($bigTest->getCategoryErrors('category1'));
echo "\n";
echo json_encode($bigTest->getUncategorizedErrors());
echo "\n";
echo json_encode(['Total' => $bigTest->getTotalCount(), 'Success' => $bigTest->getTotalSuccessCount(), 'Error' => $bigTest->getTotalErrorCount(), 'error in category1' => $bigTest->getCategoryErrorCount('category1')]);
echo "\n";
