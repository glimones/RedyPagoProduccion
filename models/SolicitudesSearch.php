<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Solicitudes;

/**
 * SolicitudesSearch represents the model behind the search form about `app\models\Solicitudes`.
 */
class SolicitudesSearch extends Solicitudes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ciudad_id'], 'integer'],
            [['ruc', 'razon_social', 'contacto_cedula', 'contacto_nombres', 'contacto_apellidos', 'direccion', 'email', 'actividades', 'fecha_solicitud', 'estado'], 'safe'],
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
        $query = Solicitudes::find();

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
            'ciudad_id' => $this->ciudad_id,
            'fecha_solicitud' => $this->fecha_solicitud,
        ]);

        $query->andFilterWhere(['like', 'ruc', $this->ruc])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'contacto_cedula', $this->contacto_cedula])
            ->andFilterWhere(['like', 'contacto_nombres', $this->contacto_nombres])
            ->andFilterWhere(['like', 'contacto_apellidos', $this->contacto_apellidos])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'actividades', $this->actividades])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
