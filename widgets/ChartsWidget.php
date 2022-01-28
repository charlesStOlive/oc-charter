<?php namespace Waka\Charter\Widgets;

use Backend\Classes\WidgetBase;

class ChartsWidget extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'chart';

    public $chartDatas;
    public $chartType;
    public $options;
    public $width;
    public $height;
    public $type;
    public $periode;

    public function setChartType($type) {
        $this->chartType = $type;
        return $this;
    }
    public function setChartDatas($datas) {
        $this->chartDatas = $datas;
        return $this; 
    }
    
    public function setChartOptions($options) {
        $this->createOptions($options);
        $this->addColorsOptions($options);
        return $this;
    }

    public function create($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        return $this->render();
    }

    public function render()
    {

        $this->vars['chartID'] = $this->options['id'] ?? null;
        $this->vars['chartdata'] = json_encode($this->chartDatas);
        $this->vars['options'] = json_encode($this->options);
        $this->vars['width'] = $this->width . 'px';
        $this->vars['height'] = $this->height . 'px';
        //trace_log($this->chartType);
        //trace_log(json_encode($this->chartDatas));
        //trace_log(json_encode($this->options));
        //trace_log($this->width . 'px');
        //trace_log($this->height . 'px');

        return $this->makePartial($this->chartType);
    }

    public function createOptions($options)
    {
        trace_log($options);
        $this->options = [
            'type' => $options['type'] ?? 'bar',
            //ID pour affichage de plusieurs graphes dans une même page web. L'id est ajouté à créate options dans le chartFormWidget
            'id' => $options['id'] ?? null,
            'beginAtZero' => $options['beginAtZero'] ?? 1,
            'cutoutPercentage' => $options['cutoutPercentage'] ?? 0,
        ];
        
    }

    public function addColorsOptions($options)
    {
        $degrade = $options['degrade'] ?? false;
        $dataSets = $this->chartDatas['datasets'];
        $nbDataSets = count($dataSets);

        $type = $options['type'] ?? 'bar';

        if ($this->chartType == 'pie_or_doughnut') {
            $nbValue = count($this->chartDatas['labels']);
            //trace_log($nbValue);
            $colors = \Waka\Utils\Classes\PhpColors::getSeparate($nbValue);
            $this->chartDatas['datasets'][0]['backgroundColor'] = $colors;
        } else {
            $colors = \Waka\Utils\Classes\PhpColors::getSeparate($nbDataSets);
            $i = 0;

            foreach ($dataSets as $key => $dataset) {
                if ($type == "bar") {
                    $this->chartDatas['datasets'][$key]['backgroundColor'] = $colors[$i];
                } else {
                    $this->chartDatas['datasets'][$key]['borderColor'] = $colors[$i];
                }
                $i++;
            }
        }
    }
}
