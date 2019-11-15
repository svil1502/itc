<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contact}}`.
 */
class m191115_105143_create_contact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contact}}', [
            'id' => $this->primaryKey(),
            'description' => $this->char(255)->defaultValue(null),
            'user_id' => $this->integer(11)->notNull(),
        ]);
        $this->addForeignKey(
            'user_id',  // это "условное имя" ключа
            'contact', // это название текущей таблицы
            'user_id', // это имя поля в текущей таблице, которое будет ключом
            'user', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contact}}');
        //Добавляем удаление внешнего ключа
        $this->dropForeignKey(
            'user_id',
            'user'
        );
    }

}
