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
                    $group->save(array(
                        'name' => $tmp
                    ));
                    $groupId = $group->_id;
                    $groups++;
                    unset($group);
                }
                else
                    continue;
            }

            /**
             * Place in previously mentioned group
             */
            if (isset($groupId)) {
                $post = Post::create();
                $post->save(array(
                    'post' => (int) $postId,
                    'desc' => trim($desc),
                    'y2011' => (int) $y2011,
                    'y2010' => (int) $y2010,
                    'y2009' => (int) $y2009,
                    'groupId' => $groupId
                ));
                $posts++;
            }
        }

        // Grants
        $ret = compact('groups', 'posts') + $this->grants();

        return $ret;
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
                $currentPost = (int) $post;
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
                    'y2011' => (int) $y2011,
                    'y2010' => (int) $y2010,
                    'change' => (int) $change
                ));
            }
        }

        return compact('grants');
	}
}
