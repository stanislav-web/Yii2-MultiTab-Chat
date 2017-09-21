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
        $isAuth = Yii::$app->chatroom->isUserAuth();
        $ip = Yii::$app->chatroom->getAuthIp();
        return $this->render('index',
            compact('title',
                'subtitle',
                'messageModel',
                'userModel',
                'isAuth',
                'ip'
            )
        );
    }

    /**
     * Ge messages action
     *
     * @return Response
     */
    public function actionList()
    {
        $request = Yii::$app->getRequest();

        if (false === $request->isAjax) {
            throw new ForbiddenHttpException(' Access forbidden');
        }

        $messages = Yii::$app->chatroom->loadMessages($request->get('lastId'));
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

        if (null === Yii::$app->chatroom->validateUser()) {
            $response = Yii::$app->chatroom->validateMessage();
        } else {
            $response = array_merge(
                Yii::$app->chatroom->validateUser(),
                Yii::$app->chatroom->validateMessage()
            );
        }
        return $response;
    }

    /**
     * Chat message save
     *
     * @return Response
     */
    public function actionSave()
    {
        $request = Yii::$app->getRequest();
        if (false === $request->isAjax && false === $request->isPost) {
            throw new ForbiddenHttpException(' Access forbidden');
        }

        $response = Yii::$app->chatroom->saveChat($request);
        return $this->asJson(['response' => $response]);
    }
}
