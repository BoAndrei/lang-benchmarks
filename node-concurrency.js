const { Worker, isMainThread, parentPort, workerData } = require('worker_threads');

const arraySize = 5000000;
const numWorkers = 8;

if (isMainThread) {
    console.time('Execution Time');

    const array = Array.from({ length: arraySize }, (_, i) => i + 1);
    const chunkSize = Math.ceil(arraySize / numWorkers);
    let results = new Array(arraySize);
    let completedWorkers = 0;

    for (let i = 0; i < numWorkers; i++) {
        const start = i * chunkSize;
        const end = Math.min(start + chunkSize, arraySize);

        const worker = new Worker(__filename, {
            workerData: { start, end, array }
        });

        worker.on('message', (data) => {
            results.splice(start, data.length, ...data);
            completedWorkers++;

            if (completedWorkers === numWorkers) {
                console.log("Processing complete");
                console.timeEnd('Execution Time');
            }
        });
    }
} else {
    const { start, end, array } = workerData;
    const result = array.slice(start, end).map(process);

    parentPort.postMessage(result);
}

function process(val) {
    return val * 2;
}
