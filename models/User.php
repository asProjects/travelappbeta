<?php

class User
{
    private $userName;
    private $name;
    private $email;
    private $cityCode;
    private $avatar;

    public function GetUserName()
    {
        return $this->userName;
    }
    public function GetName()
    {
        return $this->name;
    }
    public function GetEmail()
    {
        return $this->email;
    }
    public function GetCityCode()
    {
        return $this->cityCode;
    }
    public function GetAvatar()
    {
        return $this->avatar;
    }

    public function __construct($userName,$name,$email,$cityCode,$avatar)
    {
        $this->userName=$userName;
        $this->name=$name;
        $this->email=$email;
        $this->cityCode=$cityCode;
        $this->avatar=$avatar;

    }


}

class UserCompressed
{
    public $UserName;
    public $Name;
    public $Email;
    public $CityCode;
    public $Avatar;
}

class UserManager
{

    public static function GetCompressedUser($user)
    {
        $compressedUser=new UserCompressed();

        $compressedUser->UserName=$user->GetUserName();
        $compressedUser->Name=$user->GetName();
        $compressedUser->Email=$user->GetEmail();
        $compressedUser->CityCode=$user->GetCityCode();
        $compressedUser->Avatar=$user->GetAvatar();

        return $compressedUser;
    }

    public static function Login($username,$password)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $query='SELECT Name,Avatar,Email,City FROM Users WHERE UserName=? and Password=?';
            $statement=$db->prepare($query);
            $statement->bindValue(1,$username);
            $statement->bindValue(2,$password);
            $statement->execute();
            $userDB=$statement->fetch();
            if($userDB==null)
            {
                return ResultBit::Fail;
            }
            else
            {
                $user=new User($username,$userDB['Name'],$userDB['Email'],DestinationManager::GetCityFromCode($userDB['City']),$userDB['Avatar']);
                return $user;
            }
            
        }

        
    }

    public static function Signup($user,$password)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $query='INSERT INTO Users(UserName,Name,Password,Avatar,Email,City) values(?,?,?,?,?,?)';
            $statement=$db->prepare($query);
            $statement->bindValue(1,$user->UserName);
            $statement->bindValue(2,$user->Name);
            $statement->bindValue(3,$password);
            $statement->bindValue(4,$user->Avatar);
            $statement->bindValue(5,$user->Email);
            $statement->bindValue(6,DestinationManager::GetCodeFromCity($user->CityCode));

            if($statement->execute()==true)
            {
                return ResultBit::Success;
            }
            else
            {
                return ResultBit::Fail;
            }

            
        }
        
    }

}



?>