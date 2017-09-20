<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

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

        $messageModel = Yii::$app->chatroom->getMessageModel();
        $userModel = Yii::$app->chatroom->getUserModel();
        return $this->render('index',
            compact('title', 'subtitle', 'messageModel', 'userModel')
        );
    }

    /**
     * Ge messages action
     *
     * @return Response
     */
    public function actionList()
    {
        if (false === Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException(' Access forbidden');
        }

        $messages = Yii::$app->chatroom->loadMessages();
        return $this->asJson($messages);

    }

    /**
     * Chat message validation
     *
     * @return array
     */
    public function actionValidate()
    {

        if (false === Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException(' Access forbidden');
        }
        return array_merge(
            Yii::$app->chatroom->validateUser(),
            Yii::$app->chatroom->validateMessage()
        );
    }

    /**
     * Chat message save
     *
     * @return array
     */
    public function actionSave()
    {

        if (false === Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException(' Access forbidden');
        }

        $result = \Yii::$app->chatroom->saveChat();
        return $this->asJson($result);
    }
}
