<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property integer $USER_ID
 * @property string $USER_NAME
 * @property string $EMAIL_ADDRESS
 * @property string $SURNAME
 * @property string $OTHER_NAMES
 * @property string $PHONE_NUMBER
 * @property integer $ACCOUNT_STATUS
 * @property string $DATE_REGISTERED
 * @property string $DATE_UPDATED
 *
 * @property UserAuthentication[] $userAuthentications
 * @property UserUploads[] $userUploads
 */
class UserProfile extends \yii\db\ActiveRecord
{

    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_UPDATE = 'test';


    public $PASSWORD;
    public $REPEAT_PASSWORD;
    public $CHANGE_PASSWORD;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SIGNUP] = ['USER_NAME', 'EMAIL_ADDRESS', 'PASSWORD', 'REPEAT_PASSWORD'];//Scenario Values Only Accepted
        $scenarios[self::SCENARIO_UPDATE] = ['SURNAME', 'OTHER_NAMES', 'EMAIL_ADDRESS', 'PASSWORD', 'REPEAT_PASSWORD', 'PHONE_NO', 'TIMEZONE', 'COUNTRY', 'CHANGE_PASSWORD'];//Scenario Values Only Accepted
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SURNAME', 'OTHER_NAMES', 'EMAIL_ADDRESS', 'PASSWORD', 'REPEAT_PASSWORD'], 'required', 'on' => [self::SCENARIO_SIGNUP, self::SCENARIO_UPDATE]],

            [['USER_NAME', 'EMAIL_ADDRESS', 'SURNAME', 'OTHER_NAMES'], 'required'],
            [['ACCOUNT_STATUS'], 'integer'],
            [['DATE_REGISTERED', 'DATE_UPDATED'], 'safe'],
            [['USER_NAME', 'SURNAME'], 'string', 'max' => 10],
            //[['EMAIL_ADDRESS'], 'string', 'max' => 15],
            [['EMAIL_ADDRESS'], 'email'],
            [['OTHER_NAMES'], 'string', 'max' => 25],
            [['PHONE_NUMBER'], 'string', 'max' => 20],
            [['USER_NAME'], 'unique'],
            [['EMAIL_ADDRESS'], 'unique'],
            ['REPEAT_PASSWORD', 'compare', 'compareAttribute' => 'PASSWORD', 'skipOnEmpty' => false, 'message' => "Passwords don't match"], //password confirmation rule

            //default values
            [['ACCOUNT_STATUS'], 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'USER_ID' => Yii::t('app', 'User  ID'),
            'USER_NAME' => Yii::t('app', 'Username'),
            'EMAIL_ADDRESS' => Yii::t('app', 'Email Address'),
            'SURNAME' => Yii::t('app', 'Surname'),
            'OTHER_NAMES' => Yii::t('app', 'Other Names'),
            'PHONE_NUMBER' => Yii::t('app', 'Phone Number'),
            'ACCOUNT_STATUS' => Yii::t('app', 'Account Status'),
            'DATE_REGISTERED' => Yii::t('app', 'Date Registered'),
            'DATE_UPDATED' => Yii::t('app', 'Last Updated'),
            'PASSWORD' => Yii::t('app', 'Password'),
            'REPEAT_PASSWORD' => Yii::t('app', 'Repeat Password'),
            'CHANGE_PASSWORD' => Yii::t('app', 'Change Password'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuthentications()
    {
        return $this->hasMany(UserAuthentication::className(), ['USER_ID' => 'USER_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserUploads()
    {
        return $this->hasMany(UserUploads::className(), ['USER_ID' => 'USER_ID']);
    }
}
