<?php

namespace app\models;

class Usuarios extends \app\models\base\UsuariosBase implements \yii\web\IdentityInterface
{
    public function rules()
    {
        return array_merge(parent::rules(),
        [
            [['nombres', 'apellidos', 'empresa_id'], 'required'],
            ['email', 'unique'],
        ]);
    }

    public function attributeLabels()
    {
    return [
        'id' => 'ID',
        'empresa_id' => 'Empresa',
        'nombres' => 'Nombres',
        'apellidos' => 'Apellidos',
        'email' => 'E-mail',
        'clave' => 'Contraseña',
        'estado' => 'Estado',
        'token' => 'Token',
        'es_super' => 'Es Super',
        'es_admin' => 'Es Admin',
        'fecha_creacion' => 'Fecha Creación',
    ];
    }

	public function getAuthKey()
    {
        return $this->token;
    }

    public function getId()
    {
        return $this->id;
    }

    public function validateAuthKey($token)
    {
        // return $this->token === $token;
    }

    public static function findIdentity($id)
    {
        return self::findOne( $id );
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // foreach (self::$users as $user) {
        //     if ($user['accessToken'] === $token) {
        //         return new static($user);
        //     }
        // }

        return null;
    }

    public static function findByUsername($username)
    {
        return self::findOne( ['email' => $username] );
    }

    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->clave);
    }
    
}