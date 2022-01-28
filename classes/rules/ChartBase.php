<?php namespace Waka\Charter\Classes\Rules;

use Waka\Utils\Classes\Rules\AskBase;
use Winter\Storm\Extension\ExtensionBase;
use Waka\Utils\Classes\DataSource;

/**
 * Notification ask base class
 *
 * @package waka\utils
 * @author Alexey Bobkov, Samuel Georges
 */
class ChartBase extends AskBase
{
    use \Waka\Utils\Classes\Traits\ScopePeriodes;

    private function getAllMethods($class) {
        $class = new \ReflectionClass($class);
        return $class->getMethods();
    }

    public function getFinalClass() {
        $class = $this->getDs()->class;
        if($childModel = $this->host->relation) {
            //trace_log('relation : '.$childModel.' class : '.$class);
            $finalClass = $this->getClassRelation($class, $childModel);
            $class = get_class($finalClass);
        }
        return $class;
    }


    public function getAllCalculs($class) {
        $methods = $this->getAllMethods($class);
        $labelMethods = [];
        foreach($methods as $method) {
            if(ends_with($method->name, 'DataSet')) {
                $labelMethods[$method->name] = $method->name;
            }
        }
        return $labelMethods;
    }

    public function getAllLabels($class) {
        $methods = $this->getAllMethods($class);
        $labelMethods = [];
        foreach($methods as $method) {
            if(ends_with($method->name, 'Labels')) {
                $labelMethods[$method->name] = $method->name;
            }
        }
        return $labelMethods;
    }

    public function getAllSeries($class) {
        $methods = $this->getAllMethods($class);
        $labelSeries = [];
        foreach($methods as $method) {
            if(ends_with($method->name, 'DataSetSeries')) {
                $labelSeries[$method->name] = $method->name;
            }
        }
        return $labelSeries;
    }

    

    public function listModelCalculs() {
        $class = $this->getFinalClass();
        return $this->getAllCalculs($class);
    }

    public function listModelLabels($class) {
        $class = $this->getFinalClass();
        return $this->getAllLabels($class); 
    }

    public function listModelSeries($class) {
        $class = $this->getFinalClass();
        return $this->getAllSeries($class); 
    }

    public function listAttributes() {
        $attributeMode = $this->host->attributesMode;
        //trace_log($attributeMode);
        if($attributeMode == "perso") {
            $class = $this->getFinalClass();
            $datas = new $class();
            // trace_log($this->host->{$this->host->attributesFunction}());
            // trace_log($this->host->attributesFunction);
            return $datas->{$this->host->attributesFunction}();
        } else {
            return $this->listPeriode();
            
        }

    }

    // public function filterFields($fields, $context = null) {
    //      if(isset($fields->attributesMode)) {
    //            //trace_log($fields->attributesMode->value);

    //     }
    // }

    public function getRelation($class, $relation) 
    {
        $relationExploded = explode('.', $relation);
        $finalClass;
        foreach($relationExploded as $key=>$subrelation) {
                $finalClass = $class->{$relation};
        }
        if(!$finalClass) {
            throw new \ApplicationException('relation non trouvé dans getRelation de chartBase');
        };
        return $finalClass;
    }

    public function getClassRelation($className, $relation) 
    {
        //trace_log('getRelation');
        if(!$relation) {
            return $className;
        }
        
        $relationExploded = explode('.', $relation);
        $class = new $className;
        //trace_log(get_class($class));
        $finalClass;
        foreach($relationExploded as $key=>$subrelation) {
                $finalClass = $class->{$relation}();
        }
        if(!$finalClass) {
            throw new \ApplicationException('relation non trouvé dans getRelation de chartBase');
        };
        return $finalClass->getRelated();
    }
    
}
