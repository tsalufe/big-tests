<?php
namespace BigTests;

interface BigTestsInterface
{
    function runAll(): bool;

    function getBigDatas() : \Generator;

    function run($bigData) : bool;

    function getIdentifier($bigData) : string;

    function processDataAndGetResult($data);

    function validate($result, $expected) : bool;

    function saveStatus(bool $status, $result, $bigData) : void; 

    function output($bigData, $status): void;
}
