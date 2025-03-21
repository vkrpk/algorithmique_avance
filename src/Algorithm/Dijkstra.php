<?php

namespace App\Algorithm;

class Dijkstra {
    private $graph = [];
    private $nodes = [];
    private $neighbors = [];
    private $minDist = [];
    private $prevHopNodes = [];

    public function __construct(array $graph) {
        $this->graph = $graph;

        $this->initGraph();
    }

    public function getPath(string $start, string $end):array {
        //  init node data
        $this->initNodeData($start);

        /*
        while Q is not empty:
            u ← vertex in Q with minimum dist[u]
            remove u from Q
        */

        //  mutate $Q, retain all nodes for future rerunning
        $Q = $this->nodes;

        while(count($Q) > 0) {
            $currentNode = $this->findNextClosestNode($Q);

            //  stop when we reach the end
            if($currentNode == $end || $this->minDist[$currentNode] == INF) {
                break;
            }

            //  set all neighbors distance to start and previous hop
            $this->updateNeighbors($currentNode);

            //  remove $currentNode from $Q
            $Q = array_diff($Q, [$currentNode]);
        }

        return $this->shortestPath($end);
    }

    private function shortestPath(string $end):array {
        //  walk the hops backwards from the end
        $path = [$end];

        //  current node is the end node
        $node = $end;

        while(isset($this->prevHopNodes[$node])) {
            //  the the previous hop to our current node
            $node = $this->prevHopNodes[$node];

            //  push the previous node to the start of our path
            array_unshift($path, $node);
        }

        return $path;
    }

    private function updateNeighbors(string $currentNode):void {

        /*
        for each neighbor v of u still in Q:
            alt ← dist[u] + Graph.Edges(u, v)
            if alt < dist[v]:
                dist[v] ← alt
                prev[v] ← u
        */

        if(isset($this->neighbors[$currentNode])) {
            foreach($this->neighbors[$currentNode] AS $neighbor) {
                $neighborDistanceToStart = $this->minDist[$currentNode] + $neighbor["distance"];

                if($neighborDistanceToStart < $this->minDist[$neighbor["neighborNode"]]) {
                    if($this->minDist[$neighbor["neighborNode"]] != INF) {
                        //  helpful output to see new lowest distance for a node
                        echo "to ".$neighbor["neighborNode"]." via ";
                        echo $this->prevHopNodes[$neighbor["neighborNode"]];
                        echo " (".$this->minDist[$neighbor["neighborNode"]].")";
                        echo " > ";
                        echo "to ".$neighbor["neighborNode"]." via ";
                        echo $currentNode;
                        echo " (".$neighborDistanceToStart.")";
                        echo PHP_EOL;
                    }

                    //  set lowest distance to reach this node
                    $this->minDist[$neighbor["neighborNode"]] = $neighborDistanceToStart;

                    //  set the previous best hop node for this node
                    $this->prevHopNodes[$neighbor["neighborNode"]] = $currentNode;
                }
            }
        }
    }

    private function findNextClosestNode(array $Q):string {
        $closest = INF;

        foreach($Q AS $node) {
            if($this->minDist[$node] < $closest) {
                $closest = $this->minDist[$node];
                $currentNode = $node;
            }
        }

        return $currentNode;
    }

    private function initNodeData(string $start):void {

        /*
        for each vertex v in Graph.Vertices:
            dist[v] ← INFINITY
            prev[v] ← UNDEFINED
            add v to Q (done in initGraph)
        dist[source] ← 0
        */

        $this->minDist = [];
        $this->prevHopNodes = [];

        foreach($this->nodes AS $node) {
            //  min distance to reach this node
            $this->minDist[$node] = INF;

            //  the previous node visited to reach this min distance
            $this->prevHopNodes[$node] = NULL;
        }

        //  the min distance to the start is always 0
        $this->minDist[$start] = 0;
    }

    private function initGraph():void {
        //  transform graph into something more useful
        foreach($this->graph AS $edge) {
            array_push($this->nodes, $edge[0], $edge[1]);

            $this->neighbors[$edge[0]][] = [
                "neighborNode" => $edge[1],
                "distance" => $edge[2]
            ];

            $this->neighbors[$edge[1]][] = [
                "neighborNode" => $edge[0],
                "distance" => $edge[2]
            ];
        }

        $this->nodes = array_unique($this->nodes);
    }
}