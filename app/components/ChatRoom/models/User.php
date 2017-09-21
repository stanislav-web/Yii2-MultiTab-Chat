<?php

namespace app\components\ChatRoom\models;

use yii\db\ActiveRecord;

/**
 * Class User
 * @package app\components\ChatRoom\models
 */
class User extends ActiveRecord
{

    const TABLE_NAME = 'users';
    const USERNAME_MIN_LENGTH = 3;
    const USERNAME_MAX_LENGTH = 64;

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
            [ ['username'],'filter','filter'=>'trim'],
            [ ['ip'],'filter','filter'=>'trim'],
            [ ['ip'], 'required', 'message' => '{attribute} is required'],
            [ ['username'], 'required', 'message' => '{attribute} is required'],
            [ ['username'],'string','min'=> self::USERNAME_MIN_LENGTH,'max'=> self::USERNAME_MAX_LENGTH,
                'message' => '`{attribute}` must have not less {min} and greater than {max}'],
        ];
    }
}
