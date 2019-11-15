<?php
namespace app\models;
use Yii;
use yii\base\Model;
use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * Signup form
 */
class SignupForm extends Model
{
    const ADMIN_ROLE = 'admin';
    const SUPERVISER_ROLE = 'superviser';
    const OPERATOR_ROLE = 'operator';

    public static $roleList = [
        self::ADMIN_ROLE  => self::ADMIN_ROLE,
        self::SUPERVISER_ROLE => self::SUPERVISER_ROLE,
        self::OPERATOR_ROLE  => self::OPERATOR_ROLE,
    ];
    public $password;
    public $username;
    public $email;
    public $role;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['password', 'username', 'email', 'role'], 'required'],
            [['email'], 'email'],
            [['password', 'username', 'email', 'role'], 'string', 'max' => 15],
            [
                'role',
                'in',
                'range' => self::$roleList,
            ],

        ];
    }


    public function attributeLabels()
    {
        return [

            'password' => 'Пароль пользователя',
            'username' => 'Имя пользователя',
            'email' => 'Электропочта',
            'role' => 'Роль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */


public function signup()
{
    if ($this->validate()) {
        $user = new User();

        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->role = $this->role;
        $user->save(true);

        // RBAC:
        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRole($this->role);
        $auth->assign($authorRole, $user->getId());

        return $user;
    }

    return null;
}



}
