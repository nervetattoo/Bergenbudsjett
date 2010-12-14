<?php

namespace app\controllers;

use \app\models\App;

class AppController extends \lithium\action\Controller {

    public $scripts = array(
        '/js/Models.js',
        '/js/BudgetView.js',
        '/js/app.js'
    );

	public function index() {
		return array();
	}
}
