<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\traits\GeneratorNestedSets;

class Tree extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%tree}}';
    }

    public function getThread()
    {
        return self::find()
            ->andWhere(['>=', 'lft', $this->lft])
            ->andWhere(['<=', 'rgt', $this->rgt])
            ->orderBy('lft')
            ->all();
    }

    public static function getRoot()
    {
        $root = new Tree();
        $root->attributes = ['lvl' => 0, 'id' => 1, 'name' => 'root'];

        return $root;
    }

    public static function findDefault()
    {

        return self::formatJsonForTree(self::find()->orderBy('lft')->All());
    }

    public static function findAllByThread($ids)
    {
        $list = explode(',', $ids);
        $nodes = self::find()->orderBy('lft')->andWhere(['in', 'id', $list])->All();
        if ($nodes == null) {
            return null;
        }
        $items = [Tree::getRoot()];
        foreach ($nodes as $node) {
            $thread = $node->thread;
            if ($thread[0]->lvl != 1) {
                $delta = $thread[0]->lvl - 1;
                foreach ($thread as $threadNode) {
                    $threadNode->lvl -= $delta;
                }
            }
            $items = array_merge($items, $thread);
        }

        return self::formatJsonForTree($items);
    }

    public function generateData($n)
    {
        $data = self::generate($n);
        Tree::deleteAll();
        Yii::$app->db->createCommand()->batchInsert('tree', ['id', 'name', 'lft', 'rgt', 'lvl'], $data)->execute();
    }

    public static function generate($n = 5)
    {

        if ($n <= 0) {
            return false;
        }
        $root = [
            'id'   => 1,
            'name' => 1,
            'lft'  => 1,
            'rgt'  => $n * 2,
            'lvl'  => 0,
        ];
        $id = $root['id'];
        $result = [$root];
        if ($n === 1) {
            return $result;
        } else {
            $queue = [$root];
        }
        while (!empty($queue)) {
            $elem = array_shift($queue);
            $n = $elem['rgt'] - 1;
            $id++;
            $lft = $elem['lft'] + 1;
            $rgt = $elem['rgt'] - 1;
            $lvl = $elem['lvl'];
            while ($lft < $n) {
                $nrgt = rand($lft + 1, $rgt);
                if (($nrgt - $lft) % 2 == 0) {
                    $nrgt++;
                }
                $node = [
                    'id'   => $id,
                    'name' => $id,
                    'lft'  => $lft,
                    'rgt'  => $nrgt,
                    'lvl'  => $lvl + 1
                ];
                $result[] = $node;
                if (($nrgt - $lft) > 1) {
                    $queue[] = $node;
                }
                if ($nrgt === $n) {
                    break;
                } else {
                    $id++;
                    $lft = $nrgt + 1;
                }
            }
        }

        return $result;
    }

    public static function formatJsonForTree($arrData)
    {
        $stack = [];
        $arraySet = [];
        foreach ($arrData as $arrValues) {
            $stackSize = count($stack);
            while ($stackSize > 0 && $stack[$stackSize - 1]['rgt'] < $arrValues['lft']) {
                array_pop($stack);
                $stackSize--;
            }
            $link =& $arraySet;
            for ($i = 0; $i < $stackSize; $i++) {
                $link =& $link[$stack[$i]['id']]['nodes'];
            }
            $tmp = array_push($link, ['text' => $arrValues->name, 'nodes' => []]);
            array_push($stack, ['id' => $tmp - 1, 'rgt' => $arrValues['rgt']]);
        }

        return $arraySet;
    }
}
