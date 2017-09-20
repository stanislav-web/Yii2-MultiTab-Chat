<?php

namespace app\components\ChatRoom;

use app\components\ChatRoom\models\User;
use yii\base\Object;
use app\components\ChatRoom\models\Message;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class ChatRoom
 * @package app\components\ChatRoom
 */
class ChatRoom extends Object
{

    /**
     * @var User
     */
    private $user;

    /**
     * @var Message
     */
    private $message;

    /**
     * @const MESSAGE_LIMIT
     */
    const MESSAGE_LIMIT = 20;

    /**
     * Get model `message`
     *
     * @return Message
     */
    public function getMessageModel() {

        if(null === $this->message) {
            $this->message = new Message();
        }

        return $this->message;
    }

    /**
     * Get model `user`
     *
     * @return User
     */
    public function getUserModel() {

        if(null === $this->user) {
            $this->user = new User();
        }

        return $this->user;
    }

    /**
     * Validate `user`
     * @return array
     */
    public function validateUser() {

        $userModel = $this->getUserModel();

        $request = \Yii::$app->getRequest();
        if ($request->isPost && $userModel->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($userModel);
        }
    }

    /**
     * Save models into `users`, and `messages`
     *
     * @return bool
     */
    public function saveChat() {

        $transaction = \Yii::$app->db->beginTransaction();

        try  {
            if ( $this->getUserModel()->save() && $this->getMessageModel()->save()) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }

            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
        }
    }

    /**
     * Validate `message`
     * @return array
     */
    public function validateMessage() {

        $messageModel = $this->getMessageModel();

        $request = \Yii::$app->getRequest();
        if ($request->isPost && $messageModel->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($messageModel);
        }
    }

    /**
     * Load messages
     *
     * @param int $limit
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function loadMessages($limit = self::MESSAGE_LIMIT) {

        $messages = Message::find()
            ->leftJoin('user', 'user.id = message.userId')
            ->orderBy(['publication' => SORT_ASC])
            ->limit($limit)
            ->all();

        return $messages;
    }
}