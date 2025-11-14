<?php

class db
{
    protected static $host;
    protected static $user;
    protected static $pass;
    protected static $db_name;

    public static function connect() 
    {
        self::$host="localhost";
        self::$user="root";
        self::$pass="";
        self::$db_name="to-do";

        $connect = new mysqli( self::$host="localhost",self::$user="root",self::$pass="",self::$db_name="to-do") or die ('error al conectarse a la base de datos');
        return $connect;

    }
}