<?php namespace Waka\Charter\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Waka\Charter\Models\Chart;

/**
 * Charts Back-end Controller
 */
class Charts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Waka.Charter', 'charter', 'charts');
    }

    public function test($id)
    {
        $data = $data = [
            'title' => 'Attention ce sont de fausses données',
            'subtitle' => "l'app n'a pas reçu de données",
            'width' => 600,
            'textyaxis' => null,
            'dataSets' => [
                [
                    'name' => "serie 1",
                    'data' => [43934, 52503, 57177, 69658, 97031, 119931, 137133, 125000],
                ],
                [
                    'name' => "serie 2",
                    'data' => [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434],
                ],
                [
                    'name' => "serie 3",
                    'data' => [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387],
                ],
            ],
        ];
        $view = $this->createView($data, 'bar');
        $width = null;
        trace_log($view);
        return $view;

        //return \SnappyImage::loadHTML($view)->setOption('width', $width)->setOption('format', 'jpeg')->inline();

    }

    public function makeimg($id)
    {
        $filename = uniqid('oc');
        $filepath = temp_path() . "/charts/" . $filename;
        $data = null;
        $view = $this->createView($data, 'test');

        return $view;

        $width = $data['width'] ?? 400;

        return \SnappyImage::loadHTML($view)->setOption('width', $width)->setOption('format', 'jpeg')->inline();

        // $snappy = \App::make('snappy.image');
        // $snappy->generateFromHtml($view, $filepath);
        // return $filepath;
    }
    public function createurl($id)
    {
        $chart = Chart::find($id);
        if (!$chart) {
            $chart = new Chart();
            $filepath = $chart->url;
            $data = null;
            $view = $this->createView($data, 'test');

            $width = $data['width'] ?? 400;
            $snappy = \App::make('snappy.image');
            $snappy->setOption('format', 'jpeg');
            $snappy->setOption('width', $width);
            $snappy->generateFromHtml($view, $filepath);
            $chart->ready = true;
            $chart->save();
        }
        return $chart->url;
    }

    public function createView($data, $template = "test")
    {
        if (!$data) {
            $data = [
                'title' => 'Attention ce sont de fausses données',
                'subtitle' => "l'app n'a pas reçu de données",
                'width' => 600,
                'textyaxis' => null,
                'series' => [
                    [
                        'name' => "serie 1",
                        'data' => [43934, 52503, 57177, 69658, 97031, 119931, 137133, 140000],
                    ],
                    [
                        'name' => "serie 2",
                        'data' => [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434],
                    ],
                    [
                        'name' => "serie 3",
                        'data' => [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387],
                    ],
                    [
                        'name' => "serie 4",
                        'data' => [0, 0, 7988, 12169, 15112, 22452, 34400, 34227],
                    ],
                    [
                        'name' => "serie 5",
                        'data' => [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111],
                    ],

                ],
            ];
        }
        return \View::make('waka.charter::charts.' . $template)->withData($data);

    }
}
