<?php

namespace app\controllers;

use \app\models\Post;
use \app\models\Group;
use \app\models\Grant;
use \app\models\GrantGroup;

class BudgetController extends \lithium\action\Controller {

	public function groups() {
        $groups = Group::find('all', array(
            'order' => array(
                'name' => 'asc'
            )
        ));
        return compact('groups');
	}
}
