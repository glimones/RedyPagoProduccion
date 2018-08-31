<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Empresas;

/**
 * EmpresasSearch represents the model behind the search form about `app\models\Empresas`.
 */
class EmpresasSearch extends Empresas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ciudad_id', 'estado'], 'integer'],
            [['ruc', 'razon_social', 'logo', 'contacto_cedula', 'contacto_nombres', 'contacto_apellidos', 'direccion', 'email', 'actividades', 'url_comercio_electronico', 'fecha_afiliacion', 'id_commerce', 'id_acquirer', 'id_wallet', 'llave_vpos', 'llave_wallet', 'ambiente'], 'safe'],
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
        $query = Empresas::find();

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
            'fecha_afiliacion' => $this->fecha_afiliacion,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'ruc', $this->ruc])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'contacto_cedula', $this->contacto_cedula])
            ->andFilterWhere(['like', 'contacto_nombres', $this->contacto_nombres])
            ->andFilterWhere(['like', 'contacto_apellidos', $this->contacto_apellidos])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'actividades', $this->actividades])
            ->andFilterWhere(['like', 'url_comercio_electronico', $this->url_comercio_electronico])
            ->andFilterWhere(['like', 'id_commerce', $this->id_commerce])
            ->andFilterWhere(['like', 'id_acquirer', $this->id_acquirer])
            ->andFilterWhere(['like', 'id_wallet', $this->id_wallet])
            ->andFilterWhere(['like', 'llave_vpos', $this->llave_vpos])
            ->andFilterWhere(['like', 'llave_wallet', $this->llave_wallet])
            ->andFilterWhere(['like', 'ambiente', $this->ambiente]);

        return $dataProvider;
    }
}
