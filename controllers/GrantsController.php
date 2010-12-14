<?php

namespace app\controllers;

use \app\models\Post;
use \app\models\Group;
use \app\models\Grant;
use \app\models\GrantGroup;

use lithium\data\Connections;

class GrantsController extends \lithium\action\Controller {

	public function byPost($id = false) {
        $id = ($id) ?: $this->request->query['id'];
        if ($id) {
            $grants = Grant::find('all', array(
                'conditions' => array(
                    'post' => (int) $id
                ),
                'limit' => 100
            ));
            return compact('grants');
        }
	}

	public function byGroup($id = false) {
        $id = ($id) ?: $this->request->query['id'];
        if ($id) {
            $grants = Grant::find('all', array(
                'conditions' => array(
                    'group' => new MongoId($id)
                ),
                'limit' => 100
            ));
            return compact('grants');
        }
	}
}
