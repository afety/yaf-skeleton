<?php

namespace Library\Extend\Migration;

class TypeMap
{
    protected static $map = [
        'array' => 'array',
        'ascii_string' => 'string',
        'bigint' => 'string',
        'boolean' => 'boolean',
        'date' => 'string',
        'date_immutable' => 'string',
        'datetime' => 'string',
        'datetime_immutable' => 'string',
        'datetimetz' => 'string',
        'datetimetz_immutable' => 'string',
        'decimal' => 'float',
        'float' => 'float',
        'integer' => 'int',
        'json' => 'string',
        'simple_array' => '',
        'smallint' => 'int',
        'string' => 'string',
        'text' => 'string',
        'time' => 'string',
        'time_immutable' => 'string',

        // NOT SUPPORT
//        'binary' 					=> '',
//        'blob' 						=> '',
//        'dateinterval' 				=> '',
//        'guid' 						=> '',
//        'object' 					=> '',
    ];

    /**
     *
     *
     * @param string $typeName
     * @return bool|string
     */
    public static function getPHPType(string $typeName)
    {
        return self::$map[$typeName] ?? false;
    }
}