<?php

/**
 * This class represent a double linked list node with a total time count
 *
 * Class DoublyLinkedListWithCount
 */
class DoublyLinkedListWithCount extends SplDoublyLinkedList
{
    private $totalTime = 0;

    public function setTotalTime($totalTime)
    {
        $this->totalTime = $totalTime;
    }

    public function getTotalTime()
    {
        return $this->totalTime;
    }
}