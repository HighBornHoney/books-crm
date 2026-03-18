<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    public $authorIds = [];

    public static function tableName()
    {
        return 'books';
    }

    public function rules()
    {
        return [
            [['title', 'year'], 'required'],
            [['description'], 'string'],
            [['year'], 'integer'],
            [['title', 'isbn', 'cover'], 'string', 'max' => 255],
            [['isbn'], 'unique'],
            [['authorIds'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'year' => 'Год',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'cover' => 'Обложка',
        ];
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }

    public function afterFind()
    {
        $this->authorIds = $this->getAuthors()->select('id')->column();
        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->db->createCommand()
            ->delete('book_author', ['book_id' => $this->id])
            ->execute();

        if (is_array($this->authorIds)) {
            foreach ($this->authorIds as $authorId) {
                Yii::$app->db->createCommand()->insert('book_author', [
                    'book_id' => $this->id,
                    'author_id' => $authorId,
                ])->execute();
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
