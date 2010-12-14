<?php

namespace app\controllers;

use \app\models\Post;
use \app\models\Group;
use \app\models\Grant;
use \app\models\GrantGroup;

class BudgetController extends \lithium\action\Controller {

	public function groups() {
        $groups = Group::find('all', array(
            'conditions' => array(
                'y2011' => array('$gte' => 0)
            ),
            'order' => array(
                'name' => 'asc'
            )
        ))->to('array');

        $total = 0;
        foreach ($groups as $group) {
            $total += $group['y2011'];
        }
        foreach ($groups as &$group) {
            $group['percentage'] = round($group['y2011'] / $total, 4);
        }

        return compact('total', 'groups');
	}
}
