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

    #[Route('/', name: 'home')]
    public function index(ChartBuilderInterface $chartBuilder) {
        $pieces = [2, 5, 10, 50, 100];

//        dd($this->rendu_monnaie_naive($pieces, 150));

        $resultat20 = $this->rendu_monnaie_dynamique($pieces, 20);
        $resultat70 = $this->rendu_monnaie_dynamique($pieces, 70);

        return $this->render('base.html.twig', compact('resultat20', 'resultat70'));


        $this->rendreLeMoinsDeMonnaiePossible(1462, 1500);

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $i = 0;


        $tableOfOneHundredRandomNumbers = array_map(function ($value) {
            static $i = 0;
            $i++;
            return $i;
        }, array_fill(0, 10, null));


        shuffle($tableOfOneHundredRandomNumbers);
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
}