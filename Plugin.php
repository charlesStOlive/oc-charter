<?php namespace Waka\Charter;

use Backend;
use System\Classes\PluginBase;

/**
 * Charter Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Charter',
            'description' => 'No description provided yet...',
            'author' => 'Waka',
            'icon' => 'icon-leaf',
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        //todo
    }

    public function registerFormWidgets(): array
    {
        return [
            'Waka\Charter\FormWidgets\ChartFormWidget' => 'chartform',
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Waka\Charter\Components\MyComponent' => 'myComponent',
        ];
    }


    public function registerWakaRules()
    {
        return [
            'asks' => [
                ['\Waka\Charter\WakaRules\Asks\ChartPie'], 
                ['\Waka\Charter\WakaRules\Asks\BarLine'],
            ],
            'fncs' => [
            ],
            'conditions' => [
            ]
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'waka.agg.admin.super' => [
                'tab' => 'Waka - Graphiques',
                'label' => 'Super Administrateur des graphiques',
            ],
            'waka.agg.admin.base' => [
                'tab' => 'Waka - Graphiques',
                'label' => 'Administrateur des graphiques',
            ],
            'waka.agg.user' => [
                'tab' => 'Waka - Graphiques',
                'label' => 'Utilisateur des graphiques',
            ],
        ];
    }


    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'charter' => [
                'label' => 'Charter',
                'url' => Backend::url('waka/charter/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['waka.charter.*'],
                'order' => 500,
            ],
        ];
    }
    // public function registerSettings()
    // {
    //     return [
    //         'charts' => [
    //             'label' => Lang::get('waka.charter::lang.menu.charts'),
    //             'description' => Lang::get('waka.charter::lang.menu.charts_description'),
    //             'category' => Lang::get('waka.charter::lang.menu.settings_category'),
    //             'icon' => 'icon-bar-chart',
    //             'url' => Backend::url('waka/charter/charts'),
    //             'permissions' => ['waka.charter.admin.*'],
    //             'order' => 1,
    //         ],
    //     ];
    // }
}
