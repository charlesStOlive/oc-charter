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

    // private function makeChartFromData($data, $type)
    // {
    //     $view = \View::make('waka.charter::charts.' . $type)->withData($data);
    //     return $view;
    // }

    public function makeChartUrl($data = null, $type = null, $plugins = "waka.charter::charts")
    {
        if (!$type) {
            $type = 'doughnut';
        }

        if (!$data) {
            $data = $this->getDataExemple($type);
        }

        //trace_log($data);

        $view = \View::make($plugins . '.' . $type)->withData($data);

        $filename = uniqid('oc');
        $fileAdress = "/storage/app/media/charts/" . $filename . '.jpeg';
        $filepath = public_path() . $fileAdress;
        //return \SnappyImage::loadHTML($view)
        \SnappyImage::loadHTML($view)
            ->setOption('width', $data['width'])
            ->setOption('height', $data['height'])
            ->setOption('format', 'jpeg')
            ->save($filepath);
        //->inline();

        return \Config::get('app.url') . $fileAdress;
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

    public function getDataExemple($type)
    {
        if ($type == "l_line_r_bar") {
            return [
                "labels" => ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                'set_l' => [100, 50, 10, 300, 222, 150],
                'set_r' => [10, 8, 1, 15, 16, 12],
                'set_l_label' => "CA HT",
                'set_r_label' => "NB ventes",
                'set_l_title' => "€ HT",
                'set_r_title' => "Nb",
                'width' => 500,
                'height' => 500,
            ];
        }
        if ($type == "2bars") {
            return [
                "labels" => ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                'set_l' => [100, 50, 10, 300, 222, 150],
                'set_r' => [80, 45, 12, 280, 200, 100],
                'set_l_label' => "CA 2020",
                'set_r_label' => "CA 2019",
                'width' => 500,
                'height' => 500,
            ];
        }
        $mainColor = \Config::get('waka.wconfig::colors.primary');
        $colors = new \Waka\Utils\Classes\PhpColors($mainColor);
        if ($type == "pie") {
            return [
                "labels" => ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                'set' => [100, 50, 10, 300, 222, 150],
                'backgroundColors' => $colors->getColorsArray(6),
            ];
        }
        if ($type == "doughnut") {
            return [
                "labels" => ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                'set' => [100, 50, 10, 300, 222, 150],
                'backgroundColors' => $colors->getColorsArray(6),
            ];
        }
    }

}
