<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Usuarios;

/**
 * UsuariosSearch represents the model behind the search form about `app\models\Usuarios`.
 */
class UsuariosSearch extends Usuarios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'empresa_id', 'estado', 'es_super', 'es_admin'], 'integer'],
            [['nombres', 'apellidos', 'email', 'clave', 'token', 'fecha_creacion'], 'safe'],
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
        $query = Usuarios::find();

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
            'empresa_id' => $this->empresa_id,
            'estado' => $this->estado,
            'es_super' => $this->es_super,
            'es_admin' => $this->es_admin,
            'fecha_creacion' => $this->fecha_creacion,
        ]);

        if( Yii::$app->user->identity->es_admin == 1 ){
            $query->andFilterWhere(['like', 'nombres', $this->nombres])
            ->andFilterWhere(['like', 'apellidos', $this->apellidos])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'clave', $this->clave])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andWhere(['=', 'empresa_id', Yii::$app->user->identity->empresa_id]);
        }elseif ( Yii::$app->user->identity->es_super == 1 ) {
            $query->andFilterWhere(['like', 'nombres', $this->nombres])
            ->andFilterWhere(['like', 'apellidos', $this->apellidos])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'clave', $this->clave])
            ->andFilterWhere(['like', 'token', $this->token]);
        }

        

        return $dataProvider;
    }
}
