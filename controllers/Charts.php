<?php namespace Waka\Charter\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Waka\Charter\Models\Chart;

/**
 * Charts Back-end Controller
 */
class Charts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        '@Waka.Charter.Behaviors.ChartsBehavior',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Waka.Charter', 'charter', 'charts');
    }

}
