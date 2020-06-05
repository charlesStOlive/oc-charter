<?php namespace Waka\Charter\Models;

use Model;

/**
 * Chart Model
 */
class Chart extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'waka_charter_charts';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = ['config'];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [
        'charteable' => [],
    ];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function makeUrl($data)
    {
        $url = \Twig::parse($this->config, compact('data'));
        //trace_log($url);
        $url = urlencode(preg_replace("/\r|\n/", "", $url));
        return "https://quickchart.io/chart?bkg=white&c=" . $url;
    }

    public function afterSave()
    {
        $this->test();
    }

    public function test()
    {
        $data = [
            'title' => 'Attention ce sont de fausses données',
            'subtitle' => "l'app n'a pas reçu de données",
            'width' => 600,
            'textyaxis' => null,
            'labels' => ['m1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm8'],
            'dataSets' => [
                [
                    'name' => "serie 1",
                    'data' => [43934, 52503, 57177, 69658, 97031, 119931, 137133, 140000],
                ],
                [
                    'name' => "serie 2",
                    'data' => [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434],
                ],
                [
                    'name' => "serie 3",
                    'data' => [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387],
                ],
            ],
        ];

        $url = \Twig::parse($this->config, compact('data'));
        //trace_log($url);
        $url = preg_replace('/\s+/S', "", $url);
        //$url = str_replace("++", "", $url);

        //trace_log("https://quickchart.io/chart?bkg=white&c=" . $url);

        //trace_log($this->config['type']);
    }

}
