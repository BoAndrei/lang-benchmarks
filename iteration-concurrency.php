<?php
ini_set('memory_limit','2048M');
$arraySize  = 10000000;
$numWorkers = 8;

$startTime = microtime(true);

$array = range(1, $arraySize);

$chunkSize = intval($arraySize / $numWorkers);
$results = array_fill(0, $arraySize, 0);

$workers = [];

for ($i = 0; $i < $numWorkers; $i++) {
    $start = $i * $chunkSize;
    $end = $start + $chunkSize;
    if ($i === $numWorkers - 1) {
        $end = $arraySize;
    }

    $pid = pcntl_fork();
    if ($pid === -1) {
        die('Could not fork');
    } elseif ($pid) {
        $workers[] = $pid;
    } else {
        for ($j = $start; $j < $end; $j++) {
            $results[$j] = process($array[$j]);
        }
        exit(0);
    }
}

foreach ($workers as $worker) {
    pcntl_waitpid($worker, $status);
}

echo "Processing complete\n";
$elapsedTime = microtime(true) - $startTime;
echo sprintf("Script execution time: %.2f seconds\n", $elapsedTime);

function process($val) {
    return $val * 2;
}
