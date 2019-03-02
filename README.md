# big-tests
Easily test your model and logic against your big data set

# install
```
git clone https://github.com/tsalufe/big-tests.git
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
