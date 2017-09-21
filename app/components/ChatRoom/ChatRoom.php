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
     * Init
     */
    public function init()
    {
        $session = \Yii::$app->session;

        if (false === $session->isActive) {
            $session->open();
        }

        parent::init();
    }

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
     * @return bool
     */
    public function isUserAuth()
    {
        $isAuth = false;

        $session = \Yii::$app->session;
        $isUserHasIp = $session->has('ip');
        $ip = ip2long(\Yii::$app->request->userIP);

        $isUserExist = $this->isUserExist($ip);
        if ($isUserExist || $isUserHasIp) {
            $isAuth = true;
        }

        return $isAuth;
    }

    /**
     * @return string
     */
    public function getAuthIp()
    {
        $session = \Yii::$app->session;
        $ip = $session->get('ip', null);

        return (true === isset($ip)) ? $ip : null;
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
     * @return bool
     */
    public function saveChat(Request $request)
    {

        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $userModel = $this->getUserModel();
            $userModel->ip = ip2long($request->getUserIP());
            $isExist = $userModel::findOne($userModel->ip);
            if (!$isExist) {
                $userModel->username = $request->post('User')['username'];
                $userModel->save(false);
            }

            $messageModel = $this->getMessageModel();
            $messageModel->userIp = $userModel->ip;
            $messageModel->message = $request->post('Message')['message'];
            $messageModel->save(false);

            $session = \Yii::$app->session;
            $session->set('ip', $userModel->ip);

            $transaction->commit();
            return $messageModel->id;

        } catch (\Exception $e) {
            $transaction->rollBack();
            return 'Error!';
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
            ->leftJoin('users', 'messages.userIp = users.ip');

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

    /**
     * Check if user exists
     *
     * @param int $ip
     *
     * @return bool
     */
    private function isUserExist($ip) {
        $userModel = $this->getUserModel();
        // findOne() WTF ?? return array|null|ActiveRecord (((
        // this shit breeds shit
        return (null === $userModel::findOne($ip)) ? false : true;
    }
}