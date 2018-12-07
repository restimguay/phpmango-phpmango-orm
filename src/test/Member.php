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
            'email'=>[
                'type'=>'string',
                'min'=>10,
                'max'=>64,
                'label'=>'E-mail',
                'required'=>true
            ],
            'first_name'=>[
                'type'=>'string',
                'min'=>1,
                'max'=>64,
                'label'=>'First Name',
                'required'=>true
            ],
            'surname'=>[
                'type'=>'string',
                'min'=>1,
                'max'=>64,
                'label'=>'Surname',
                'required'=>true
            ],
            'middle_name'=>[
                'type'=>'string',
                'min'=>1,
                'max'=>64,
                'label'=>'Middle Name',
                'required'=>true
            ],
            'password_hash'=>[
                'type'=>'string',
                'min'=>1,
                'max'=>64,
                'label'=>'Password Hash',
                'required'=>true,
                'display'=>false
            ],
            'session_key'=>[
                'type'=>'string',
                'min'=>1,
                'max'=>64,
                'label'=>'Session Key',
                'required'=>false,
                'display'=>false
            ],
            'email_validate_key'=>[
                'type'=>'string',
                'min'=>1,
                'max'=>64,
                'label'=>'Email Validate Key',
                'required'=>false,
                'display'=>false
            ],
            'joined_date'=>[
                'type'=>'integer',
                'max'=>14,
                'label'=>'Date Joined',
                'required'=>true
            ],
            'last_login'=>[
                'type'=>'integer',
                'max'=>14,
                'label'=>'Last Login',
                'required'=>true
            ]
        ];
    }
}