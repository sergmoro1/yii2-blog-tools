<?php
namespace console\controllers;
 
use yii\console\Controller;
use console\rbac\PostModeratorRule;
use console\rbac\OwnCommentRule;
use console\rbac\OwnAnswerRule;
use console\rbac\OwnProfileRule;
use console\rbac\UserGroupRule;
 
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        // delete previous /console/rbac/items.php, /console/rbac/rules.php
        $auth->removeAll();
 
        // Create permissions
        
        // Create simple, based on action{$NAME} permissions
        $index  = $auth->createPermission('index');
        $create = $auth->createPermission('create');
        $update = $auth->createPermission('update');
        $delete = $auth->createPermission('delete');
 
        $auth->add($index);
        $auth->add($create);
        $auth->add($update);
        $auth->add($delete);

        // Post
        $viewPost = $auth->createPermission('viewPost');
        $createPost = $auth->createPermission('createPost');
        $changePostStatus = $auth->createPermission('changePostStatus');

        $auth->add($viewPost);
        $auth->add($createPost);
        $auth->add($changePostStatus);
        
        // Rule- Post Moderator
        $postModerator = new PostModeratorRule();
        $auth->add($postModerator);

        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->ruleName = $postModerator->name;
        $auth->add($updateOwnPost);

        $auth->addChild($updateOwnPost, $update);
        
        // Comment
        $replyComment = $auth->createPermission('replyComment');

        $auth->add($replyComment);

        // Rule - Comments Only For Own Posts
        $ownComment = new OwnCommentRule();
        $auth->add($ownComment);

        $replyOwnComment = $auth->createPermission('replyOwnComment');
        $replyOwnComment->ruleName = $ownComment->name;
        $auth->add($replyOwnComment);

        $auth->addChild($replyOwnComment, $replyComment);

        // Rule - Own Answers For Comments
        $ownAnswer = new OwnAnswerRule();
        $auth->add($ownAnswer);

        $updateOwnAnswer = $auth->createPermission('updateOwnAnswer');
        $updateOwnAnswer->ruleName = $ownAnswer->name;
        $auth->add($updateOwnAnswer);

        $auth->addChild($updateOwnAnswer, $update);

        // User

        // Rule - Own Answers For Comments
        $ownProfile = new OwnProfileRule();
        $auth->add($ownProfile);

        $updateOwnProfile = $auth->createPermission('updateOwnProfile');
        $updateOwnProfile->ruleName = $ownProfile->name;
        $auth->add($updateOwnProfile);

        $auth->addChild($updateOwnProfile, $update);

        // Settings

        $gear = $auth->createPermission('gear');
        $gear->description = 'Change Settings';
        $auth->add($gear);

        // Role
        
        // Rule - User Group
        $group = new UserGroupRule();
        $auth->add($group);

        $author = $auth->createRole('author');
        $author->ruleName  = $group->name;
        $auth->add($author);

        $admin = $auth->createRole('admin');
        $admin->ruleName  = $group->name;
        $auth->add($admin);
 
        // Author
        $auth->addChild($author, $index);
        $auth->addChild($author, $createPost);
        $auth->addChild($author, $viewPost);
        $auth->addChild($author, $updateOwnPost);
        $auth->addChild($author, $replyOwnComment);
        $auth->addChild($author, $updateOwnAnswer);
        $auth->addChild($author, $updateOwnProfile);
        
        // Admin
        $auth->addChild($admin, $replyComment);
        $auth->addChild($admin, $changePostStatus);
        $auth->addChild($admin, $create);
        $auth->addChild($admin, $update);
        $auth->addChild($admin, $delete);
        $auth->addChild($admin, $gear);
        // Admin can all that can Author
        $auth->addChild($admin, $author);
    }
}
