<?php
ini_set('memory_limit','2048M');
$arraySize  = 10000000;

$startTime = microtime(true);

$array = range(1, $arraySize);

$results = array_map('process', $array);

echo "Processing complete\n";

$elapsedTime = microtime(true) - $startTime;
echo sprintf("Script execution time: %.2f seconds\n", $elapsedTime);

function process($val) {
    return $val * 2;
}
