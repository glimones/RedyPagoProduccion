<?php

namespace app\models;
use Yii;

class Clientes extends \app\models\base\ClientesBase
{
    public function rules()
    {
        return array_merge(parent::rules(),
            [   //[['identificacion','empresa_id'], 'unique','attribute',['identificacion','empresa_id']],
            [['identificacion', 'empresa_id'], 'unique', 'targetAttribute' => ['identificacion', 'empresa_id']],
                ['identificacion', 'validateIdentificacion'],
                [['identificacion'], 'string', 'max' => 13, 'tooLong' => 'Su identificación no consta con la longitud de caracteres permitida '],
                ['email', 'email'],
            ]);
    }


    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'identificacion' => Yii::t('app', 'CI / RUC / Pasaporte'),
            'razon_social' => Yii::t('app', 'Razon Social'),
            'telefonos' => Yii::t('app', 'Teléfonos'),
            'direccion' => Yii::t('app', 'Dirección'),
            'email' => Yii::t('app', 'Email'),
            'empresa' => Yii::t('app', 'empresa'),
        ];
    }

public function validateIdentificacion($attribute, $model, $validator)
{
        $strCedula=$this->identificacion;
            if(is_numeric($strCedula)){
                //$total_caracteres=strlen($strCedula);// se suma el total de caracteres
                //if($total_caracteres==10){//compruebo que tenga 10 digitos la cedula
                    //$nro_region=substr($strCedula, 0,2);//extraigo los dos primeros caracteres de izq a der
                  //  if($nro_region>=1 && $nro_region<=24){// compruebo a que region pertenece esta cedula//
                        $ult_digito=substr($strCedula, -1,1);//extraigo el ultimo digito de la cedula
                        $valor2=substr($strCedula, 1, 1);
                        $valor4=substr($strCedula, 3, 1);
                        $valor6=substr($strCedula, 5, 1);
                        $valor8=substr($strCedula, 7, 1);
                        $suma_pares=($valor2 + $valor4 + $valor6 + $valor8);
                        $valor1=substr($strCedula, 0, 1);
                        $valor1=($valor1 * 2);
                        if($valor1>9){ $valor1=($valor1 - 9); }else{ }
                        $valor3=substr($strCedula, 2, 1);
                        $valor3=($valor3 * 2);
                        if($valor3>9){ $valor3=($valor3 - 9); }else{ }
                        $valor5=substr($strCedula, 4, 1);
                        $valor5=($valor5 * 2);
                        if($valor5>9){ $valor5=($valor5 - 9); }else{ }
                        $valor7=substr($strCedula, 6, 1);
                        $valor7=($valor7 * 2);
                        if($valor7>9){ $valor7=($valor7 - 9); }else{ }
                        $valor9=substr($strCedula, 8, 1);
                        $valor9=($valor9 * 2);
                        if($valor9>9){ $valor9=($valor9 - 9); }else{ }
                        $suma_impares=($valor1 + $valor3 + $valor5 + $valor7 + $valor9);
                        $suma=($suma_pares + $suma_impares);
                        $dis=substr($suma, 0,1);//extraigo el primer numero de la suma
                        $dis=(($dis + 1)* 10);//luego ese numero lo multiplico x 10, consiguiendo asi la decena inmediata superior
                        $digito=($dis - $suma);
                        if($digito==10){
                            $digito='0';
                        }else{

                        }//si la suma nos resulta 10, el decimo digito es cero
                        if ($digito==$ult_digito){//comparo los digitos final y ultimo
                        }else{
                            $this->addError($attribute,"Cedula Incorrecta");
                        }
                    /*}else{
                        $this->addError($attribute,"Este Nro de Cedula no corresponde a ninguna provincia del ecuador");
                    }*/
               // }else{
              //      $this->addError($attribute,"Es un Numero y tiene solo".$total_caracteres." - ".$attribute);
              //  }
            }

    }

}