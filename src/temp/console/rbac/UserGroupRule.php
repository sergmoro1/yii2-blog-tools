<?php
namespace console\rbac;
 
use Yii;
use yii\rbac\Rule;
use common\models\User;
 
class UserGroupRule extends Rule
{
    public $name = 'userGroup';
 
    public function execute($user_id, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $group = Yii::$app->user->identity->group;
            if ($item->name === 'admin') {
                return $group == User::GROUP_ADMIN;
            } elseif ($item->name === 'author') {
                 return $group == User::GROUP_ADMIN || $group == User::GROUP_AUTHOR;
            } elseif ($item->name === 'commentator') {
                 return $group == User::GROUP_ADMIN || $group == User::GROUP_AUTHOR || $group == User::GROUP_COMMENTATOR;
            }
        }
        return false;
    }
}
