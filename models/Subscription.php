<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Subscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'subscriptions';
    }

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [
                ['author_id'],
                'unique',
                'targetAttribute' => ['author_id', 'phone'],
                'message' => 'Вы уже подписаны на этого автора',
            ],
        ];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
