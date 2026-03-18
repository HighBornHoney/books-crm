<?php

namespace app\controllers;

use app\models\Author;
use app\models\AuthorSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Response;

class AuthorController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'report'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate(): string|Response
    {
        $model = new Author();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel(int $id): Author
    {
        if (($model = Author::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Автор не найден');
    }

    public function actionReport(?int $year = null): string
    {
        $year = $year ?: date('Y');

        $sql = <<<SQL
            SELECT a.id, a.name, COUNT(ba.book_id) AS book_count
            FROM authors a
            JOIN book_author ba ON ba.author_id = a.id
            JOIN books b ON b.id = ba.book_id
            WHERE b.year = :year
            GROUP BY a.id, a.name
            ORDER BY book_count DESC
            LIMIT 10
        SQL;

        $topAuthors = Yii::$app->db->createCommand($sql)
            ->bindValue(':year', $year)
            ->queryAll();

        return $this->render('report', [
            'topAuthors' => $topAuthors,
            'year' => $year,
        ]);
    }
}
