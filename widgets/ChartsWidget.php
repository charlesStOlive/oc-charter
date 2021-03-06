<?php namespace Waka\Charter\Widgets;

use Backend\Classes\WidgetBase;

class ChartsWidget extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'chart';

    public $chartdata;
    public $partial;
    public $options;
    public $width;
    public $height;
    public $type;

    public function create($chartdata, $partial, $options, $width, $height)
    {
        $this->width = $width;
        $this->height = $height;

        $this->partial = $partial;

        $this->createOptions($options);
        $this->chartdata = $chartdata;
        $this->addColorsOptions($options);

        return $this->render();
    }

    public function render()
    {

        $this->vars['options'] = json_encode($this->options);
        $this->vars['chartdata'] = json_encode($this->chartdata);
        $this->vars['width'] = $this->width . 'px';
        $this->vars['height'] = $this->height . 'px';

        if ($this->type == "doughnut") {
            return $this->makePartial('pie');
        } else {
            return $this->makePartial($this->partial);
        }
    }

    public function createOptions($options)
    {
        //trace_log($options);
        $this->options = [
            'type' => $options['type'] ?? 'bar',
            'beginAtZero' => $options['beginAtZero'] ?? true,
            'cutoutPercentage' => $options['cutoutPercentage'] ?? 0,
        ];
    }

    public function addColorsOptions($options)
    {
        $degrade = $options['degrade'] ?? false;

        $dataSets = $this->chartdata['datasets'];
        $nbDataSets = count($dataSets);

        $type = $options['type'] ?? 'bar';

        if ($this->partial == 'pie_or_doughnut') {
            $nbValue = count($this->chartdata['labels']);
            //trace_log($nbValue);
            $colors = \Waka\Utils\Classes\PhpColors::getSeparate($nbValue);
            $this->chartdata['datasets'][0]['backgroundColor'] = $colors;
        } else {
            $colors = \Waka\Utils\Classes\PhpColors::getSeparate($nbDataSets);
            $i = 0;

            foreach ($dataSets as $key => $dataset) {
                if ($type == "bar") {
                    $this->chartdata['datasets'][$key]['backgroundColor'] = $colors[$i];
                } else {
                    $this->chartdata['datasets'][$key]['borderColor'] = $colors[$i];
                }
                $i++;
            }
        }
    }

    public function loadAssets()
    {
        $this->addJs('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js');
    }
}
