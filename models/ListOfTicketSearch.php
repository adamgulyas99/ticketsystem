<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\models\Ticket;

/**
 * ListOfTicketSearch represents the model behind the search form of `app\models\Ticket`.
 */
class ListOfTicketSearch extends Ticket
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'admin_id'], 'integer'],
            [['heading', 'priority'], 'safe'],
            [['status'], 'boolean'],
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
        $query = Ticket::find();
        $query->joinWith(['user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['sender'] = [
            'asc' => [new Expression('users.name ASC')],
            'desc' => [new Expression('users.name DESC')],
        ];

        $dataProvider->sort->attributes['manager'] = [
            'asc' => [new Expression('users.name ASC')],
            'desc' => [new Expression('users.name DESC')],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'admin_id' => $this->admin_id,
        ]);

        $query->andFilterWhere(['ilike', 'heading', $this->heading])
            ->andFilterWhere(['ilike', 'priority', $this->priority]);

        return $dataProvider;
    }
}
