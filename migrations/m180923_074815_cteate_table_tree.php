<?php

use yii\db\Migration;

/**
 * Class m180923_074815_cteate_table_tree
 */
class m180923_074815_cteate_table_tree extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tree', [
            'id'   => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'lft'  => $this->integer()->notNull(),
            'rgt'  => $this->integer()->notNull(),
            'lvl'  => $this->integer()->notNull(),
        ]);
        //Заливаем тестовые данные
        $sql = "INSERT INTO `tree` VALUES (1,'1',1,40,0),(2,'2',2,7,1),(3,'3',8,25,1),(4,'4',26,27,1),(5,'5',28,37,1),(6,'6',38,39,1),(7,'7',3,6,2),(8,'8',9,14,2),(9,'9',15,20,2),(10,'10',21,22,2),(11,'11',23,24,2),(12,'12',29,36,2),(13,'13',4,5,3),(14,'14',10,11,3),(15,'15',12,13,3),(16,'16',16,19,3),(17,'17',30,35,3),(18,'18',17,18,4),(19,'19',31,32,4),(20,'20',33,34,4);";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tree');
    }
}
