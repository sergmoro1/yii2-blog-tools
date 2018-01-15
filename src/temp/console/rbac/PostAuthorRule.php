<?php
namespace console\rbac;

use yii\rbac\Rule;

/**
 * Checks if post.author_id matches user_id passed via params
 */
class PostAuthorRule extends Rule
{
    public $name = 'postAuthor';

    /**
     * @param string|integer $user_id the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user_id, $item, $params)
    {
		return isset($params['post']) 
			? $params['post']->author_id == $user_id
			: true;
    }
}
