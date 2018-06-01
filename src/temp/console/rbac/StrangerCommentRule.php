<?php
namespace console\rbac;

use yii\rbac\Rule;

/**
 * Only Stranger Comment and Last Comment can be replied
 */
class StrangerCommentRule extends Rule
{
    public $name = 'strangerComment';

    /**
     * @param string|integer $user_id the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user_id, $item, $params)
    {
        if(!isset($params['comment']))
            return true;
        return $params['comment']->last && $params['comment']->user_id != $user_id;
    }
}
