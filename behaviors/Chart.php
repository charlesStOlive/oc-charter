<?php namespace Waka\Charter\Behaviors;

use Backend\Classes\ControllerBehavior;

// use Redirect;
// use Waka\Mailer\Classes\MailCreator;
// use Waka\Mailer\Models\WakaMail;

class Chart extends ControllerBehavior
{
    public function __construct($controller)
    {
        parent::__construct($controller);
    }

    public function onCreateChart()
    {
    }
}