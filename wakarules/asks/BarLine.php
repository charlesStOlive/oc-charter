<?php namespace Waka\Charter\WakaRules\Asks;

use Waka\Charter\Classes\Rules\ChartBase;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use ApplicationException;
use Waka\Charter\Controllers\Charts;
use Waka\Utils\Interfaces\Ask as AskInterface;
use Waka\Pdfer\Classes\MakeShot;

class BarLine extends ChartBase implements AskInterface
{
    public $jsonable = ['datas'];
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
        //trace_log('resolve --'.$context);
        $model = $modelSrc;
        if($childModel = $this->getConfig('relation')) {
            $model = $this->getRelation($model, $childModel);
        }
        //
        $src_calculs = $this->getConfig('src_calculs');
        $series = $this->host->getConfig('datas');
        //trace_log($series);

        $width =  $this->getConfig('width');
        $height = $this->getConfig('height');
        $title = $this->getConfig('title');
        $srcLabels = $this->getConfig('srcLabels');

        $datas = [
            'datasets' => [],
        ];

        $options = [
            'type' => $this->getConfig('type'),
            'beginAtZero' => true,
            // 'color' => $attributes['color'],
        ];

        // $attribute
        $i=0;
        $labelsTemp;
        foreach($series as $serie) {
            $serieAttribute = $serie['src_att'];
            // Cas des attributs période
            $attribute = ['periode' => $serieAttribute];
            $dataFromModel = $model->{$src_calculs}($attribute);
            $serieData = [
                'label' => $serie['src_label'],
                'data' =>  array_values($dataFromModel),
            ];
            $labelsTemp = array_keys($dataFromModel);
            $datas['datasets'][$i] = $serieData;
            //chart.js aime  les 0 contrairement a powerpoint...
            $i++;
        }

        $labels;
        if($srcLabels) {
            if(method_exists($model, $srcLabels)) {
                $labels = $model->{$srcLabels}($attributes1);
            }
        } else {
            $labels = $labelsTemp;
        };

        $datas['labels'] = $labels;

        $options = [
            'type' => $this->getConfig('type'),
            'beginAtZero' => $this->getConfig('beginAtZero'),
            // 'color' => $attributes['color'],
        ];

        $chartHtm = new Charts();
        $chartHtm = $chartHtm->setChartType('bar_or_line')
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

            //trace_log($pictureUrl);

            $finalResult = [
                'width' => $width,
                'height' => $height,
                'title' => $title,
                'path' => $pictureUrl,
            ];
            return $finalResult;
        }

        
    }
}
