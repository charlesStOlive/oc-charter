<?php namespace Waka\Charter\WakaRules\Asks;

use Waka\Charter\Classes\Rules\ChartBase;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use ApplicationException;
use Waka\Charter\Controllers\Charts;
use Waka\Utils\Interfaces\Ask as AskInterface;

class BarLine extends ChartBase implements AskInterface
{
    public $jsonable = [];
    /**
     * Returns information about this event, including name and description.
     */
    public function subFormDetails()
    {
        return [
            'name'        => 'Graphique en barre ou ligne',
            'description' => 'Accèpte multiples dataSet',
            'icon'        => 'icon-pie-chart',
            'premission'  => 'wcli.utils.ask.edit.admin',
            'ask_emit'    => 'richeditor',
            'show_attributes' => false,
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
        //trace_log('resolve --');
        $model = $modelSrc;
        if($childModel = $this->getConfig('relation')) {
            $model = $this->getRelation($model, $childModel);
        }
        $src_1_label = $this->getConfig('src_1_label');
        $src_1_att = $this->getConfig('src_1_att');
        $src_2_label = $this->getConfig('src_2_label');
        $src_2_att = $this->getConfig('src_2_att');
        //
        $srcLabels = $this->getConfig('src_labels');
        $src_calculs = $this->getConfig('src_calculs');
        $width =  $this->getConfig('width');
        $height = $this->getConfig('height');
        $title = $this->getConfig('title');

        $attributes1 = [
            'periode' => $src_2_att,
        ];
         $attributes2 = [
            'periode' => $src_1_att,
        ];

        $dataSet1 = [];
        $dataSet2 = [];
        $labels = [];

        //Préparation des label si pas overridé par thuis->labels
        $labelsTemp = null;


        if(method_exists($model, $src_calculs)) {
            $dataSet1 = $model->{$src_calculs}($attributes1);
            $dataSet1 = array_values($dataSet1);
            $dataSet2 = $model->{$src_calculs}($attributes2);
            $labelsTemp = array_keys($dataSet2);
            $dataSet2 = array_values($dataSet2);
            
        } 
        
        $labels;
        if($srcLabels) {
            if(method_exists($model, $srcLabels)) {
                $labels = $model->{$srcLabels}($attributes1);
            }
        } else {
            $labels = $labelsTemp;
        };

        $options = [
            'type' => $this->getConfig('type'),
            'beginAtZero' => false,
            // 'color' => $attributes['color'],
        ];
        $datas = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $dataSet1,
                    'label' => $src_1_label,
                ],
                [
                    'data' => $dataSet2,
                    'label' => $src_2_label,
                ],
            ],
        ];

        //trace_log($datas);


        $chart = new Charts();
        $chart_url = $chart->setChartType('bar_or_line')
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
