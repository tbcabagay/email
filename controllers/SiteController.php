<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\EmailForm;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new EmailForm();

        if ($model->load(Yii::$app->request->post()) && $model->send()) {
            return $this->refresh();
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
}
