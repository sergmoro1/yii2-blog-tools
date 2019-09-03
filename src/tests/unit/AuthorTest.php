<?php
namespace sergmoro1\blog\tests\unit;

use sergmoro1\blog\models\Author;

class AuthorTest extends \Codeception\Test\Unit
{
    /**
     * @var \tests\codeception\backend\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $this->tester->haveFixtures([
            'authors' => [
                'class' => \sergmoro1\blog\tests\common\fixtures\AuthorFixture::className(),
                'dataFile' => '@vendor/sergmoro1/yii2-blog-tools/src/tests/common/fixtures/data/author.php',
            ]
        ]);
    }
    
    public function testCreate() {
        foreach ($this->tester->grabFixture('authors') as $author) {
            $model = new Author($author);
            $this->tester->expect($model->save());
        }
        $this->tester->seeInDatabase('author', ['name' => 'Sergey Morozov']);
        $model = new Author();
        $this->tester->assertCount(2, $model->getAll());
    }
}
