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
            ),
            'limit' => 8
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

    public function postsAndGrants() {
        $id = $this->request->query['id'];
        // First find all posts for group
        $posts = Post::find('all', array(
            'conditions' => array(
                'groupId' => new \MongoId($id)
            ),
            'limit' => 10
        ))->to('array');

        $ids = array();
        $total = 0;
        foreach ($posts as &$p) {
            $total += $p['y2011'];
            /*
            $post = $p['post'];
            $ids[] = $post;
             */
        }

        /*
        $grants = Grant::find('all', array(
            'conditions' => array(
                'post' => array(
                    '$in' => $ids
                )
            ),
            'limit' => 100
        ))->to('array');
         */
        return compact('total', 'posts');
    }
}
