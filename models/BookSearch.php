<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class BookSearch extends Book
{
    public function rules(): array
    {
        return [
            [['id', 'year'], 'integer'],
            [['title', 'description', 'isbn', 'cover', 'created_at'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params, ?string $formName = null): ActiveDataProvider
    {
        $query = Book::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'year' => $this->year,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'isbn', $this->isbn])
            ->andFilterWhere(['like', 'cover', $this->cover]);

        return $dataProvider;
    }
}
