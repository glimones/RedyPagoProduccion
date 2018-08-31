<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;

class Facturasoft extends Model
{
    public static function emitirComprobante($data)
    {
    $ENDPOINT = '/ingresar/nuevo';
          return self::llamar($ENDPOINT, $data);
    }

    public static function imprimirComprobante($data)
    {
          $ENDPOINT = '/ingresar/imprimir';
          return self::llamar($ENDPOINT, $data);
    }

    public static function llamar($ENDPOINT, $data)
    {
    $estado = array();
          try
          {
              $curl = curl_init();
              
              curl_setopt_array($curl, array(
                  
                      CURLOPT_HTTPHEADER => array(
                            "Authorization: Basic " . base64_encode(Yii::$app->params['facturasoft']['usuario'].":".Yii::$app->params['facturasoft']['clave']),
                            "Content-type: application/json",
                            "Accept: application/json"
                      ),
                      CURLOPT_POSTFIELDS => json_encode($data),
                      CURLOPT_RETURNTRANSFER => 1,
                      CURLOPT_URL => Yii::$app->params['facturasoft']['url_api'] . $ENDPOINT,
                      CURLOPT_USERAGENT => 'Facturasoft',
                      CURLOPT_POST => 1,
              ));
              $resp = curl_exec($curl);
              $info = curl_getinfo($curl);
              if($resp === FALSE || $info['http_code'] != 200)
              {
                  $result = "Error general. Codigo HTTP:".$info['http_code'].'. ';
                  if ( curl_error($curl) ) 
                  {
                      $result = $result . 'Error CURL: '.curl_error($curl);
                  }
                  $estado['estado'] = 'ERROR';
                  $estado['mensaje'] = $result;
                  return $estado;
              }
              curl_close($curl);
        $resultado = json_decode($resp);
        if($resultado->estado == 'ERROR'){
      $estado['estado'] = 'ERROR';
                  $estado['mensaje'] = $resultado->mensaje;
                  return $estado;
        }
              $estado['estado'] = 'OK';
              $estado['mensaje'] = $resultado->mensaje;
              return $estado;

          } catch(Exception $ex) {
              $result = 'Error con emision electronica: ' . $ex->getMessage();
              $estado['estado'] = 'ERROR';
              $estado['mensaje'] = $result;
              return $estado;
          }
    }
}
