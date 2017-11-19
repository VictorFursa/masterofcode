<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Book;

/**
 * BookSearch represents the model behind the search form about `common\models\Book`.
 */
class BookSearch extends Book
{
    public $categoryName;
    public $tagName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryName', 'tagName'], 'safe'],
            [['id', 'category_id'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Book::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['category.name' => SORT_ASC],
                    'desc' => ['category.name' => SORT_DESC],
                    'label' => 'Book Name'
                ],
                'tagName' => [
                    'asc' => ['tag.name' => SORT_ASC],
                    'desc' => ['tag.name' => SORT_DESC],
                    'label' => 'Tag Name'
                ],
                'categoryName' => [
                    'asc' => ['category.name' => SORT_ASC],
                    'desc' => ['category.name' => SORT_DESC],
                    'label' => 'Category Name'
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            /**
             *  greedy data loading Category
             * for sorting work.
             */
            return $dataProvider;
        }

        // filtration by category
        $query->joinWith(['category' => function ($q) {
            $q->where('category.name LIKE "%' . $this->categoryName . '%"');
        }]);

        // filtration by tags
        $query->joinWith(['tags' => function ($q) {
            $q->where('tag.name LIKE "%' . $this->tagName . '%"');
        }]);

        $query->andFilterWhere(['like', 'book.name', $this->name]);

        return $dataProvider;
    }
}
