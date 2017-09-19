<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

/**
 * Class ChatController
 * @package app\controllers
 */
class ChatController extends Controller
{
    /**
     * Default layout
     *
     * @var string
     */
    public $layout = 'chat';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'list' => ['get'],
                    'add' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $title = 'Simple Chat';
        $subtitle = 'Recent chat messages';
        return $this->render('index', compact('title','subtitle'));
    }

    /**
     * Ge messages action
     *
     * @return array
     */
    public function actionList() {

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'search' => $data,
                'code' => 200,
            ];
        }
    }

    /**
     * Post message action
     *
     * @return array
     */
    public function actionAdd() {

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'search' => $data,
                'code' => 200,
            ];
        }
    }
}
