<?php

namespace app\components\ChatRoom\models;

use yii\db\ActiveRecord;

/**
 * Class Message
 * @package app\components\ChatRoom\models
 */
class Message extends ActiveRecord
{

    const TABLE_NAME = 'messages';
    const MESSAGE_MIN_LENGTH = 1;
    const MESSAGE_MAX_LENGTH = 500;

    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $userId
     */
    public $userId;

    /**
     * @var string $message
     */
    public $message;

    /**
     * @var int $publication
     */
    public $publication;

    /**
     * Get table name
     *
     * @return string
     */
    public static function tableName()
    {
        return self::TABLE_NAME;
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public function rules()
    {

        return [
            [ ['message'],'filter','filter'=>'trim'],
            [ ['message'], 'required', 'message' => '{attribute} is required'],
            [ ['message'],'string','min'=> self::MESSAGE_MIN_LENGTH,'max'=> self::MESSAGE_MAX_LENGTH,
                'message' => '`{attribute}` must have not less {min} and greater than {max}'],
        ];
    }
}