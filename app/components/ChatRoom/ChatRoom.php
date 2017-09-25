<?php

namespace app\components\ChatRoom;

use app\components\ChatRoom\models\Message;
use app\components\ChatRoom\models\User;
use yii\base\Object;
use yii\db\Query;
use yii\web\Request;
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
     * @var string location
     */
    private $location = 'Kharkov';

    /**
     * Get model `message`
     *
     * @return Message
     */
    public function getMessageModel()
    {

        if (null === $this->message) {
            $this->message = new Message();
        }

        return $this->message;
    }

    /**
     * Get model `user`
     *
     * @return User
     */
    public function getUserModel()
    {

        if (null === $this->user) {
            $this->user = new User();
        }

        return $this->user;
    }

    /**
     * Validate `message`
     * @return array
     */
    public function validateMessage()
    {

        $messageModel = $this->getMessageModel();
        $response = [];

        $request = \Yii::$app->getRequest();
        if ($request->isPost && $messageModel->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $response = ActiveForm::validate($messageModel);
        }

        return $response;
    }

    /**
     * Validate `user`
     * @return array
     */
    public function validateUser()
    {

        $request = \Yii::$app->getRequest();
        $response = [];

        if ($request->isPost && $this->getUserModel()->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $response = ActiveForm::validate($this->getUserModel());
        }

        return $response;
    }

    /**
     * Save models into `users`, and `messages`
     *
     * @param Request $request
     *
     * @return User
     */
    public function saveChat(Request $request)
    {

        $transaction = \Yii::$app->db->beginTransaction();
        $userModel = $this->getUserModel();

        try {

            $username = $request->post('User')['username'];

            $user = $userModel::findOne([
                'username' => $request->post('User')['username'],
            ]);

            if (!$user) {
                $userModel->ip = ip2long($request->getUserIP());
                $userModel->username = $username;
                $userModel->save(false);
                $userId = $userModel->getPrimaryKey();
            } else {
                $userId = $user->id;
            }

            $messageModel = $this->getMessageModel();
            $messageModel->userId = $userId;
            $messageModel->message = $request->post('Message')['message'];
            $messageModel->save(false);

            $transaction->commit();
            return $userModel;

        } catch (\Exception $e) {
            $transaction->rollBack();
            return $userModel;
        }
    }

    /**
     * Load messages
     *
     * @param int $lastId
     *
     * @return array
     */
    public function loadMessages($lastId)
    {
        $query = new Query();
        $query->select([
            'messages.id',
            'INET_NTOA(users.ip) as ip',
            'users.username',
            'messages.message',
            'messages.publication'
        ])
            ->from('messages')
            ->leftJoin('users', 'messages.userId = users.id');

        if (0 < (int)$lastId) {
            $query->where(['>', 'messages.id', (int)$lastId]);
        }
        $query->orderBy(['publication' => SORT_ASC]);

        $response = $query->createCommand()->queryAll();

        return $this->mapLoadResponse($response);
    }

    /**
     * Map response
     *
     * @param array $response
     *
     * @return array
     */
    private function mapLoadResponse(array $response)
    {

        $geoIp = \Yii::$app->geoip2;

        foreach ($response as &$entity) {
            if (false === isset($geoIp->getInfoByIP($entity['ip'])->city)) {
                $entity['location'] = $this->location;
            }
        }

        return $response;
    }
}