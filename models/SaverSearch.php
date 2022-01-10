<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Saver;

/**
 * SaverSearch represents the model behind the search form of `app\models\Saver`.
 */
class SaverSearch extends Saver
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'event_id'], 'integer'],
            [['seat_id', 'comment','place_title'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = Saver::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'event_id' => $this->event_id,
        ]);

        $query->andFilterWhere(['like', 'seat_id', $this->seat_id])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'place_title', $this->place_title]);

        return $dataProvider;
    }
}
