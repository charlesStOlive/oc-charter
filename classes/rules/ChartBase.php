<?php namespace Waka\Charter\Classes\Rules;

use Waka\Utils\Classes\Rules\AskBase;
use Winter\Storm\Extension\ExtensionBase;
use Waka\Utils\Classes\DataSource;
use Waka\Utils\Interfaces\Ask as AskInterface;

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
    public function getAllCalculs($class) {
        $methods = $this->getAllMethods($class);
        $labelMethods = [];
        foreach($methods as $method) {
            if(starts_with($method->name, 'getCc')) {
                $labelMethods[$method->name] = $method->name;
            }
        }
        return $labelMethods;
    }

    public function getAllLabels($class) {
        $methods = $this->getAllMethods($class);
        $labelMethods = [];
        foreach($methods as $method) {
            if(starts_with($method->name, 'getLb')) {
                $labelMethods[$method->name] = $method->name;
            }
        }
        return $labelMethods;
    }

    public function listModelCalculs() {
        //trace_log('listModelCalculs');
        $class = $this->getDs()->class;
        //trace_log();
        if($childModel = $this->host->relation) {
            //trace_log('relation : '.$childModel.' class : '.$class);
            $finalClass = $this->getClassRelation($class, $childModel);
            $class = get_class($finalClass);
        }
        //trace_log(get_class($class));
        return $this->getAllCalculs($class);
    }

    public function listModelLabels($class) {
        //trace_log('listModelLabels');
        $class = $this->getDs()->class;
        if($childModel = $this->host->relation) {
            //trace_log('relation : '.$childModel.' class : '.$class);
            $finalClass = $this->getClassRelation($class, $childModel);
            $class = get_class($finalClass);
            
        }
        //trace_log(get_class($class));
        return $this->getAllLabels($class);

         
    }

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
