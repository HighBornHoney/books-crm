<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Author extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'authors';
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('book_author', ['author_id' => 'id']);
    }

    public function getSubscriptions(): ActiveQuery
    {
        return $this->hasMany(Subscription::class, ['author_id' => 'id']);
    }

    public static function getTopAuthorsByYear(int $year): array
    {
        $sql = <<<SQL
            SELECT
                a.id,
                a.name,
                COUNT(ba.book_id) AS book_count
            FROM authors a
            JOIN book_author ba ON ba.author_id = a.id
            JOIN books b ON b.id = ba.book_id
            WHERE b.year = :year
            GROUP BY a.id, a.name
            ORDER BY book_count DESC
            LIMIT 10
        SQL;

        return Yii::$app->db
            ->createCommand($sql)
            ->bindValue(':year', $year)
            ->queryAll();
    }
}
