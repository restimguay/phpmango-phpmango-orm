<?php

namespace MGO\test;

use MGO\model\BaseModel;

{}
class Photo extends BaseModel
{
    /**
     *@var integer $photo_id
    */
    public $photo_id;

    /**
     *@var string $file_name
    */
    public $file_name;

    /**
     *@var string $format
    */
    public $format;

    /**
     *@var string $path
    */
    public $path;
    public function __construct()
    {
        parent::__construct('photo','photo_id');
    }
    /**
     * 
     * 'sex'=>[
                'type'=>'string',
                'min'=>4,
                'max'=>6,
                'label'=>'Sex',
                'required'=>true
            ],
     * 
     */
    public function definition()
    {
        return [
            'photo_id'=>['type'=>'integer','max'=>14,'label'=>'PhotoID','required'=>true],
            'file_name'=>['type'=>'string','min'=>10,'max'=>64,'lable'=>'File Name','required',true],
            'format'=>['type'=>'string','min'=>3,'max'=>3,'lable'=>'File Format','required',true],
            'path'=>['type'=>'string','min'=>10,'max'=>256,'lable'=>'Path','required',true],
        ];
    }
}