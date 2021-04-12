<?php namespace Waka\Charter\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Waka\Charter\Controllers\Charts;

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

        

        $chart = new Charts();
        $chart = $chart->setChartType($this->chartType);
        
        $fncLabels = $this->labels;
        $labels = $model->{$fncLabels}($this->attributes);
        //trace_log($labels);
        
        $chart = $chart->addLabels($labels);
        

        $dataSets = [];

        foreach($this->dataSets as $rowDataSet) { 
            $dataSetFnc = $rowDataSet['fnc'] ?? '';
            $dataSetLabel = $rowDataSet['label'] ?? '';
            $dataSet = $model->{$dataSetFnc}($this->attributes);
            //trace_log($dataSet);
            $chart = $chart->addManualDataSet($dataSetLabel, $dataSet);
        }

        $chartHtm = $chart->addChartOptions($options)->renderChart($this->width, $this->height);
        
        $this->vars['chartHtm'] = $chartHtm;
        
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        // $this->addCss('css/chartformwidget.css', 'waka.charter');
        // $this->addJs('js/chartformwidget.js', 'waka.charter');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return \Backend\Classes\FormField::NO_SAVE_DATA;
    }
}
