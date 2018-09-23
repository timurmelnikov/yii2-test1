<?php

namespace app\controllers;

use app\helpers\TreeFormater;
use Yii;
use yii\web\Controller;
use app\models\GenerateForm;
use app\models\Tree;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $form = new GenerateForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            Tree::generateData($form->numberOfNodes);
        }
        $data = \json_encode(Tree::findDefault(), JSON_UNESCAPED_UNICODE);

        return $this->render('index', ['model' => $form, 'data' => $data]);
    }

    public function actionError()
    {
        return $this->redirect('/');
    }

    public function actionThread($ids)
    {
        $form = new GenerateForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            Tree::generateData($form->numberOfNodes);
        }
        $data = \json_encode(Tree::findAllByThread($ids), JSON_UNESCAPED_UNICODE);

        return $this->render('index', ['model' => $form, 'data' => $data]);
    }
}
