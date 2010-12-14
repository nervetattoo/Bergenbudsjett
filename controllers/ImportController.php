<?php

namespace app\controllers;

use \app\models\Post;
use \app\models\Group;
use \app\models\Grant;
use \app\models\GrantGroup;

use lithium\data\Connections;

class ImportController extends \lithium\action\Controller {

	public function index() {
        $fh = fopen("../resources/primary.csv", "r");
        $posts = $groups = 0;
        $connection = Connections::get('default')->connection;
        foreach (array('posts', 'groups', 'grants', 'grant_groups') as $collection) {
            $connection->$collection->remove(array());
        }

        while ($data = fgetcsv($fh)) {
            list($tmp, $postId, $desc, $y2011, $y2010, $y2009) = $data;

            if (!empty($tmp))
            {
                /**
                 * Keep track of group name
                 */
                if (strpos($tmp, "Totalt") === false)
                {
                    $group = Group::create();
                    list($number, $name) = explode(" - ", $tmp);
                    $name = str_replace('"', "", trim($name));
                    $number = trim($number);
                    $group->save(array(
                        'name' => $name,
                        'number' => $number
                    ));
                    $groupId = $group->_id;
                    $groups++;
                }
                elseif ($group)
                {
                    $group->y2011 = (int) ($y2011 / 1000);
                    $group->y2010 = (int) ($y2010 / 1000);
                    $group->y2009 = (int) ($y2009 / 1000);
                    $group->save();
                    continue;
                }
            }

            /**
             * Place in previously mentioned group
             */
            if (isset($groupId)) {
                $post = Post::create();
                $post->save(array(
                    'post' => (float) $postId,
                    'desc' => trim($desc),
                    'y2011' => (int) ($y2011 / 1000),
                    'y2010' => (int) ($y2010 / 1000),
                    'y2009' => (int) ($y2009 / 1000),
                    'groupId' => $groupId
                ));
                $posts++;
            }
        }

        // Grants
        return compact('groups', 'posts') + $this->grants();
	}

	public function grants() {
        $fh = fopen("../resources/grants.csv", "r");
        $grants = 0;
        $currentPost = false;
        $currentName = false;
        while ($data = fgetcsv($fh)) {
            $grants++;
            list($post, $groupName, $desc, $y2011, $y2010, $change) = $data;

            /**
             * What post to file this udner
             */
            if (is_numeric($post))
            {
                $currentPost = (float) $post;
                $currentName = $groupName;
                // Store grant group
                $grantGroup = GrantGroup::create();
                $grantGroup->save(array(
                    'post' => $currentPost,
                    'desc' => $groupName
                ));
            }

            /**
             * Assert we are under a post before starting to store
             */
            if ($currentPost)
            {
                /**
                 * Place in previously mentioned group
                 */
                $grant = Grant::create();
                $grant->save(array(
                    'post' => $currentPost,
                    'name' => $currentName,
                    'group' => $grantGroup->_id,
                    'desc' => $desc,
                    'y2011' => (int) ($y2011 / 1000),
                    'y2010' => (int) ($y2010 / 1000),
                    'change' => (int) ($change / 1000)
                ));
            }
        }

        return compact('grants');
	}
}
