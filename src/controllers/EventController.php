<?php
namespace sergmoro1\blog\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use sergmoro1\modal\controllers\ModalController;
use sergmoro1\blog\models\Event;
use sergmoro1\blog\models\EventSearch;

class EventController extends ModalController
{
	public $modelName = 'Event';
    public function newModel() { return new Event(); }
    public function newSearch() { return new EventSearch(); }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function fillin($model, $update = true)
    {
		if($update)
		{
			$model->begin_date = date('d.m.Y', $model->begin);
			$model->end_date = date('d.m.Y', $model->end);
		} else {
			$model->begin_date = date('d.m.Y', time());
			$model->end_date = date('d.m.Y', time() + 3600 * 24);
		}
		return $model;
	}

    /**
     * Finds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
