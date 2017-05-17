<?php

class AddressType
{
    public $LineOne;
    public $Area;

    public function __construct($lineOne,$area)
    {
        $this->LineOne=$lineOne;
        $this->Area=$area;
    }

}

class FacilityType
{
    public $WiFi;
    public $Breakfast;
    public $OtherDetails;

    public function __construct($wifi,$breakfast,$otherDetails)
    {
        $this->WiFi=$wifi;
        $this->Breakfast=$breakfast;
        $this->OtherDetails=$otherDetails;
    }
}

class AbstractHotel
{
    private $hotelID;
    private $name;
    private $category;

    public function GetHotelID()
    {
        return $this->hotelID;
    }
    public function GetName()
    {
        return $this->name;
    }
    public function GetCategory()
    {
        return $this->category;
    }

    public function __construct($hotelID,$name,$cat)
    {
        $this->hotelID=$hotelID;
        $this->name=$name;
        $this->category=$cat;
    }
}

class Hotel 
{

    private $hotelID;
    private $name;
    private $category;

    private $address;
    private $cityCode;
    private $description;
    private $price;
    private $images;
    private $timmings;
    private $facilities;
    private $availability;

    public function GetHotelID()
    {
        return $this->hotelID;
    }
    public function GetName()
    {
        return $this->name;
    }
    public function GetCategory()
    {
        return $this->category;
    }

    public function GetAddress()
    {
        return $this->address;
    }
    public function GetCityCode()
    {
        return $this->cityCode;
    }
    public function GetDescription()
    {
        return $this->description;
    }
    public function GetPrice()
    {
        return $this->price;
    }
    public function GetImages()
    {
        return $this->images;
    }
    public function GetTimmings()
    {
        return $this->timmings;
    }
    public function GetFacilities()
    {
        return $this->facilities;
    }
    public function GetAvailability()
    {
        return $this->availability;
    }

    public function __construct($hotelID,$name,$cat,$address,$cityCode,$description,$price,$images,$timmings,$facilities,$availability)
    {
        $this->hotelID=$hotelID;
        $this->name=$name;
        $this->category=$cat;

        $this->address=$address;
        $this->cityCode=$cityCode;
        $this->description=$description;
        $this->price=$price;
        $this->images=$images;
        $this->timmings=$timmings;
        $this->facilities=$facilities;
        $this->availability=$availability;
    }

}

class HotelCompressed
{
    public $HotelID;
    public $Name;
    public $Category;

    public $Address;
    public $CityCode;
    public $Description;
    public $Price;
    public $Images;
    public $Timmings;
    public $Facilities;
    public $Availability;
}

class AbstractHotelCompressed
{
    public $HotelID;
    public $Name;
    public $Category;
}

class HotelManager
{
    public static function GetCompressedAbstractHotel($abstractHotel)
    {
        $compressedAbstractHotel=new AbstractHotelCompressed();
        $compressedAbstractHotel->HotelID=$abstractHotel->GetHotelID();
        $compressedAbstractHotel->Name=$abstractHotel->GetName();
        $compressedAbstractHotel->Category=$abstractHotel->GetCategory();
        return $compressedAbstractHotel;
    }

    public static function GetCompressedHotel($hotel)
    {
        $compressedHotel=new HotelCompressed();
        $compressedHotel->HotelID=$hotel->GetHotelID();
        $compressedHotel->Name=$hotel->GetName();
        $compressedHotel->Category=$hotel->GetCategory();
        $compressedHotel->Address=$hotel->GetAddress();
        $compressedHotel->CityCode=$hotel->GetCityCode();
        $compressedHotel->Description=$hotel->GetDescription();
        $compressedHotel->Price=$hotel->GetPrice();
        $compressedHotel->Images=$hotel->GetImages();
        $compressedHotel->Timmings=TimmingGrpManager::GetTimestampRegEx($hotel->GetTimmings());
        $compressedHotel->Facilities=$hotel->GetFacilities();
        $compressedHotel->Availability=$hotel->GetAvailability();

        return $compressedHotel;
    }

    public static function GetAbstractHotelByID($hotelID)
    {
        
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            //All Query Code Here
            $query='SELECT HotelId,Name,Category FROM Hotel WHERE HotelId=?';
            $statement=$db->prepare($query);
            $statement->bindValue(1,$hotelID);
            $statement->execute();
            $hotel=$statement->fetch();

            $hotid=$hotel['HotelId'];
            $name=$hotel['Name'];
            $cat=(int)$hotel['Category'];

            $abstractHotel=new AbstractHotel($hotid,$name,$cat);
            return $abstractHotel;
            
        }
        
/*
        $abstractHotel=new AbstractHotel($hotelID,'Bawana Recidency',4);
        return $abstractHotel;
        */
    }

    public static function GetHotelById($Id)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            
            $query1='SELECT * FROM Hotel WHERE HotelId=?';
            $query2='SELECT Path FROM RoomPics WHERE HotelId=?';
            $query3='SELECT Name FROM OtherFacilities WHERE HotelId=?';

            $statement1=$db->prepare($query1);
            $statement2=$db->prepare($query2);
            $statement3=$db->prepare($query3);

            $statement1->bindValue(1,$Id);
            $statement1->execute();
            $hotel=$statement1->fetch();
            

            
            $k=0;
            $statement2->bindValue(1,$hotel['HotelId']);
            $statement2->execute();
            $imagesDB=$statement2->fetchAll();
            $images=array();
            foreach($imagesDB as $image)
            {
                $images[$k]=$image['Path'];
                $k++;
            }
                
            $k=0;
            $statement3->bindValue(1,$hotel['HotelId']);
            $statement3->execute();
            $facilitiesDB=$statement3->fetchAll();
            $facilities=array();
            foreach($facilitiesDB as $facility)
            {
                $facilities[$k]=$facility['Name'];
                $k++;
            }

            $checkIn=TimmingGrpManager::GetDateTimeFromTimestamp($hotel['CheckIn']);
            $checkOut=TimmingGrpManager::GetDateTimeFromTimestamp($hotel['CheckOut']);
            $timmings=new TimmingGrp($checkIn,$checkOut);

            $address=new AddressType($hotel['LineOne'],$hotel['Area']);
            $fac=new FacilityType((boolean)$hotel['WiFi'],(boolean)$hotel['Breakfast'],$facilities);

            $city=DestinationManager::GetCityFromCode($hotel['City']);

            $newHotel=new Hotel($hotel['HotelId'],$hotel['Name'],(int)$hotel['Category'],$address,$city,$hotel['Description'],(double)$hotel['Price'],$images,$timmings,$fac,(int)$hotel['Availability']);
            
            return $newHotel;

        }
    }

    public static function GetHotelsByCity($city)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $hotels=array();
            $cityCode=DestinationManager::GetCodeFromCity($city);
            $query1='SELECT * FROM Hotel WHERE City=?';
            $query2='SELECT Path FROM RoomPics WHERE HotelId=?';
            $query3='SELECT Name FROM OtherFacilities WHERE HotelId=?';

            $statement1=$db->prepare($query1);
            $statement2=$db->prepare($query2);
            $statement3=$db->prepare($query3);

            $statement1->bindValue(1,$cityCode);
            $statement1->execute();
            $hotelsDB=$statement1->fetchAll();
            $i=0;

            foreach($hotelsDB as $hotel)
            {
                $k=0;
                $statement2->bindValue(1,$hotel['HotelId']);
                $statement2->execute();
                $imagesDB=$statement2->fetchAll();
                $images=array();
                foreach($imagesDB as $image)
                {
                    $images[$k]=$image['Path'];
                    $k++;
                }
                
                $k=0;
                $statement3->bindValue(1,$hotel['HotelId']);
                $statement3->execute();
                $facilitiesDB=$statement3->fetchAll();
                $facilities=array();
                foreach($facilitiesDB as $facility)
                {
                    $facilities[$k]=$facility['Name'];
                    $k++;
                }

                $checkIn=TimmingGrpManager::GetDateTimeFromTimestamp($hotel['CheckIn']);
                $checkOut=TimmingGrpManager::GetDateTimeFromTimestamp($hotel['CheckOut']);
                $timmings=new TimmingGrp($checkIn,$checkOut);

                $address=new AddressType($hotel['LineOne'],$hotel['Area']);
                $fac=new FacilityType((boolean)$hotel['WiFi'],(boolean)$hotel['Breakfast'],$facilities);

                $hotels[$i]=new Hotel($hotel['HotelId'],$hotel['Name'],(int)$hotel['Category'],$address,$city,$hotel['Description'],(double)$hotel['Price'],$images,$timmings,$fac,(int)$hotel['Availability']);
                $i++;
                

            }

            return $hotels;

        }
        /*
        $hotels=array();

        $img=array('img1','img2','img3');
        $time=new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00'));

        $hotels[0]=new Hotel('H160','Bhawana Residency',4,new AddressType('123 A Street','Narela'),$city,'lorem ipsum set amir',993.1,$img,$time,new FacilityType(true,true,array('lorem','ipsum')),60);
        $hotels[1]=new Hotel('H160','Bhawana Residency',4,new AddressType('123 A Street','Narela'),$city,'lorem ipsum set amir',993.1,$img,$time,new FacilityType(true,true,array('lorem','ipsum')),60);
        $hotels[2]=new Hotel('H160','Bhawana Residency',4,new AddressType('123 A Street','Narela'),$city,'lorem ipsum set amir',993.1,$img,$time,new FacilityType(true,true,array('lorem','ipsum')),60);
        $hotels[3]=new Hotel('H160','Bhawana Residency',4,new AddressType('123 A Street','Narela'),$city,'lorem ipsum set amir',993.1,$img,$time,new FacilityType(true,true,array('lorem','ipsum')),60);
        $hotels[4]=new Hotel('H160','Bhawana Residency',4,new AddressType('123 A Street','Narela'),$city,'lorem ipsum set amir',993.1,$img,$time,new FacilityType(true,true,array('lorem','ipsum')),60);

        return $hotels;
        */
    }
}
?>
