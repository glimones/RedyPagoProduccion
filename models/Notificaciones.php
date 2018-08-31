<?php

namespace app\models;

class Notificaciones extends \app\models\base\NotificacionesBase
{
    public function setNotificacion(){
    	
  //   	$notificaciones = Notificaciones::find()->all();
		// foreach ($notificaciones as $notificacion) {
		//     $notificacion->estado = 'En espera';
		//     $notificacion->save();
		// }

		// foreach ($notificaciones as $notificacion) {
		//     $notificacion->estado = 'Enviado';
		//     $notificacion->save();
		//     $email = Yii::$app->mailer->compose('nuevo_ticket', ['nombre'=>Yii::$app->user->identity->nombre_comercial, 'model'=>$model])
  //               ->setFrom('soporte@abitmedia.com')
  //               ->setTo(Yii::$app->user->identity->email)
  //               ->setSubject('Nuevo ticket recibido exitosamente: '.$model->asunto)
  //               ->send();
  //               sleep(4);
		// }

    }
}