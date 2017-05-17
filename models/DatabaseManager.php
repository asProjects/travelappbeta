<?php
class DataBaseManager
{
    private static $dsn='mysql:host=localhost;dbname=travelapp';
    private static $userName='root';
    private static $password='';
    private static $db;

    private function __construct()
    {
            
    }

    public static function GetDatabase()
    {
        if(!isset(self::$db))
        {
            try
            {
                self::$db=new PDO(self::$dsn,self::$userName,self::$password);
            }
            catch(PDOException $e)
            {
                $err='-1';
                return $err;
            }
        }
        return self::$db;
    }
}
?>