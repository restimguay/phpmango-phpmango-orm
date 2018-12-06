<?php

namespace MGO\test;

use MGO\model\BaseModel;


class Member extends BaseModel
{
      /**
      *@var integer $id
      */
      public $id;

      /**
      *@var string $email
      */
      public $email;

      /**
      *@var string $first_name
      */
      public $first_name;

      /**
      *@var string $surname
      */
      public $surname;

      /**
      *@var string $middle_name
      */
      public $middle_name;

      /**
      *@var string $password_hash
      */
      public $password_hash;

      /**
      *@var string $session_key
      */
      public $session_key;

      /**
      *@var string $email_validate_key
      */
      public $email_validate_key;

      /**
      *@var integer $joined_date
      */
      public $joined_date;

      /**
      *@var integer $last_login
      */
      public $last_login;

      /**
      *@var string $sex
      */
      public $sex;
    public function __construct()
    {
        parent::__construct('member','id');
    }

    public function definition(){
        return [
            'sex'=>[
                'type'=>'string',
                'min'=>4,
                'max'=>6,
                'label'=>'Sex',
                'required'=>true
            ],
        ];
    }
}