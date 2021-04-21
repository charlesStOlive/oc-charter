<?php namespace Waka\Charter\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * ChartFormWidget Form Widget
 */
class ChartFormWidget extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'waka_charter_chart_form_widget';

    public $chartLabel = '';
    public $chartType = '';
    public $renderType = 'bar';
    public $beginAtZero = true;
    public $labels = [];
    public $dataSets = [];
    public $width = '400';
    public $height = '400';
    public $attributes = [];
    public $chartDatas;
    public $chartOptions;


    /**
     * @inheritDoc
     */
    public function init()
    {
        if ($this->formField->disabled) {
            $this->readOnly = true;
        }

        $this->fillFromConfig([
            'chartLabel',
            'chartType',
            'renderType',
            'labels',
            'dataSets',
            'beginAtZero',
            'attributes'
        ]);
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('chartformwidget');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $model = $this->model;
        $options = [
            'type' =>  $this->renderType,
            'id' => $this->getId(),
            'beginAtZero' => $this->beginAtZero,
        ];
        $fncLabels = $this->labels;
        $labels = $model->{$fncLabels}($this->attributes);
        $this->addLabels($labels);
        //trace_log($labels);
        foreach($this->dataSets as $rowDataSet) { 
            $dataSetFnc = $rowDataSet['fnc'] ?? '';
            $dataSetLabel = $rowDataSet['label'] ?? '';
            $dataSet = $model->{$dataSetFnc}($this->attributes);
            //trace_log($dataSet);
            $this->addManualDataSet($dataSetLabel, $dataSet);
        }
        //A fair en endernier en fonction des données les couleurs vont changer
        $this->setChartOptions($options);

        //trace_log($this->chartDatas);
        //trace_log($this->chartOptions);


        $this->vars['chartdata'] = json_encode($this->chartDatas);
        $this->vars['options'] = json_encode($this->chartOptions);
        $this->vars['width'] = $this->width . 'px';
        $this->vars['height'] = $this->height . 'px';
    }

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

    public function setChartOptions($options) {
        $this->createOptions($options);
        $this->addColorsOptions($options);
        return $this;
    }

    public function createOptions($options)
    {
        //trace_log($options);
        $this->chartOptions = [
            'type' => $options['type'] ?? 'bar',
            //ID pour affichage de plusieurs graphes dans une même page web. L'id est ajouté à créate options dans le chartFormWidget
            'id' => $options['id'] ?? null,
            'beginAtZero' => $options['beginAtZero'] ?? true,
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



    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addJs('/plugins/waka/charter/widgets/chartswidget/assets/js/wakachart.js');
        $this->addJs('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js'); 
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return \Backend\Classes\FormField::NO_SAVE_DATA;
    }
}
