<?php

namespace App\Controller;

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

    public function breedingAndCrossbreeding (array $chromosomes, int $crossoverPoint)
    {
//        $crossoverPoint = count($chromosomes[1]['parcours']) / 2;
        $firstPart = array_slice($chromosomes[0]['parcours'], 0, $crossoverPoint);
        $secondPart = array_slice($chromosomes[1]['parcours'], $crossoverPoint,  count($chromosomes[1]['parcours']) - $crossoverPoint);

        $modifiedChromosome = array_merge($firstPart, $secondPart);

        $cities = $this->extractCities($modifiedChromosome);

//        dd( count(array_unique($this->extractCities($chromosomes[0]['parcours']))), count($this->extractCities($chromosomes[0]['parcours'])) - 1);

        $i = 1;

        while (count($cities) - 1 !== count(array_unique($cities))) {
            $i++;

            $modifiedChromosome = array_merge($firstPart, array_slice($chromosomes[$i]['parcours'], $crossoverPoint,  count($chromosomes[$i]['parcours']) - $crossoverPoint));
        }

        $nouvellePopulationDeChromosomes = $this->keepsTheBest($this->createChildren($modifiedChromosome, 100), 25);

        dd($nouvellePopulationDeChromosomes);
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


    #[Route('/', name: 'home')]
    public function index(ChartBuilderInterface $chartBuilder) {

        $this->algo_genetique();

//        $pieces = [2, 5, 10, 50, 100];
//
////        dd($this->rendu_monnaie_naive($pieces, 150));
//
//        $resultat20 = $this->rendu_monnaie_dynamique($pieces, 20);
//        $resultat70 = $this->rendu_monnaie_dynamique($pieces, 70);
//
//        return $this->render('base.html.twig', compact('resultat20', 'resultat70'));
//
//
//        $this->rendreLeMoinsDeMonnaiePossible(1462, 1500);
//
//        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
//
//        $i = 0;
//
//
//        $tableOfOneHundredRandomNumbers = array_map(function ($value) {
//            static $i = 0;
//            $i++;
//            return $i;
//        }, array_fill(0, 10, null));
//
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

    private function keepsTheBest(array $list, int $int): array
    {
        return array_slice($list, 0, $int);
    }
}