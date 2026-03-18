<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Book extends ActiveRecord
{
    public array $authorIds = [];
    public ?string $coverFile = null;

    public static function tableName(): string
    {
        return 'books';
    }

    public function rules(): array
    {
        return [
            [['title', 'year'], 'required'],
            [['description'], 'string'],
            [['year'], 'integer'],
            [['title', 'isbn', 'cover'], 'string', 'max' => 255],
            [['isbn'], 'unique'],
            [['authorIds'], 'safe'],
            [
                ['coverFile'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'jpg, jpeg, png, gif',
                'maxSize' => 1024 * 1024 * 5,
                'checkExtensionByMimeType' => true,
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Название',
            'year' => 'Год',
            'description' => 'Описание',
            'isbn' => 'ISBN',
        ];
    }

    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }

    public function afterFind(): void
    {
        $this->authorIds = $this->getAuthors()->select('id')->column();
        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $coverFile = UploadedFile::getInstance($this, 'coverFile');
        if ($coverFile) {
            $fileName = 'uploads/' . uniqid() . '.' . $coverFile->extension;
            if ($coverFile->saveAs(Yii::getAlias('@webroot') . '/' . $fileName)) {
                $this->cover = $fileName;
            }
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes): void
    {
        Yii::$app->db->createCommand()
            ->delete('book_author', ['book_id' => $this->id])
            ->execute();

        foreach ($this->authorIds as $authorId) {
            Yii::$app->db->createCommand()->insert('book_author', [
                'book_id' => $this->id,
                'author_id' => $authorId,
            ])->execute();

            $subs = Subscription::find()->where(['author_id' => $authorId])->all();
            foreach ($subs as $sub) {
                $phone = urlencode($sub->phone);
                $text = urlencode("Новая книга {$this->title} от {$sub->author->name}");
                $apiKey = 'EMULATOR_KEY';

                file_get_contents("https://smspilot.ru/api.php?send=1&to={$phone}&text={$text}&apikey={$apiKey}");
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
