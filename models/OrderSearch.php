<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\validators\DateValidator;

use app\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order
{

    //differs form created_at in that It isn't a datetime value
    public $createdAtDate;
    public $createdAtDateMachineFormat;
    //public $createdAtTime;
    //public $createdAtTimeMachineFormat;
    //public $customer;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'number'/*, 'status', 'customer_id'*/], 'integer'],
            [[/*'created_at', */'createdAtDate'/*, 'createdAtTime'*/], 'trim'],
            //[['customer'], 'safe'],
            //[['created_at'], 'date', 'type' => DateValidator::TYPE_DATETIME, 'timestampAttribute' => 'createdAtMachineFormat', 'timestampAttributeFormat' => 'php:Y-m-d H:i:s'],
            [['createdAtDate'], 'date', 'timestampAttribute' => 'createdAtDateMachineFormat', 'timestampAttributeFormat' => 'php:Y-m-d'],
            /*[['createdAtTime'], 'date', 'type' => DateValidator::TYPE_TIME, 'timestampAttribute' => 'createdAtTimeMachineFormat', 'timestampAttributeFormat' => 'php:H:i:s'],*/
        ];
    }

    /**
     * @inheritdoc
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

        $query = Order::find();
        $query->joinWith(['customer']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['customer'] = [
            'asc' => ['customer.first_name' => SORT_ASC],
            'desc' => ['customer.first_name' => SORT_DESC],
            'default' => SORT_ASC,
            //'label' => Inflector::camel2words('age'),
        ];

        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        //Alternative version ...
        /*$dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'number',
                    'created_at',
                    'finished_at',
                    'customer' =>[
                        'asc' => ['customer.first_name' => SORT_ASC],
                        'desc' => ['customer.first_name' => SORT_DESC],
                        'default' => SORT_ASC,
                        //'label' => Inflector::camel2words('age'),
                    ],
                ],
                'defaultOrder' => ['created_at' => SORT_DESC],
        ]]);*/

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dateFrom = $dateTo = null;
        if(isset($this->createdAtDateMachineFormat)){
            $format = 'Y-m-d H:i:s';
            $datetime = new \DateTime($this->createdAtDateMachineFormat, new \DateTimeZone(Yii::$app->timeZone));
            $datetime->setTimezone(new \DateTimeZone('UTC'));
            $dateFrom = $datetime->format($format);
            $datetime->modify('+1 day');
            $dateTo = $datetime->format($format);
        }

        $query->andFilterWhere([
            'number' => $this->number,
        ])
        ->andFilterWhere(['>=', 'order.created_at', $dateFrom])
        ->andFilterWhere(['<', 'order.created_at', $dateTo]);

        //Abandoned strategies ...
        //$sqlQuery = $query->createCommand()->getRawSql();

        //->andFilterWhere(['like', 'customer.first_name', $this->customer]);

        //->andFilterWhere(['like', 'created_at', $this->created_at]);
        //->andFilterWhere(['like', 'DATE(created_at)', $this->createdAtDate])
        //->andFilterWhere(['like', 'TIME(created_at)', $this->createdAtTime]);

        //->andFilterWhere(["DATE(CONVERT_TZ(created_at, 'UTC', 'America/Tijuana'))" => $this->createdAtDateMachineFormat])
        //->andFilterWhere(["TIME(CONVERT_TZ(created_at, 'UTC', 'America/Tijuana'))" => $this->createdAtTimeMachineFormat]);

        //->andFilterWhere(["created_at" => new Expression("CONVERT_TZ('2016-06-02 19:30:16', 'America/Tijuana', 'UTC')")]);

        return $dataProvider;
    }

}
