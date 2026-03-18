<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Author;
use app\models\Subscription;
use yii\web\NotFoundHttpException;

class SubscriptionController extends Controller
{
    public function actionCreate($authorId)
    {
        $author = Author::findOne($authorId);
        if (!$author) {
            throw new NotFoundHttpException('Автор не найден');
        }

        $model = new Subscription();
        $model->author_id = $authorId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Вы подписались на автора!');

            return $this->redirect(['author/view', 'id' => $authorId]);
        }

        return $this->render('create', [
            'model' => $model,
            'author' => $author,
        ]);
    }
}
