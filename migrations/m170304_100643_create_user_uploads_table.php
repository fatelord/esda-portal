<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_uploads`.
 */
class m170304_100643_create_user_uploads_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('user_uploads', [
            'UPLOAD_ID' => $this->primaryKey(),
            'USER_ID' => $this->integer()->notNull(),
            'FILE_NAME' => $this->string(200)->notNull()->comment('File Name'),
            'FILE_PATH' => $this->string(200)->notNull()->comment('Document Path'),
            'COMMENTS' => $this->text()->comment('Comments'),
            'PUBLICLY_AVAILABLE' => $this->boolean()->notNull()->comment('Publicly Available'),
            'DATE_UPLOADED' => $this->dateTime()->comment('Date Uploaded'),
            'UPDATED' => $this->dateTime()->comment('Date Updated'),
            'DELETED' => $this->boolean()->defaultValue(0)->comment('File Deleted')
        ], 'ENGINE=InnoDB');

        $this->addForeignKey('fk_user_uploads', 'user_uploads', 'USER_ID', 'user_profile', 'USER_ID', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('user_uploads');
    }
}
