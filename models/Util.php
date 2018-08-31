<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use app\models\PedidosOperaciones;

class Util extends Model
{
    public static function getGenerarPermalink($string)
    {
       $spacer = '-';
       $string = mb_strtolower($string, "UTF-8");
       //non-alpha changed for space
       $string = preg_replace("/[\W]/u",' ', $string); 
       //trim first and last spaces
       $string = trim($string);
       //replace blank spaces for $spacer
       $string = str_replace(' ', $spacer, $string);
       //Countinuos $spacer deleting
       $string = preg_replace("/[ _]+/",$spacer, $string);
       $wrong = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'ç', 'ü'); 
       $right = array('a', 'e' ,'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N', 'c', 'u');
       $string = str_replace($wrong, $right, $string);  
       if( ! strlen($string) > 0 ){
          $string = 'sin-titulo'.'-'.time();
       }
       return $string;
    }

    public static function getMesesReportes(){
        $meses = array();
        $meses[1] = 'enero';
        $meses[2] = 'febrero';
        $meses[3] = 'marzo';
        $meses[4] = 'abril';
        $meses[5] = 'mayo';
        $meses[6] = 'junio';
        $meses[7] = 'julio';
        $meses[8] = 'agosto';
        $meses[9] = 'septiembre';
        $meses[10] = 'octubre';
        $meses[11] = 'noviembre';
        $meses[12] = 'diciembre';
        return $meses;
    }

    public static function getTransaccionesPorMes($mes, $anio, $es_super = null){
        if( is_null( $es_super ) ){
          $rows = \app\models\Pedidos::find()->where('empresa_id = '.Yii::$app->user->identity->empresa_id.' and MONTH(fecha_creacion) = '.$mes.' and YEAR(fecha_creacion) = '.$anio)->all();
        }else{
          $rows = \app\models\Pedidos::find()->where('MONTH(fecha_creacion) = '.$mes.' and YEAR(fecha_creacion) = '.$anio)->all();
        }
        if(count($rows)>0){
            return count($rows);
        }else{
            return 0;
        }
    }
    public static function getTransaccionesPorMesUser($mes, $anio, $es_user = null){
        if( is_null( $es_user ) ){
            $rows = \app\models\Pedidos::find()
                ->where('pedidos.empresa_id = '.Yii::$app->user->identity->empresa_id.
                ' and pedidos.usuario_id='.Yii::$app->user->identity->id.' and MONTH(pedidos.fecha_creacion) = '.$mes.' and YEAR(pedidos.fecha_creacion) = '.$anio)
                ->all();
        }else{
            $rows = \app\models\Pedidos::find()
                  ->Where('pedidos.usuario_id='.Yii::$app->user->identity->id.' and MONTH(pedidos.fecha_creacion) = '.$mes.' and YEAR(pedidos.fecha_creacion) = '.$anio)
                  ->all();
        }
        if(count($rows)>0){
            return count($rows);
        }else{
            return 0;
        }
    }

    public static function getTransaccionesProcesadasPorMes($mes, $anio, $es_super = null){
        if( is_null( $es_super ) ){
          $rows = \app\models\Pedidos::find()->where('empresa_id = '.Yii::$app->user->identity->empresa_id.' and MONTH(fecha_creacion) = '.$mes.' and YEAR(fecha_creacion) = '.$anio.' and estado = "Autorizado" ')->all();
        }else{
          $rows = \app\models\Pedidos::find()->where('MONTH(fecha_creacion) = '.$mes.' and YEAR(fecha_creacion) = '.$anio.' and estado = "Autorizado" ')->all();
        }
        if(count($rows)>0){
            return count($rows);
        }else{
            return 0;
        }
    }

    public static function getTransaccionesProcesadasPorMesUser($mes, $anio, $es_super = null){
        if( is_null( $es_super ) ){
            $rows = \app\models\Pedidos::find()
                ->where('pedidos.empresa_id = '.Yii::$app->user->identity->empresa_id.' and pedidos.usuario_id='.Yii::$app->user->identity->id.' and MONTH(pedidos.fecha_creacion) = '.$mes.' and YEAR(pedidos.fecha_creacion) = '.$anio.' and pedidos.estado = "Autorizado" ')
                ->all();

        }else{
            $rows = \app\models\Pedidos::find()
                ->where('pedidos.usuario_id='.Yii::$app->user->identity->id.' and MONTH(pedidos.fecha_creacion) = '.$mes.' and YEAR(pedidos.fecha_creacion) = '.$anio.' and pedidos.estado = "Autorizado" ')
                ->all();
        }
        if(count($rows)>0){
            return count($rows);
        }else{
            return 0;
        }
    }

    public static function generarOrdenOperacion($pedido_id){
      $purchase_operation_number = str_pad( rand(1, 999999999), 9, "0", STR_PAD_LEFT);
      $regOper = new PedidosOperaciones();
      $regOper->pedido_id = $pedido_id;
      $regOper->purchase_operation_number = $purchase_operation_number;
      $regOper->fecha = date('Y-m-d H:i:s');
      $regOper->dispositivo = \Yii::getAlias('@device');
      $regOper->save();
      return $purchase_operation_number;
    }

    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function borrarRegistrosRecursivos( $model ){
      $reflector = new \ReflectionClass($model);
      $relations = [];
      foreach ($reflector->getMethods() AS $method) {
          $relations [] = $method->name;
      }

      foreach ($relations as $relation) {
          $clase = explode('get', $relation);
          if( isset( $clase[1] ) ){
              $clase = $clase[1];
              if( file_exists(Yii::getAlias('@app').'/models/'.$clase.'.php') ){
                  // echo 'existe modelo:'.$clase.' <br>';
                  $relacion = lcfirst($clase);
                  $modelHijos = $model->$relacion;
                  if(is_array($modelHijos)){
                      foreach ($modelHijos as $hijo) {
                          self::borrarRegistrosRecursivos( $hijo );
                          $hijo->delete();
                      }
                  }
              }
          }
      }
      $model->delete();
    }

}
