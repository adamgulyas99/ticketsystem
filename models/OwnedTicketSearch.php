<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ticket;
use yii\db\Expression;

/**
 * OwnedTicketSearch represents the model behind the search form of `app\models\Ticket`.
 */
class OwnedTicketSearch extends Ticket
{

    public $create_time;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'admin_id'], 'integer'],
            [['heading', 'priority', 'create_time'], 'safe'],
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
//        $query = Ticket::find();
//        $query->joinWith(['comments', 'lastcomment'], true);

        // add conditions that should always apply here

//        $query->where(['ticket.user_id' => \Yii::$app->user->identity->getId()]);
        $query = Ticket::find()->where(['ticket.user_id' => \Yii::$app->user->identity->getId()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'status' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->sort->attributes['create_time'] = [
            'asc' => [new Expression('comment.create_time ASC')],
            'desc' => [new Expression('comment.create_time DESC')],
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
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['ilike', 'heading', $this->heading])
            ->andFilterWhere(['ilike', 'priority', $this->priority]);

        return $dataProvider;
    }
}
