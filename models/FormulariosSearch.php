<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Formularios;

/**
 * FormulariosSearch represents the model behind the search form about `app\models\Formularios`.
 */
class FormulariosSearch extends Formularios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'empresa_id'], 'integer'],
            [['descripcion', 'fecha', 'token'], 'safe'],
            [['precio'], 'number'],
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
        $query = Formularios::find()
        ->where('empresa_id = '.Yii::$app->user->identity->empresa_id)
        ->andWhere('usuario_id = "'.Yii::$app->user->identity->id.'"');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            //'fecha' => $this->fecha,

            //'precio' => $this->precio,
            'empresa_id' => $this->empresa_id,
        ]);



        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'fecha', $this->fecha])
            ->andFilterWhere(['like', 'precio', $this->precio])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andWhere(['=', 'empresa_id', Yii::$app->user->identity->empresa_id])
            ->orderBy(['fecha'=>SORT_DESC]);

        return $dataProvider;
    }
    public function searchUser($params)
    {
        $query = Formularios::find()
        ->where('formularios.empresa_id = '.Yii::$app->user->identity->empresa_id)
            ->andWhere('formularios.usuario_id = "'.Yii::$app->user->identity->id.'"');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'fecha' => $this->fecha,
            'precio' => $this->precio,
            'empresa_id' => $this->empresa_id,
        ]);

        $query->andFilterWhere(['like', 'formularios.descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'formularios.token', $this->token])
            ->andWhere(['=', 'formularios.empresa_id', Yii::$app->user->identity->empresa_id])
            ->orderBy('formularios.id ');
        return $dataProvider;
    }

}
