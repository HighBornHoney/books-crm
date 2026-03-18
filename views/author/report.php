<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $topAuthors array */
/* @var $year int */

$this->title = "ТОП 10 авторов за {$year}";
?>
    <h1><?= Html::encode($this->title) ?></h1>

<?php if (!empty($topAuthors)): ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>№</th>
            <th>Автор</th>
            <th>Книг</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($topAuthors as $i => $author): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= Html::encode($author['name']) ?></td>
                <td><?= $author['book_count'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Книг за <?= $year ?> год не найдено.</p>
<?php endif; ?>

