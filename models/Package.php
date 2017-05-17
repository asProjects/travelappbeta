<?php
class Package
{
    private $packageID;
    private $name;
    private $destination;
    private $description;
    private $duration;
    private $basePrice;
    private $images;
    private $hotel;

    private $transportType;

    public function GetPackageID()
    {
        return $this->packageID;
    }
    public function GetName()
    {
        return $this->name;
    }
    public function GetDestination()
    {
        return $this->destination;
    }
    public function GetDescription()
    {
        return $this->description;
    }
    public function GetDuration()
    {
        return $this->duration;
    }
    public function GetBasePrice()
    {
        return $this->basePrice;
    }
    public function GetImages()
    {
        return $this->images;
    }
    public function GetHotel()
    {
        return $this->hotel;
    }
    public function GetTransportType()
    {
        return $this->transportType;
    }


    public function __construct($packageID,$name,$destination,$description,$duration,$basePrice,$images,$transportType)
    {
        $this->packageID=$packageID;
        $this->name=$name;
        $this->destination=$destination;
        $this->description=$description;
        $this->duration=$duration;
        $this->basePrice=$basePrice;
        $this->images=$images;

        $this->transportType=$transportType;
    }

    public function SetHotel($hotel)
    {
        $this->hotel=$hotel;
    }

}

class PackageCompressed
{
    public $PackageID;
    public $Name;
    public $Destination;
    public $Description;
    public $Duration;
    public $BasePrice;
    public $Images;
    public $Hotel;
    public $TransportType;
}


class Packagemanager
{
    public static function GetCompressedPackage($package)
    {
        $compressedPackage=new PackageCompressed();
        $compressedPackage->PackageID=$package->GetPackageID();
        $compressedPackage->Name=$package->GetName();
        $compressedPackage->Destination=$package->GetDestination();
        $compressedPackage->Description=$package->GetDescription();
        $compressedPackage->Duration=$package->GetDuration();
        $compressedPackage->BasePrice=$package->GetBasePrice();
        $compressedPackage->Images=$package->GetImages();
        $hotel=$package->GetHotel();
        $compressedPackage->Hotel=HotelManager::GetCompressedAbstractHotel($hotel);
        $compressedPackage->TransportType=$package->GetTransportType();
            
        return $compressedPackage;
    }

    public static function GetPackagesByCity($destination)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $cityCode=DestinationManager::GetCodeFromCity($destination);
            $images=DestinationManager::GetImagesByCity($cityCode);

            $query='SELECT * FROM Package WHERE Destination=?';
            $statement=$db->prepare($query);

            $statement->bindValue(1,$cityCode);
            $statement->execute();

            $packagesDB=$statement->fetchAll();

            

            $packages= array();
            $i=0;
            foreach($packagesDB as $package)
            {
                $hotel=HotelManager::GetAbstractHotelByID($package['Hotel']);
                $packages[$i]=new Package($package['PackageId'],$package['Name'],$destination,$package['Description'],(int)$package['Duration'],(double)$package['BasePrice'],$images,$package['TransportType']);
                $packages[$i]->SetHotel($hotel);
                $i++;        
            }

            return $packages;
        }    
        /*$destination='MUM';
            
            $packages= array();
        $images=DestinationManager::GetImagesByCity($destination);
        $hotel=HotelManager::GetAbstractHotelByID('HTL230');
        $packages[0]=new Package('P280','Sigapore Dreams',$destination,'lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[0]->SetHotel($hotel);
        $packages[1]=new Package('P280','Sigapore Dreams',$destination,'lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[1]->SetHotel($hotel);
        $packages[2]=new Package('P280','Sigapore Dreams',$destination,'lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[2]->SetHotel($hotel);
        $packages[3]=new Package('P280','Sigapore Dreams',$destination,'lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[3]->SetHotel($hotel);
        $packages[4]=new Package('P280','Sigapore Dreams',$destination,'lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[4]->SetHotel($hotel);
        $packages[5]=new Package('P280','Sigapore Dreams',$destination,'lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[5]->SetHotel($hotel);

        return $packages;*/
            //All Query Code Here
        
    }

    public static function GetFeaturedPackages()
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            

            $query='SELECT * FROM FeaturedPackage';
            $statement=$db->prepare($query);

            $statement->execute();

            $packagesDB=$statement->fetchAll();
            

            $packages= array();
            $i=0;
            foreach($packagesDB as $package)
            {
                $destination=DestinationManager::GetCityFromCode($package['Destination']);
                $images=DestinationManager::GetImagesByCity($package['Destination']);
                $hotel=HotelManager::GetAbstractHotelByID($package['Hotel']);
                $packages[$i]=new Package($package['PackageId'],$package['Name'],$destination,$package['Description'],(int)$package['Duration'],(double)$package['BasePrice'],$images,$package['TransportType']);
                $packages[$i]->SetHotel($hotel);
                $i++;        
            }

            return $packages;
        }

        /*
        $packages= array();
        $images=DestinationManager::GetImagesByCity('Singapore');
        $hotel=HotelManager::GetAbstractHotelByID('H160');
        $packages[0]=new Package('P280','Sigapore Dreams','Singapore','lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[0]->SetHotel($hotel);
        $packages[1]=new Package('P280','Sigapore Dreams','Singapore','lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[1]->SetHotel($hotel);
        $packages[2]=new Package('P280','Sigapore Dreams','Singapore','lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[2]->SetHotel($hotel);
        $packages[3]=new Package('P280','Sigapore Dreams','Singapore','lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[3]->SetHotel($hotel);
        $packages[4]=new Package('P280','Sigapore Dreams','Singapore','lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[4]->SetHotel($hotel);
        $packages[5]=new Package('P280','Sigapore Dreams','Singapore','lorem ipsum,dolor sit amet',5,999.2,$images,'Flight');
        $packages[5]->SetHotel($hotel);

        return $packages;
        */
    }
    
} 
?>
