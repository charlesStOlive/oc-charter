<?php namespace Waka\Charter\WakaRules\Asks;

use Waka\Charter\Classes\Rules\ChartBase;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use ApplicationException;
use Waka\Charter\Controllers\Charts;
use Waka\Utils\Interfaces\Ask as AskInterface;
use Waka\Pdfer\Classes\MakeShot;

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
        trace_log('resolve --'.$context);
        $model = $modelSrc;
        if($childModel = $this->getConfig('relation')) {
            $model = $this->getRelation($model, $childModel);
        }
        //
        $src_calculs = $this->getConfig('src_calculs');
        $src_label = $this->getConfig('src_label');
        $src_att = $this->getConfig('src_att');
        //trace_log($series);

        $width =  $this->getConfig('width');
        $height = $this->getConfig('height');
        $title = $this->getConfig('title');
        $srcLabels = $this->getConfig('srcLabels');

         $dataFromModel = [];
        if(method_exists($model, $src_calculs)) {
            $attribute = ['periode' => $src_att];
            $dataFromModel = $model->{$src_calculs}($attribute);
        }
        $dataSet1 = array_values($dataFromModel);
        $dataSet1Labels = array_keys($dataFromModel);
        $labelsTemp = array_keys($dataFromModel);
        
        $labels;
        if($srcLabels) {
            if(method_exists($model, $srcLabels)) {
                $attribute = ['periode' => $src_att];
                $labels = $model->{$srcLabels}($attribute);
            }
        } else {
            $labels = $labelsTemp;
        };


        $options = [
            'type' => $this->getConfig('type'),
            'beginAtZero' => $attributes['beginAtZero'] ?? false,
            // 'color' => $attributes['color'],
        ];
        

        $datas = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $dataSet1,
                    'label' => $dataSet1Labels,
                ],
            ],
        ];

        $chartHtm = new Charts();
        $chartHtm = $chartHtm->setChartType('pie_or_doughnut')
                    ->addChartDatas($datas)
                    ->addChartOptions($options)
                    ->renderChart($width, $height);

        if($context == 'twig') {

            $finalResult = [
                'width' => $width,
                'height' => $height,
                'title' => $title,
                'htm' => $chartHtm,
            ];
            return $finalResult;
            
        }
        else {
            $pictureUrl = MakeShot::htm($chartHtm, $width, $height);

            trace_log($pictureUrl);

            $finalResult = [
                'width' => $width,
                'height' => $height,
                'title' => $title,
                'url' => $pictureUrl,
            ];
            return $finalResult;
        }
    }
}
