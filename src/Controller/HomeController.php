<?php

namespace App\Controller;

use App\Algorithm\Dijkstra;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    public function triABulle(array $array): array
    {
        $arrayCount = count($array);

        for ($i = 0; $i < $arrayCount - 1; $i++) {
            for ($j = $i; $j < $arrayCount - 1; $j++) {
                if($array[$j] > $array[$j + 1]) {
                    $temp = $array[$j + 1];
                    $array[$j + 1] = $array[$j];
                    $array[$j] = $temp;
                }
            }
        }

        return $array;
    }

    public function rendreLeMoinsDeMonnaiePossible(int $montant, int $donne) {
        $pieces = [2, 5, 10, 20, 50, 100, 200];

        $donneInitial = $donne;

        $monnaie = [];

        $cleCinq = array_search(5, $pieces);

        if ((($montant - $donne) % 2) === 0) {
                unset($pieces[$cleCinq]);
        }

        rsort($pieces);

        $countArrayPieces = count($pieces);

        for ($i = 0; $i < $countArrayPieces; $i++) {
            while ($donne >= $pieces[$i]) {
                if ($donne - $montant >= $pieces[$i]) {
                    $monnaie[] = $pieces[$i];
                    $donne -= $pieces[$i];
                } else {
                    break;
                }
            }
        }

        $monnaieRendu = 0;

        $monnaieRendu = array_reduce($monnaie, function ($result, $item) {
            return $result + $item;
        }, $monnaieRendu);

        dd($monnaie, $monnaieRendu, 'Montant : ' . $montant, 'Donné : ' . $donneInitial);
    }

    public function rendu_monnaie_naive (array $pieces, int $montant) {
        if ($montant === 0) {
            return 0;
        } else {
            $mini = 100000;
            $length = count($pieces);

            for ($i = 0; $i < $length; $i++) {
                if ($pieces[$i] <= $montant) {
                    $nb = 1 + $this->rendu_monnaie_naive($pieces, $montant-$pieces[$i]);
                    if($nb < $mini) {
                        $mini = $nb;
                    }
                }
            }
            return $mini;
        }
    }

    public function rendu_monnaie_dynamique(array $pieces, int $montant) {
        $memoire = array_fill(0, $montant + 1, PHP_INT_MAX);

        $memoire[0] = 0;

        for ($m = 1; $m <= $montant; $m++) {
            foreach ($pieces as $piece) {
                if ($piece <= $m) {
                    $resultat = $memoire[$m - $piece];
                    if ($resultat != PHP_INT_MAX && $resultat + 1 < $memoire[$m]) {
                        $memoire[$m] = $resultat + 1;
                    }
                }
            }
        }

        dd($memoire);

        return $memoire[$montant] == PHP_INT_MAX ? -1 : $memoire[$montant];
    }

    public function shuffle_assoc($list) {
        if (!is_array($list)) return $list;

        $keys = array_keys($list);
        shuffle($keys);
        $random = ['PARIS' => ['x' => 48.8566, 'y' => 2.3522, 'nom' => 'PARIS']];
        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }
        return $random;
    }

    private function deg2rad (int $x){
        return pi() * $x/180;
    }

    private function get_distance_m (int $lat1, int $lng1, int $lat2, int $lng2) {
        $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
        $rlo1 = deg2rad($lng1);    // CONVERSION
        $rla1 = deg2rad($lat1);
        $rlo2 = deg2rad($lng2);
        $rla2 = deg2rad($lat2);
        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return (int) round(($earth_radius * $d) / 1000);
    }

    public function createChildren (array $villes, int $numberOfChildren): array
    {
        $chromosomes = [];

        for ($i = 0; $i < $numberOfChildren; $i++) {
            $villesCopy = $villes;

            $distance = 0;

            shuffle($villesCopy);

            if(!in_array("PARIS", $this->extractCities($villes), true)) {
                array_unshift($villesCopy, ['x' => 48.8566, 'y' => 2.3522, 'ville' => 'PARIS']);
                $villesCopy[] = ['x' => 48.8566, 'y' => 2.3522, 'ville' => 'PARIS'];
            }

            $listLength = count($villesCopy);

            for ($j = 0; $j < $listLength - 1; $j++) {
                $distance += $this->get_distance_m($villesCopy[$j]['x'], $villesCopy[$j]['y'], $villesCopy[$j+1]['x'], $villesCopy[$j+1]['y']);
            }

            $chromosomes = $this->deleteDuplicates($villesCopy, $chromosomes, $distance);
        }

        usort($chromosomes, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        return $chromosomes;
    }

    public function extractCities (array $chromosome) {
        return array_map(function($value) {
            return $value['ville'];
        }, $chromosome);
    }

    private function mutation (array $population, int $rate)
    {
        for ($i = 0; $i < count($population) - 1; $i++) {
            dd(count($population[$i]['parcours']['ville']));
            if ((int) round(mt_rand() / mt_getrandmax() * 100) < $rate) {
                $min = 1;
                $max = count($population[$i]) - 2;

                $this->array_swap($population, rand($min, $max), rand($min, $max));
            }
        }
    }

    public function array_swap(&$array,$swap_a,$swap_b){
        list($array[$swap_a],$array[$swap_b]) = array($array[$swap_b],$array[$swap_a]);
    }

    public function breedingAndCrossbreeding (array $chromosomes, int $crossoverPoint)
    {
//        $crossoverPoint = count($chromosomes[1]['parcours']) / 2;
        $firstPart = array_slice($chromosomes[0]['parcours'], 0, $crossoverPoint);
        $secondPart = array_slice($chromosomes[1]['parcours'], $crossoverPoint,  count($chromosomes[1]['parcours']) - $crossoverPoint);

        $modifiedChromosome = array_merge($firstPart, $secondPart);

        $cities = $this->extractCities($modifiedChromosome);

//        dd( count(array_unique($this->extractCities($chromosomes[0]['parcours']))), count($this->extractCities($chromosomes[0]['parcours'])) - 1);

        $i = 1;

        try {
            while (count($cities) - 1 !== count(array_unique($cities))) {
                $i++;

                $modifiedChromosome = array_merge($firstPart, array_slice($chromosomes[$i]['parcours'], $crossoverPoint,  count($chromosomes[$i]['parcours']) - $crossoverPoint));
            }
        } catch (\Exception $exception) {
            dd('reproduction et croisement : Impossible de fusionner le meilleur résultat avec une autre séquence sans passer deux fois par la même ville.');
        }

        $nouvellePopulationDeChromosomes = $this->keepsTheBest($this->createChildren($modifiedChromosome, 100), 25);

        dd($this->mutation($nouvellePopulationDeChromosomes, 100));
    }

    public function algo_genetique ()
    {
        $villes = [
            ['x' => 48.5734, 'y' => 7.7521, 'ville' => 'STRASBOURG'],
            ['x' => 48.1173, 'y' => -1.6778, 'ville' => 'RENNES'],
            ['x' => 43.7102, 'y' => 7.2620, 'ville' => 'NICE'],
            ['x' => 47.4784, 'y' => -0.5632, 'ville' => 'ANGERS'],
            ['x' => 47.2184, 'y' => -1.5536, 'ville' => 'NANTES'],
            ['x' => 43.7383, 'y' => 7.4244, 'ville' => 'MONACO']
        ];

        $chromosomes = $this->keepsTheBest($this->createChildren($villes, 100), 25);

        $this->breedingAndCrossbreeding($chromosomes, 1);

        dump($chromosomes);
    }

    public function deleteDuplicates(array $villes, array $chromosomes, int $distance): array
    {
        $parcoursKey = '';
        foreach ($villes as $ville) {
            $parcoursKey .= $ville['ville'] . '|';
        }

        $existe = false;
        foreach ($chromosomes as $chromosome) {
            $existingKey = '';
            foreach ($chromosome['parcours'] as $ville) {
                $existingKey .= $ville['ville'] . '|';
            }

            if ($existingKey === $parcoursKey) {
                $existe = true;
                break;
            }
        }

        if (!$existe) {
            $chromosomes[] = ['parcours' => $villes, 'distance' => $distance];
        }

        return $chromosomes;
    }

    private function heuristic($node, $goal) {
        return abs($node[0] - $goal[0]) + abs($node[1] - $goal[1]);
    }

    public function getDistance (array $start, array $end) {
        $distanceX = $start[0] - $end[0];
        $distanceY = $start[1] - $end[1];

        return (int) round(sqrt($distanceX**2 + $distanceY**2)) * 10;
    }

    public function pathStep (array $currentPosition)
    {
        $adjacentPositions = $this->getAdjacentPositions($currentPosition);
        foreach ($adjacentPositions as $adjacentPosition) {

        }

        if ($currentPosition ) {
            throw new \Exception('Chemin trouvé');
        }
    }

    private function computeDistances ($grid) {

    }

    private function getAdjacentPositions (array $currentPosition)
    {
        $directions = [[0, 1], [1, 0], [-1, 1], [0, -1]];
        $adjPositions = [];

        foreach ($directions as $direction) {
            $adjX = $currentPosition[0] + $direction[0];
            $adjY = $currentPosition[1] + $direction[1];

            $adjPositions[] = [$adjX, $adjY];
        }

        return $adjPositions;
    }

    public function fillArrayWith (array $grid, bool|int $value) {
        $array = [];
        for ($i = 0; $i < count($grid); $i++) {
            for ($j = 0; $j < count($grid[0]); $j++) {
                $array[$i][$j] = $value;
            }
        }

        return $array;
    }

    public function algo_dijkstra (array $grid, array $goal)
    {
        $start = [0, 0];

        $p = $this->fillArrayWith($grid, PHP_INT_MAX);
        $visited = $this->fillArrayWith($grid, false);

        $p[$start[0]][$start[1]] = 0;

        $q = [$start];

        while (!empty($q)) {
            $minDistance = PHP_INT_MAX;
            $minIndex = -1;

            for ($i = 0; $i < count($q); $i++) {
                $node = $q[$i];
                if ($p[$node[0]][$node[1]] < $minDistance) {
                    $minDistance = $p[$node[0]][$node[1]];
                    $minIndex = $i;
                }
            }

            if ($minIndex === -1) {
                break;
            }

            $current = $q[$minIndex];
            array_splice($q, $minIndex, 1);

            $row = $current[0];
            $col = $current[1];

            if ($visited[$row][$col]) {
                continue;
            }

            $visited[$row][$col] = true;

            if ($row === $goal[0] && $col === $goal[1]) {
                break;
            }

            $directions = [[-1, 0], [0, 1], [1, 0], [0, -1]];

            foreach ($directions as $dir) {
                $newRow = $row + $dir[0];
                $newCol = $col + $dir[1];

                if ($newRow >= 0 && $newRow < count($grid) &&
                    $newCol >= 0 && $newCol < count($grid[0])) {

                    if ($grid[$newRow][$newCol] === 1) { // chemin praticable
                        $newDist = $p[$row][$col] + 1;
                        if ($newDist < $p[$newRow][$newCol]) {
                            $p[$newRow][$newCol] = $newDist;

                            if (!$visited[$newRow][$newCol]) {
                                $q[] = [$newRow, $newCol];
                            }
//                            dump($q);
                        }
                    }
                }
            }
        }

        dd($this->displayMultidimensionalArray($p), $this->displayMultidimensionalArray($visited));
    }

    #[Route('/', name: 'home')]
    public function index(ChartBuilderInterface $chartBuilder)
    {
        $grid = [
            [0, 1, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 0, 1, 1, 1, 1, 0],
            [0, 1, 0, 1, 0, 0, 0, 0, 1, 0],
            [0, 1, 0, 1, 1, 1, 1, 0, 1, 0],
            [0, 0, 0, 1, 0, 0, 1, 0, 0, 0],
            [0, 1, 0, 1, 0, 0, 1, 0, 1, 0],
            [0, 1, 0, 1, 1, 0, 1, 0, 1, 0],
            [0, 1, 0, 0, 0, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        ];

        $goal = [0, 0];

        while ($grid[$goal[0]][$goal[1]] !== 1) {
            $goal = [random_int(0, 9), random_int(0, 9)];
        }

        dd($goal);
        $this->algo_dijkstra($grid, $goal);

//        $start = [0, 0];

//        $grid[$start[0]][$goal[1]] = 0;
//        $grid[$start[0]][$goal[1]] = 0;
//
//        $Dijkstra = new Dijkstra($grid);
//
//        $this->algo_genetique();

//        $pieces = [2, 5, 10, 50, 100];
//
//       dd($this->rendu_monnaie_naive($pieces, 150));
//
//        $resultat20 = $this->rendu_monnaie_dynamique($pieces, 20);
//        $resultat70 = $this->rendu_monnaie_dynamique($pieces, 70);
//
//        return $this->render('base.html.twig', compact('resultat20', 'resultat70'));
//
//        $this->rendreLeMoinsDeMonnaiePossible(1462, 1500);
//
//        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
//
//        $i = 0;

//        $tableOfOneHundredRandomNumbers = array_map(function ($value) {
//            static $i = 0;
//            $i++;
//            return $i;
//        }, array_fill(0, 10, null));
//
//        shuffle($tableOfOneHundredRandomNumbers);
//        dd($tableOfOneHundredRandomNumbers);

//        $searchValue = 5;
//        $normalSearch =
//        $startNormalSearch = microtime(true);
//
//        foreach ($tableOfOneHundredRandomNumbers as $tableOfOneHundredRandomNumber) {
//            if($tableOfOneHundredRandomNumber === $searchValue) {
//                break;
//            }
//        }
//        $time_end = microtime(true);
//
//        $time_elapsed_secs = ($time_end - $startNormalSearch)/60;

//        dd($this->triABulle($tableOfOneHundredRandomNumbers));

//        $chart->setData([
//            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
//            'datasets' => [
//                [
//                    'label' => 'My First dataset',
//                    'backgroundColor' => 'rgb(255, 99, 132)',
//                    'borderColor' => 'rgb(255, 99, 132)',
//                    'data' => [0, 10, 5, 2, 20, 30, 45],
//                ],
//            ],
//        ]);
//
//        $chart->setOptions([
//            'scales' => [
//                'y' => [
//                    'suggestedMin' => 0,
//                    'suggestedMax' => 100,
//                ],
//            ],
//        ]);

        return $this->render('base.html.twig', [
//            'startNormalSearch' => $time_elapsed_secs,
//            'chart' => $chart,
        ]);
    }

    public function displayMultidimensionalArray (array $multidimensionalArray) { {
        $colcount = count($multidimensionalArray[0]);
        for ($c = 0; $c < $colcount; $c++) {
            foreach ($multidimensionalArray as $row) {
                echo $row[$c] . "|";
            }
            echo "<hr>";
        }
    }}

    private function keepsTheBest(array $list, int $int): array
    {
        return array_slice($list, 0, $int);
    }
}