<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
    <div class="site-index">
        <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <?= $form->field($model, 'numberOfNodes'); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Генерировать дерево', ['class' => 'btn btn-primary']); ?>
        </div>
        <?php ActiveForm::end(); ?>

        <label for="tree">Дерево:</label>
        <div id="tree"></div>

    </div>

<?php
$script = <<<JS
$('#tree').treeview({data: {$data}, levels: 1000, emptyIcon:'glyphicon glyphicon-stop'});
JS;
$this->registerJs($script);
?>