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

    function output($bigData, $status): void;
}
