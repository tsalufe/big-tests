# big-tests
Easily test your model and logic against your big data set

#example

```
require(realpath(__DIR__ . '/../vendor/autoload.php'));
use BigTests\AbstractMysqlBigTests;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\MySqlConnection;
class BillysMysqlBigTests extends AbstractMysqlBigTests
{
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
}
$mysqlConnection = new MySqlConnection(new \Pdo('mysql:dbname=homestead;host=127.0.0.1', 'homestead', 'secret'), 'homestead');
$queryBuilder = (new Builder($mysqlConnection))->from('users');
(new BillysMysqlBigTests($queryBuilder))->runAll();
```