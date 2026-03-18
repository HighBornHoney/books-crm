<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $topAuthors array */
/* @var $year int */

$this->title = "ТОП 10 авторов за {$year}";
$this->params['breadcrumbs'][] = 'Отчет';
?>

<div class="card">
    <h1 class="card-header"><?= Html::encode($this->title) ?></h1>
    <div class="card-body">
        <?php if (!empty($topAuthors)): ?>
            <table class="table table-bordered table-success table-striped table-hover">
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
                        <td><span class="badge rounded-pill bg-primary"><?= $author['book_count'] ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Книг за <?= $year ?> год не найдено.</p>
        <?php endif; ?>

    </div>
</div>
