<?php namespace Waka\Charter\Behaviors;

use Backend\Classes\ControllerBehavior;
use Waka\Charter\Widgets\ChartsWidget;

class ChartsBehavior extends ControllerBehavior
{

    //protected $exportExcelWidget;
    public $chartWidget;

    public function __construct($controller)
    {
        parent::__construct($controller);
        /*
         * Build configuration
         */
        $this->chartWidget = new ChartsWidget($controller);
        $this->chartWidget->alias = 'chartWidget';
        $this->chartWidget->bindToController();
    }

    public function createChart($chartData, $partial, $options, $width, $height)
    {
        $htm = $this->chartWidget->create($chartData, $partial, $options, $width, $height);
        //trace_log($htm);

        $filename = uniqid('oc');
        $fileAdress = "/storage/app/media/charts/" . $filename . '.jpeg';
        $filepath = public_path() . $fileAdress;
        \SnappyImage::loadHTML($htm)
            ->setOption('width', $width)
            ->setOption('height', $height)
            ->setOption('enable-javascript', true)
            ->setOption('javascript-delay', 500)
            ->setOption('format', 'jpeg')
            ->save($filepath);
        //->inline();
        $this->vars['chartUrlWidget'] = \Config::get('app.url') . $fileAdress;
        $this->vars['chartWidget'] = $htm;

        $colors = \Waka\Utils\Classes\PhpColors::getSeparate(4);
        $this->vars['colors'] = $colors;

        return $this->makePartial('$/waka/charter/controllers/charts/_popup.htm');
    }

    public function createChartUrl($chartData, $partial, $options, $width, $height)
    {
        //trace_log($chartData);
        //trace_log($options);

        $htm = $this->chartWidget->create($chartData, $partial, $options, $width, $height);
        //trace_log($htm);

        $filename = uniqid('oc');
        $fileAdress = "/storage/app/media/charts/" . $filename . '.jpeg';
        $filepath = public_path() . $fileAdress;
        \SnappyImage::loadHTML($htm)
            ->setOption('width', $width)
            ->setOption('height', $height)
            ->setOption('enable-javascript', true)
            ->setOption('javascript-delay', 500)
            ->setOption('format', 'jpeg')
            ->save($filepath);
        //->inline();
        return \Config::get('app.url') . $fileAdress;
    }

    public function onTest()
    {
        //bar 1 sets
        $partial = "bar_or_line";
        $options = [
            'type' => 'line',
            'beginAtZero' => false,
        ];
        $width = 500;
        $height = 500;
        $chartData = [
            'labels' => ['2021-quarter-1', '2020-quarter-4', '2020-quarter-3', '2020-quarter-2', '2020-quarter-1', '2019-quarter-4'],
            'datasets' => [
                [
                    'data' => [79264, 201907, 205654, 206525, 167813, 148569],
                    'label' => ['CA 6 derniers trimestres'],
                ],
            ],
        ];

        // //bar 2 sets
        // $partial = "bar_or_line";
        // $options = [
        //     'type' => 'bar',
        //     'beginAtZero' => false,
        // ];
        // $width = 500;
        // $height = 500;
        // $chartData = [
        //     'labels' => ['2021-quarter-1', '2020-quarter-4', '2020-quarter-3', '2020-quarter-2', '2020-quarter-1', '2019-quarter-4'],
        //     'datasets' => [
        //         [
        //             'data' => [79264, 201907, 205654, 206525, 167813, 148569],
        //             'label' => ['CA 6 derniers trimestres'],
        //         ],
        //         [
        //             'data' => [79164, 211907, 215654, 206525, 157813, 138569],
        //             'label' => ['Volume 6 derniers trimestres'],
        //         ],
        //     ],
        // ];

        // //pie
        // $partial = "pie_or_doughnut";
        // $options = [
        //     'type' => 'doughnut',
        //     'beginAtZero' => true,
        //     'cutoutPercentage' => 10,
        // ];
        // $width = 500;
        // $height = 500;
        // $chartData = [
        //     'labels' => ['2021-quarter-1', '2020-quarter-4', '2020-quarter-3', '2020-quarter-2', '2020-quarter-1', '2019-quarter-4'],
        //     'datasets' => [
        //         [
        //             'data' => [79264, 201907, 205654, 206525, 167813, 148569],
        //         ],
        //     ],
        // ];

        //line multi exis
        // $partial = "bar_or_line_2_axis";
        // $options = [
        //     'type' => 'bar',
        //     'beginAtZero' => true,
        // ];
        // $width = 500;
        // $height = 500;
        // $chartData = [
        //     'labels' => ['2021-quarter-1', '2020-quarter-4', '2020-quarter-3', '2020-quarter-2', '2020-quarter-1', '2019-quarter-4'],
        //     'datasets' => [
        //         [
        //             'data' => [79264, 201907, 205654, 206525, 167813, 148569],
        //             'label' => ['CA'],
        //             'yAxisID' => 'y_1',
        //         ],
        //         [
        //             'data' => [25, 30, 45, 40, 10, 20],
        //             'label' => ['volume'],
        //             'yAxisID' => 'y_2',
        //         ],
        //     ],
        // ];

        return $this->createChart($chartData, $partial, $options, $width, $height);
    }
}
