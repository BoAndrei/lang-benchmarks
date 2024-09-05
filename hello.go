package main

import (
	"fmt"
	"sync"
	"time"
)

const (
	arraySize  = 10000000
	numWorkers = 8
)

func main() {
	startTime := time.Now()

	array := make([]int, arraySize)
	for i := 0; i < arraySize; i++ {
		array[i] = i + 1
	}

	var wg sync.WaitGroup
	chunkSize := arraySize / numWorkers
	results := make([]int, arraySize)

	for i := 0; i < numWorkers; i++ {
		wg.Add(1)

		start := i * chunkSize
		end := start + chunkSize
		if i == numWorkers-1 {
			end = arraySize
		}

		go func(start, end int) {
			defer wg.Done()
			for j := start; j < end; j++ {
				results[j] = process(array[j])
			}
		}(start, end)
	}

	wg.Wait()

	fmt.Println("Processing complete")

	elapsedTime := time.Since(startTime)
	fmt.Printf("Script execution time: %.2f seconds\n", elapsedTime.Seconds())
}

func process(val int) int {
	return val * 2
}
