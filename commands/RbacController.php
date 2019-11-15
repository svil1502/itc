<?php
//namespace console\controllers;
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init
 */
class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll(); //На всякий случай удаляем старые данные из БД...

        // Создадим роли админа, супервайзера, оператора в таблице auth-item (type = 1)
        $admin = $auth->createRole('admin');
        $admin->description = 'Админ имеет полный доступ ко всем таблицам';
        $superviser = $auth->createRole('superviser');
        $superviser->description = 'Супервайзер имеет доступ только к таблице Контакты CRUD';
        $operator = $auth->createRole('operator');
        $operator->description = 'Оператор имеет доступ только к таблице Контакты со своим CRUD';


        // запишем их в БД
        $auth->add($admin);
        $auth->add($superviser);
        $auth->add($operator);

        // Создаем разрешения в таблице auth-item (type = 2)

        $updateContact = $auth->createPermission('updateContact');
        $updateContact ->description = 'Обновление таблицы contact';

        $superview = $auth->createPermission('superview');
        $superview ->description = 'CRUD для супервайзера';


        // Запишем эти разрешения в БД

        $auth->add($updateContact);
        $auth->add($superview);

        // Теперь добавим наследования. Для роли operator мы добавим разрешение
        // updateContact


        $auth->addChild($operator, $updateContact);


        // Создаем наше правило, которое позволит проверить автора записи в таблице contact
        $authorRule = new \app\components\AuthorRule;

        // Запишем его в БД в таблицу auth_rule
        $auth->add($authorRule);

        // Создадим еще новое разрешение «Редактирование собственной записи в contact» и ассоциируем его с правилом AuthorRule
        $updateOwnNews = $auth->createPermission('updateOwnNews');
        $updateOwnNews->description = 'Редактирование собственной записи в contact';

        // Указываем правило AuthorRule для разрешения updateOwnNews.
        $updateOwnNews->ruleName = $authorRule->name;
        //Запишем разрешение в БД
        $auth->add($updateOwnNews);


        // В таблице auth-item-child админ наследует роль супервайзера,
        // который, в свою очередь, наследует роль оператора.
        $auth->addChild($admin, $superviser);
        $auth->addChild($superviser, $operator);
        $auth->addChild($operator,$updateOwnNews);
        $auth->addChild($updateOwnNews,$updateContact);
        $auth->addChild($superviser,$superview);

        // Назначаем роль admin пользователю с ID 1
        $auth->assign($admin, 1);

    }
}