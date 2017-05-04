<?php

function main($argv)
{
    date_default_timezone_set("Australia/SYDNEY");

    $csv = array_map('str_getcsv', file($argv[1]));

    clearScreen();

    $graph = createGraph($csv);

    printMenu();

    // Loop until they enter 'QUIT' for Quit
    do {
        // A character from STDIN, ignoring whitespace characters
        do {
            $userInput = trim(fgets(STDIN));

            if ($userInput != 'QUIT') {
                $explodeUserInput = explode(" ", $userInput);

                if (count($explodeUserInput) == 3) {
                    echo findPath($graph, $explodeUserInput[0], $explodeUserInput[1], $explodeUserInput[2]);
                }

                prompt();
            } else {
                exit(0);
            }

        } while ( trim($userInput) == '' );

    } while ( $userInput != 'QUIT');

    exit(0);
}

function printMenu()
{
    fwrite(STDOUT, "Enter <source node> <destination node> <time>\n");
    fwrite(STDOUT, "Enter 'QUIT' to quit\n");

    prompt();
}

function clearScreen()
{
    $retval = null;

    system("clear",$retval);

    if($retval !== 0){
        exit($retval);
    }
}

function prompt()
{
    fwrite(STDOUT, "> ");
}

/**
 * Function to generate the graph from the array of CSV data
 *
 * @param $data
 * @return array
 */
function createGraph($data)
{
    $graph = array();
    $rowCount = 1;
    foreach ($data as $path) {
        if (is_numeric($path[2])) {
            if (!isset($graph[$path[0]])) {
                $graph[$path[0]] = array();
            }
            $graph[$path[0]][$path[1]] = $path[2];

            /**
             * Create the corresponding reverse path for this point to the source
             */
            if (!isset($graph[$path[1]])) {
                $graph[$path[1]] = array();
            }
            $graph[$path[1]][$path[0]] = $path[2];
        } else {
            echo "Data at row {$rowCount} is not valid because the latency value is not a number.\n";
        }

        $rowCount++;
    }

    return $graph;
}


function findPath($graph, $source, $target, $time)
{
    $visited = array();
    /**
     * Initialize Queue
     */
    $queue = new SplQueue();
    $path = array();

    /**
     * Make all points on the graph to be not visited first
     */
    foreach ($graph as $point => $neighbourPoint) {
        $visited[$point] = false;
    }

    /**
     * Put the start point into the queue and mark it as "visited"
     */
    $queue->enqueue($source);
    $visited[$source] = true;

    /**
     * Use custom doubly linked list with a count attribute to keep track of the time for the path
     */
    $path[$source] = new DoublyLinkedListWithCount();
    $path[$source]->setIteratorMode(
        // Make this linked list a queue style and the element are traversed by iterator
        SplDoublyLinkedList::IT_MODE_FIFO|SplDoublyLinkedList::IT_MODE_KEEP
    );

    $path[$source]->push($source);

    /**
     * Start looping for the queue when queue is not empty and the target is not found yet.
     */
    while (!$queue->isEmpty() && $queue->bottom() != $target) {
        /**
         * Get the element at the top of the queue
         */
        $topNode = $queue->dequeue();

        if (!empty($graph[$topNode])) {
            /**
             * Loop at each of the neighbours that the point is linked to
             */
            foreach ($graph[$topNode] as $point => $latency) {
                if (!$visited[$point]) { // Point not visited before, so begin the check

                    $path[$point] = clone $path[$topNode];

                    /**
                     * Do a check first, if the total time to get to that point is already
                     * more than what the user has asked for, then ignore.
                     * If not then we keep track of them in the queue.
                     */
                    if (($path[$point]->getTotalTime() + $latency) <= $time) {
                        /**
                         * When the path to this point is below the time, then queue the point
                         * and mark it as visited
                         */
                        $queue->enqueue($point);
                        $visited[$point] = true;

                        $path[$point] = clone $path[$topNode];
                        $path[$point]->push($point);
                        $path[$point]->setTotalTime($path[$point]->getTotalTime() + $latency);
                    } else {
                        /**
                         * If this point cost more than the time, then unset this point from the $path so that the while loop
                         * will keep going instead of stopping because it thinks it has found the path
                         */
                        unset($path[$point]);
                    }
                }
            }
        }
    }

    /**
     * $path[$target] will contain the list of points from $source to $target with the total count of time
     */
    if (isset($path[$target])) {

        $pathPoint = array();
        foreach ($path[$target] as $point) {
            $pathPoint[] = $point;
        }

        $sep = implode(" => ", $pathPoint) . " => " . $path[$target]->getTotalTime() . "\n";

        return $sep;
    }
    else {
        return "Path from $source to $target not found.\n";
    }
}