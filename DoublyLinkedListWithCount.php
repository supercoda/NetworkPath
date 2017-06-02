<?php

/**
 * This class represent a double linked list node with a total time count
 *
 * Class DoublyLinkedListWithCount
 */
class DoublyLinkedListWithCount extends SplDoublyLinkedList
{
    private $totalTime = 0;

    public function setTotalTime(int $totalTime)
    {
        $this->totalTime = $totalTime;
    }

    public function getTotalTime() : int
    {
        return $this->totalTime;
    }
}