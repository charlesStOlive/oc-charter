<?php namespace Waka\Charter\WakaRules\Asks;

use Waka\Charter\Classes\Rules\ChartBase;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use ApplicationException;
use Waka\Charter\Controllers\Charts;
use Waka\Utils\Interfaces\Ask as AskInterface;

class ChartPie extends ChartBase implements AskInterface
{

    /**
     * Returns information about this event, including name and description.
     */
    public function subFormDetails()
    {
        return [
            'name'        => 'Graphique PIE',
            'description' => 'Ajoute un champs HTML',
            'icon'        => 'icon-pie-chart',
            'premission'  => 'wcli.utils.ask.edit.admin',
            'show_attributes' => true,
            'outputs' => [
                'word_type' => 'HTM',
            ]
        ];
    }

    public function getText()
    {
        //trace_log('getText HTMLASK---');
        $hostObj = $this->host;
        //trace_log($hostObj->config_data);
        $title = $hostObj->config_data['title'] ?? null;
        if($title) {
            return $title;
        }
        return parent::getText();
    }
    
    /**
     * $modelSrc le Model cible
     * $context le type de contenu twig ou word
     * $dataForTwig un modèle en array fournit par le datasource ( avec ces relations parents ) 
     */

    public function resolve($modelSrc, $context = 'twig', $dataForTwig = []) {

        $model = $modelSrc;
        if($childModel = $this->getConfig('relation')) {
            $model = $this->getRelation($model, $childModel);
        }
        $srcLabels = $this->getConfig('src_labels');
        $srcCalculs = $this->getConfig('src_calculs');
        $calculsAttributes = $this->getConfig('calculs_attributes');
        $width =  $this->getConfig('width');
        $height = $this->getConfig('height');
        $title = $this->getConfig('title');

        $attributes = [
            'periode' => $calculsAttributes,
        ];

        
        $dataSet = $model->{$srcCalculs}($attributes);
        $labels = $model->{$srcLabels}($attributes);


        $options = [
            'type' => $this->getConfig('type'),
            // 'beginAtZero' => $attributes['beginAtZero'] ?? false,
            // 'color' => $attributes['color'],
        ];
        $datas = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $dataSet,
                    'label' => 'CA (N-1)',
                ],
            ],
        ];

        $chart = new Charts();
        $chart_url = $chart->setChartType('pie_or_doughnut')
                    ->addChartDatas($datas)
                    ->addChartOptions($options)
                    ->getChartUrl($width, $height);

        $finalResult = [
            'path' => $chart_url,
            'width' => $width,
            'height' => $height,
            'title' => $title,
        ];
        //trace_log($finalResult);
        return $finalResult;
    }
}
