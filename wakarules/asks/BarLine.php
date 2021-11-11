<?php namespace Waka\Charter\WakaRules\Asks;

use Waka\Charter\Classes\Rules\ChartBase;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use ApplicationException;
use Waka\Charter\Controllers\Charts;

class BarLine extends ChartBase
{
    public $jsonable = [];
    /**
     * Returns information about this event, including name and description.
     */
    public function askDetails()
    {
        return [
            'name'        => 'Graphique en barre ou ligne',
            'description' => 'Accèpte multiples dataSet',
            'icon'        => 'icon-pie-chart',
            'premission'  => 'wcli.utils.ask.edit.admin',
            'show_attributes' => true,
            'word_type' => 'IMG',
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
        //trace_log("resolve");
        //trace_log(get_class($model));
        //trace_log($model->name);

        
        $dataSet1 = $model->{$src_calculs}($attributes1);
        $dataSet2 = $model->{$src_calculs}($attributes2);
        $labels = $model->{$srcLabels}($attributes1);


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
        //trace_log($labels);
        //trace_log($dataSet1);
        //trace_log($src_1_label);
        //trace_log($dataSet2);
        //trace_log($src_2_label);
        //trace_log($options);
        //trace_log($width);
        //trace_log($height);

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
