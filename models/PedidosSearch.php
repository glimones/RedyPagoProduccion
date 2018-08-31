<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pedidos;

/**
 * PedidosSearch represents the model behind the search form about `app\models\Pedidos`.
 */
class PedidosSearch extends Pedidos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cliente_id', 'empresa_id', 'usuario_id'], 'integer'],
            [['a_pagar'], 'number'],
            [['usuarioCons' , 'usuarioCons'], 'safe'],
            [['fecha_creacion' , 'fecha_creacion'], 'safe'],
            [['tarjeta', 'numero_pedido', 'authorization_result', 'authorization_code', 'error_code', 'payment_reference_code', 'reserved_22', 'reserved_23', 'estado', 'url_pago', 'descripcion'], 'safe'],
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
    public $usuarioCons='';
    public function searchUser($params)
    {
        $query = Pedidos::find()
            ->where('pedidos.empresa_id = '.Yii::$app->user->identity->empresa_id)
            ->andWhere('pedidos.usuario_id = "'.Yii::$app->user->identity->id.'"');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
           return $dataProvider;
        }
       /* $query->andFilterWhere([
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'empresa_id' => $this->empresa_id,
            'usuario_id' => $this->usuario_id,
            'a_pagar' => $this->a_pagar,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_pago' => $this->fecha_pago,
            'subtotal' => $this->subtotal,
            'iva' => $this->iva,
            'total' => $this->total,
            'total_con_iva' => $this->total_con_iva,
            'total_sin_iva' => $this->total_sin_iva,
            'pago_recibido' => $this->pago_recibido,
            'cambio_entregado' => $this->cambio_entregado,
            'testing' => $this->testing,
            'pin_efectivo' => $this->pin_efectivo,
        ]);
            $query->andFilterWhere(['like', 'tarjeta', $this->tarjeta])
                ->andFilterWhere(['like', 'numero_pedido', $this->numero_pedido])
                ->andFilterWhere(['like', 'authorization_result', $this->authorization_result])
                ->andFilterWhere(['like', 'authorization_code', $this->authorization_code])
                ->andFilterWhere(['like', 'error_code', $this->error_code])
                ->andFilterWhere(['like', 'payment_reference_code', $this->payment_reference_code])
                ->andFilterWhere(['like', 'reserved_22', $this->reserved_22])
                ->andFilterWhere(['like', 'reserved_23', $this->reserved_23])
                ->andFilterWhere(['=', 'estado', $this->estado])
                ->andFilterWhere(['like', 'url_pago', $this->url_pago])
                ->andFilterWhere(['=', 'empresa_id', $this->empresa_id])
                ->andFilterWhere(['=', 'usuario_id', $this->usuario_id])
                ->andFilterWhere(['like', 'descripcion', $this->descripcion])
                ->orderBy(['fecha_creacion'=>SORT_DESC]);*/
        return $dataProvider;
    }

    public function search($params)
    {
        $query = Pedidos::find()
           ->select('pedidos.*,usuarios.nombres,usuarios.apellidos')
            ->innerJoin('usuarios', 'usuarios.id = pedidos.usuario_id')
            ->where('usuarios.empresa_id=pedidos.empresa_id');

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
            'pedidos.id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'empresa_id' => $this->empresa_id,
            'usuario_id' => $this->usuario_id,
            'a_pagar' => $this->a_pagar,
            //'pedidos.fecha_creacion' => $this->fecha_creacion,
            'fecha_pago' => $this->fecha_pago,
            'subtotal' => $this->subtotal,
            'iva' => $this->iva,
            'total' => $this->total,
            'total_con_iva' => $this->total_con_iva,
            'total_sin_iva' => $this->total_sin_iva,
            'pago_recibido' => $this->pago_recibido,
            'cambio_entregado' => $this->cambio_entregado,
            'testing' => $this->testing,
            'pin_efectivo' => $this->pin_efectivo,
        ]);

        if( Yii::$app->user->identity->es_admin == 1 ){
            $query->andFilterWhere(['like', 'tarjeta', $this->tarjeta])
            ->andFilterWhere(['like', 'numero_pedido', $this->numero_pedido])
            ->andFilterWhere(['like', 'authorization_result', $this->authorization_result])
            ->andFilterWhere(['like', 'authorization_code', $this->authorization_code])
            ->andFilterWhere(['like', 'error_code', $this->error_code])
            ->andFilterWhere(['like', 'payment_reference_code', $this->payment_reference_code])
            ->andFilterWhere(['like', 'reserved_22', $this->reserved_22])
            ->andFilterWhere(['like', 'reserved_23', $this->reserved_23])
            ->andFilterWhere(['=', 'estado', $this->estado])
            ->andFilterWhere(['like', 'url_pago', $this->url_pago])
            ->andFilterWhere(['like', 'pedidos.fecha_creacion', $this->fecha_creacion])
            ->andFilterWhere(['OR','usuarios.nombres LIKE "%'.$this->usuarioCons.'%"',
                'usuarios.apellidos LIKE "%'.$this->usuarioCons.'%"'])
            ->andWhere(['=', 'pedidos.empresa_id', Yii::$app->user->identity->empresa_id])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->orderBy(['fecha_creacion'=>SORT_DESC]);
        }elseif ( Yii::$app->user->identity->es_super == 1 ) {
            $query->andFilterWhere(['like', 'tarjeta', $this->tarjeta])
            ->andFilterWhere(['like', 'numero_pedido', $this->numero_pedido])
            ->andFilterWhere(['like', 'authorization_result', $this->authorization_result])
            ->andFilterWhere(['like', 'authorization_code', $this->authorization_code])
            ->andFilterWhere(['like', 'error_code', $this->error_code])
            ->andFilterWhere(['like', 'payment_reference_code', $this->payment_reference_code])
            ->andFilterWhere(['like', 'reserved_22', $this->reserved_22])
            ->andFilterWhere(['like', 'reserved_23', $this->reserved_23])
            ->andFilterWhere(['=', 'estado', $this->estado])
            ->andFilterWhere(['like', 'url_pago', $this->url_pago])
            ->andFilterWhere(['like', 'usuarios.nombres', $this->usuarioCons])
            ->andFilterWhere(['like', 'usuarios.apellidos', $this->usuarioCons])
            ->andFilterWhere(['=', 'pedidos.empresa_id', $this->empresa_id])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->orderBy(['fecha_creacion'=>SORT_DESC]);
        }

        

        return $dataProvider;
    }




    public function estado_cuentaUser()
    {
        $query = Pedidos::find()
            ->where('pedidos.empresa_id = '.Yii::$app->user->identity->empresa_id)
            ->andWhere('pedidos.usuario_id = "'.Yii::$app->user->identity->id.'"')
            ->andWhere('pedidos.estado = "Autorizado"');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);


        return $dataProvider;
    }

    public function estado_cuenta()
    {
        $query = Pedidos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);


        $query->andFilterWhere([
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'usuario_id' => $this->usuario_id,
            'a_pagar' => $this->a_pagar,
        ]);

        if( Yii::$app->user->identity->es_super == 1 ){
             $query->andFilterWhere(['like', 'tarjeta', $this->tarjeta])
            ->andFilterWhere(['like', 'numero_pedido', $this->numero_pedido])
            ->andFilterWhere(['like', 'authorization_result', $this->authorization_result])
            ->andFilterWhere(['like', 'authorization_code', $this->authorization_code])
            ->andFilterWhere(['like', 'error_code', $this->error_code])
            ->andFilterWhere(['like', 'payment_reference_code', $this->payment_reference_code])
            ->andFilterWhere(['like', 'reserved_22', $this->reserved_22])
            ->andFilterWhere(['like', 'reserved_23', $this->reserved_23])
            ->andFilterWhere(['=', 'estado', 'Autorizado'])
            ->andFilterWhere(['like', 'url_pago', $this->url_pago])
            ->orderBy(['fecha_pago'=>SORT_DESC]);
        }else{
             $query->andFilterWhere(['like', 'tarjeta', $this->tarjeta])
            ->andFilterWhere(['like', 'numero_pedido', $this->numero_pedido])
            ->andFilterWhere(['like', 'authorization_result', $this->authorization_result])
            ->andFilterWhere(['like', 'authorization_code', $this->authorization_code])
            ->andFilterWhere(['like', 'error_code', $this->error_code])
            ->andFilterWhere(['like', 'payment_reference_code', $this->payment_reference_code])
            ->andFilterWhere(['like', 'reserved_22', $this->reserved_22])
            ->andFilterWhere(['like', 'reserved_23', $this->reserved_23])
            ->andFilterWhere(['=', 'estado', 'Autorizado'])
            ->andFilterWhere(['like', 'url_pago', $this->url_pago])
            ->andWhere(['=', 'empresa_id', Yii::$app->user->identity->empresa_id])
            ->orderBy(['fecha_pago'=>SORT_DESC]);
        } 

        return $dataProvider;
    }
}
