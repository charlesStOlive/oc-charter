<?php namespace Waka\Charter\Behaviors;

use Backend\Classes\ControllerBehavior;
use Waka\Charter\Widgets\ChartsWidget;
use Waka\Utils\Classes\TmpFiles;

class ChartsBehavior extends ControllerBehavior
{

    //protected $exportExcelWidget;
    public $chartType;
    public $chartWidget;
    private $chartOptions;
    private $chartDatas;
    

    public function __construct($controller)
    {
        parent::__construct($controller);
        /*
         * Build configuration
         */
        $this->chartWidget = new ChartsWidget($controller);
        $this->chartWidget->alias = 'chartWidget';
        $this->chartWidget->bindToController();
        $this->chartOptions = [];

    }

    //  public static function find($code)
    // {
    //     $chartType;
    //     $chartModels = Config::get('chartModel');
    //     $chartType = $chartModels[$code] ?? null;
    //     if (!$chartType) {
    //         throw new ApplicationException("Problème de configuration de chartModels");
    //     }
    //     self::$chartType = $chartType;
    //     return new self;
    // }

    public function setChartType($type) {
        $this->chartType = $type;
        return $this;
    }

    public function setChartDatas($datas) {
        $this->chartDatas = $datas;
        return $this;

    }
    public function addChartDatas($datas) {
        if(!$this->chartDatas) {
            $this->chartDatas = [];
        }
        $this->chartDatas = array_merge($this->chartDatas, $datas);
        return $this;
    }
    
    public function addChartOptions($options) {
        $this->chartOptions = array_merge($this->chartOptions, $options);
        return $this;
    }
    public function setChartOptions($datas) {
        $this->chartOptions = $datas;
        return $this;
    }
    //
    public function addManualDataSet(string $label, array $data) {
        
        $dataSet = [
            'label' => $label,
            'data' => $data,
        ];
        //trace_log('Manual dataset');
        //trace_log($dataSet);

        $this->addDataSet($dataSet);
        return $this;
    }
    //
    public function addDataSet($_dataSet) {
        if(!$this->chartDatas) {
            $this->chartDatas = [];
        }
        $datasets = $this->chartDatas['datasets'] ?? false;
        if(!$datasets) {
            $this->chartDatas['datasets'] = [];
        }
        $twoAxis = false;
        if($this->chartType == 'bar_or_line_2_axis') {
            $twoAxis = true;
        }

        if($twoAxis) {
            $countDataSet = 0;
            if(is_countable($this->chartDatas['datasets'])) {
                $countDataSet =  count($this->chartDatas['datasets']);
            }
            $countDataSet += 1;
            $_dataSet['yAxisID'] = 'y_'. $countDataSet;
        }
        array_push($this->chartDatas['datasets'], $_dataSet);
        return $this; 
    }
    
    public function addlabels($labels) {
        if(!$this->chartDatas) {
            $this->chartDatas = [];
        }
        $this->chartDatas['labels'] = $labels;
        return $this; 
    }

    //ancien nom : createChart
    public function renderChart($width, $height)
    {
        $htm = $this->chartWidget->setChartType($this->chartType)
                ->setChartDatas($this->chartDatas)
                ->setChartOptions($this->chartOptions)
                ->create($width, $height);
        //trace_log($htm);
        return  $htm;
    }

    //ancien nom : createChartUrl
    public function getChartUrl($width, $height)
    {
        $htm = $this->chartWidget->setChartType($this->chartType)
                ->setChartDatas($this->chartDatas)
                ->setChartOptions($this->chartOptions)
                ->create($width, $height);

        //trace_log($htm);

        $tmpfile =  TmpFiles::createDirectory()->emptyFile("chart.jpeg");
        //trace_log($tmpfile->getFilePath());
        \SnappyImage::loadHTML($htm)
            ->setOption('width', $width)
            ->setOption('height', $height)
            ->setOption('enable-javascript', true)
            ->setOption('javascript-delay', 100)
            ->setOption('format', 'jpeg')
            ->save($tmpfile->getFilePath());
        //->inline();
        //trace_log($tmpfile->getFileUrl());
        return $tmpfile->getFileUrl();
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
        $chartDatas = [
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
        // $chartDatas = [
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
        // $chartDatas = [
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
        // $chartDatas = [
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

        return $this->createChart($chartDatas, $partial, $options, $width, $height);
    }
}
