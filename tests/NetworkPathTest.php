<?php

require "DoublyLinkedListWithCount.php";
require "functions.php";

class NetworkPathTest extends PHPUnit_Framework_TestCase
{
    public function testCreateGraph()
    {
        $csv = array(
            array("A", "B", 20)
        );

        $expected = array(
            "A" => array(
                "B" => 20
            ),
            "B" => array(
                "A" => 20
            )
        );

        $this->assertEquals(createGraph($csv), $expected);

        $csv = array(
            array("A", "B", 20),
            array("A", "C", 10),
            array("B", "C", 11)
        );

        $expected = array(
            "A" => array(
                "B" => 20,
                "C" => 10
            ),
            "B" => array(
                "A" => 20,
                "C" => 11
            ),
            "C" => array(
                "A" => 10,
                "B" => 11
            )
        );

        $this->assertEquals(createGraph($csv), $expected);
    }

    public function testFindPath()
    {
        $graph = array(
            "A" => array(
                "B" => 20,
                "C" => 10
            ),
            "B" => array(
                "A" => 20,
                "C" => 11
            ),
            "C" => array(
                "A" => 10,
                "B" => 11
            )
        );

        $this->assertEquals(findPath($graph, 'A', 'B', 100), "A => B => 20\n");
        $this->assertEquals(findPath($graph, 'A', 'B', 1), "Path from A to B not found.\n");
        $this->assertEquals(findPath($graph, 'A', 'C', 1000), "A => C => 10\n");
        $this->assertEquals(findPath($graph, 'A', 'A', 1000), "A => 0\n");
    }
}