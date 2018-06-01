<?php

namespace sergmoro1\blog\tests\_support;

use sergmoro1\blog\tests\common\fixtures\UserFixture;
use sergmoro1\blog\tests\common\fixtures\PostFixture;
use sergmoro1\blog\tests\common\fixtures\CommentFixture;
use sergmoro1\blog\tests\common\fixtures\TagFixture;
use Codeception\Module;
use yii\test\FixtureTrait;
use yii\test\InitDbFixture;

/**
 * This helper is used to populate the database with needed fixtures before any tests are run.
 * In this example, the database is populated with the demo login user, which is used in acceptance
 * and functional tests.  All fixtures will be loaded before the suite is started and unloaded after it
 * completes.
 */
class FixtureHelper extends Module
{

    /**
     * Redeclare visibility because codeception includes all public methods that do not start with "_"
     * and are not excluded by module settings, in actor class.
     */
    use FixtureTrait {
        loadFixtures as protected;
        fixtures as protected;
        globalFixtures as protected;
        unloadFixtures as protected;
        getFixtures as protected;
        getFixture as protected;
    }

    /**
     * Method called before any suite tests run. Loads User fixture login user
     * to use in acceptance and functional tests.
     * @param array $settings
     */
    public function _beforeSuite($settings = [])
    {
        $this->loadFixtures();
    }

    /**
     * Method is called after all suite tests run
     */
    public function _afterSuite()
    {
        $this->unloadFixtures();
    }

    /**
     * @inheritdoc
     */
    public function globalFixtures()
    {
        return [
            InitDbFixture::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@vendor/sergmoro1/yii2-blog-tools/src/tests/common/fixtures/data/init_login.php',
            ],
            'post' => [
                'class' => PostFixture::className(),
                'dataFile' => '@vendor/sergmoro1/yii2-blog-tools/src/tests/common/fixtures/data/post.php',
            ],
            'comment' => [
                'class' => CommentFixture::className(),
                'dataFile' => '@vendor/sergmoro1/yii2-blog-tools/src/tests/common/fixtures/data/comment.php',
            ],
            'tag' => [
                'class' => TagFixture::className(),
                'dataFile' => '@vendor/sergmoro1/yii2-blog-tools/src/tests/common/fixtures/data/tag.php',
            ],
        ];
    }
}
