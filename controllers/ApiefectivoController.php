<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Pedidos;
use app\models\TransaccionesDescomplicate;

class ApiefectivoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'soap' => [
                'class' => 'mongosoft\soapserver\Action',
                // 'serviceOptions' => [
                //     'disableWsdlMode' => true,
                // ]
            ],
            'pprocesarpagoefectivo' => [
                'class' => 'mongosoft\soapserver\Action',
                // 'serviceOptions' => [
                //     'disableWsdlMode' => true,
                // ]
            ],
            'pprocesarreversoefectivo' => [
                'class' => 'mongosoft\soapserver\Action',
                // 'serviceOptions' => [
                //     'disableWsdlMode' => true,
                // ]
            ],
            'descomplicaterecargar' => [
                'class' => 'mongosoft\soapserver\Action',
                // 'serviceOptions' => [
                //     'disableWsdlMode' => true,
                // ]
            ],
            'descomplicatereversorecarga' => [
                'class' => 'mongosoft\soapserver\Action',
                // 'serviceOptions' => [
                //     'disableWsdlMode' => true,
                // ]
            ],
        ];
    }

    /**
     * @param string $valor
     * @param string $idtransaccion
     * @param string $pin
     * @return array
     * @soap
     */
    public function pProcesarPagoEfectivo($valor, $idtransaccion, $pin)
    {
        
        $pedido = Pedidos::find()
          ->andWhere( [ 'pin_efectivo' => trim($pin), 'a_pagar'=>$valor ] )
          ->one();

        if( is_object( $pedido ) ){

          $fecha_hasta = strtotime('+3 day', strtotime($pedido->fecha_creacion));

          if( strtotime(date('Y-m-d')) <= $fecha_hasta ){
            if( $pedido->estado != 'Autorizado' ){

              $pedido->idtransaction = $idtransaccion;
              $pedido->estado = 'Autorizado';
              $pedido->fecha_pago = date('Y-m-d H:i:s');
              $pedido->authorization_result = '00';
              $pedido->error_message = 'PONLEMAS';
              $pedido->forma_pago = 'Efectivo';
              
              if( $pedido->save() ){
                return ['RedyPago'=>[ 'respuesta'=>'00', 'mensaje'=>'Aprobado', 'autorizacion'=>$pedido->purchase_operation_number]];
              }else{
                return ['RedyPago'=>[ 'respuesta'=>'10', 'mensaje'=>'Ocurrio un error, vuelva a intentarlo', 'errores'=>$pedido->getErrors() ]];
              }

            }else{
              return ['RedyPago'=>[ 'respuesta'=>'01', 'mensaje'=>'El pedido ya fue pagado' ]];
            }
          }else{
            return ['RedyPago'=>[ 'respuesta'=>'03', 'mensaje'=>'El pedido ha caducado' ]];
          }

        }else{
          return ['RedyPago'=>[ 'respuesta'=>'02', 'mensaje'=>'No se encontró pedido o no coinciden valores' ]];
        }

    }

    /**
     * @param string $valor
     * @param string $idtransaccion
     * @param string $pin
     * @return array
     * @soap
     */
    public function pProcesarReversoEfectivo($valor, $idtransaccion, $pin)
    {
        $pedido = Pedidos::find()
          ->andWhere( [ 'pin_efectivo' => trim($pin), 'a_pagar'=>$valor, 'idtransaction' => $idtransaccion ] )
          ->one();

        if( is_object( $pedido ) ){

          if( $pedido->estado == 'Autorizado' ){

              $pedido->idtransaction = $idtransaccion;
              $pedido->estado = 'Pago pendiente';
              $pedido->fecha_pago = null;
              $pedido->authorization_result = '';
              $pedido->error_message = '';
              $pedido->forma_pago = null;
              
              if( $pedido->save() ){
                return ['RedyPago'=>[ 'respuesta'=>'00', 'mensaje'=>'Reverso Aprobado', 'autorizacion'=>$pedido->purchase_operation_number]];
              }else{
                return ['RedyPago'=>[ 'respuesta'=>'10', 'mensaje'=>'Ocurrio un error, vuelva a intentarlo', 'errores'=>$pedido->getErrors() ]];
              }
          }else{
            
            return ['RedyPago'=>[ 'respuesta'=>'01', 'mensaje'=>'Reverso no aprobado, órden no ha sido pagada', 'autorizacion'=>$pedido->purchase_operation_number]];
          }

        }else{
          return ['RedyPago'=>[ 'respuesta'=>'02', 'mensaje'=>'No se encontró pedido o no coinciden valores' ]];
        }
    }

    /**
     * @param string $valor
     * @param string $idtransaccion
     * @param string $cedula
     * @return array
     * @soap
     */
    public function descomplicateRecargar($valor, $idtransaccion, $cedula)
    {
        $transaccion = new TransaccionesDescomplicate();
        $transaccion->cedula = $cedula;
        $transaccion->idtransaccion = $idtransaccion;
        $transaccion->valor = $valor;
        $transaccion->fecha = date('Y-m-d H:i:s');

        if( $transaccion->save() ){

          $url = Yii::$app->params['descomplicate_api'].'rechargeBalance/';
          $data = '{"cedula":"'.$cedula.'","codigo":"'.$idtransaccion.'","valor":"'.$valor.'"}';
          $header = array( 'Content-Type: application/json' );
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_POST, 1);
          curl_setopt($curl, CURLOPT_TIMEOUT, 30);
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
          $response = curl_exec($curl);
          curl_close($curl);
          $array = json_decode( $response, JSON_FORCE_OBJECT );

          $transaccion->mensaje_descomplicate = ( isset( $array['message'] ) ) ? $array['message'] : 'No disponible';
          if( isset( $array['success'] ) ){

            if( $array['success'] == 1 ){
              $transaccion->estado = 1;
              $transaccion->cod_descomplicate = ( isset( $array['cod_descomplicate'] ) ) ? $array['cod_descomplicate'] : null;
              $transaccion->save();
              return ['RedyPago'=>[ 'respuesta'=>'00', 'mensaje'=>$array['message'], 'autorizacion'=>$transaccion->cod_descomplicate ] ];
            }else{
              $transaccion->estado = 4;
              $transaccion->save();
              return ['RedyPago'=>[ 'respuesta'=>'04', 'mensaje'=>$array['message'] ]];
            }

          }else{
            
            $transaccion->estado = 4;
            $transaccion->save();
            return ['RedyPago'=>[ 'respuesta'=>'03', 'mensaje'=>$array['message'] ]];
          
          }
 
              // if( $pedido->save() ){
              //   return ['RedyPago'=>[ 'respuesta'=>'00', 'mensaje'=>'Aprobado', 'autorizacion'=>$pedido->purchase_operation_number]];
              // }else{
              //   return ['RedyPago'=>[ 'respuesta'=>'10', 'mensaje'=>'Ocurrio un error, vuelva a intentarlo', 'errores'=>$pedido->getErrors() ]];
              // }

            
          // }else{
          //   return ['RedyPago'=>[ 'respuesta'=>'03', 'mensaje'=>'El pedido ha caducado' ]];
          // }

        }else{
          return ['RedyPago'=>[ 'respuesta'=>'02', 'mensaje'=>'No se pudo establecer conexión con Descomplicate' ]];
        }

    }

    /**
     * @param string $valor
     * @param string $idtransaccion
     * @param string $cedula
     * @return array
     * @soap
     */
    public function descomplicateReversoRecarga($idtransaccion, $cedula)
    {
        $transaccion = TransaccionesDescomplicate::find()
          ->andWhere( [ 'idtransaccion' => trim($idtransaccion), 'cedula' => $cedula ] )
          ->one();

        if( is_object( $transaccion ) ){

          $url = Yii::$app->params['descomplicate_api'].'revertRechargeBalance/';
          $data = '{"codigo":"'.$idtransaccion.'"}';
          $header = array( 'Content-Type: application/json' );
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_POST, 1);
          curl_setopt($curl, CURLOPT_TIMEOUT, 30);
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
          $response = curl_exec($curl);
          curl_close($curl);
          $array = json_decode( $response, JSON_FORCE_OBJECT );

          $transaccion->mensaje_descomplicate = ( isset( $array['message'] ) ) ? $array['message'] : 'No disponible';

          if( isset( $array['success'] ) ){

            if( $array['success'] == 1 ){
              $transaccion->estado = 2;
              $transaccion->save();
              return ['RedyPago'=>[ 'respuesta'=>'00', 'mensaje'=>$array['message'] ]];
            }else{
              $transaccion->estado = 4;
              $transaccion->save();
              return ['RedyPago'=>[ 'respuesta'=>'04', 'mensaje'=>$array['message'] ]];
            }

          }else{
            
            $transaccion->estado = 4;
            $transaccion->save();
            return ['RedyPago'=>[ 'respuesta'=>'03', 'mensaje'=>$array['message'] ]];
          
          }

        }else{
          return ['RedyPago'=>[ 'respuesta'=>'02', 'mensaje'=>'No se encontró transacción o no coinciden valores' ]];
        }
    }

}