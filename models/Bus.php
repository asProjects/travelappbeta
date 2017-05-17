<?php

abstract class BusCategory
{
    const Normal=0;
    const SemiVolvo=1;
    const Volvo=2;
}

class Bus
{
    private $busNo;
    private $company;
    private $fromCityCode;
    private $toCityCode;

    private $timmings;
    private $availability;
    private $category;
    private $fare;

    public function GetbusNo()
    {
        return $this->busNo;
    }
    public function GetCompany()
    {
        return $this->company;
    }
    public function GetFromCityCode()
    {
        return $this->fromCityCode;
    }
    public function GetToCityCode()
    {
        return $this->toCityCode;
    }

    public function GetTimmings()
    {
        return $this->timmings;
    }
    public function GetAvailability()
    {
        return $this->availability;
    }
    public function GetCategory()
    {
        return $this->category;
    }
    public function GetFare()
    {
        return $this->fare;
    }

    public function __construct($busNo,$company,$fromCityCode,$toCityCode,$timmings,$availability,$category,$fare)
    {
        $this->busNo=$busNo;
        $this->company=$company;
        $this->fromCityCode=$fromCityCode;
        $this->toCityCode=$toCityCode;

        $this->timmings=$timmings;
        $this->availability=$availability;
        $this->category=$category;
        $this->fare=$fare;   
    }

}


class BusCompressed
{
    public $BusNo;
    public $Company;
    public $FromCityCode;
    public $ToCityCode;
    
    public $Timmings;
    public $Availability;
    public $Category;
    public $Fare;
}

class BusManager
{

    public static function GetCompressedBus($bus)
    {
        $compressedBus=new busCompressed();
        $compressedBus->BusNo=$bus->GetBusNo();
        $compressedBus->Company=$bus->GetCompany();
        $compressedBus->FromCityCode=$bus->GetFromCityCode();
        $compressedBus->ToCityCode=$bus->GetToCityCode();
        $compressedBus->Timmings=TimmingGrpManager::GetTimestampRegEx($bus->GetTimmings());
        $compressedBus->Availability=$bus->GetAvailability();
        $compressedBus->Category=$bus->GetCategory();
        $compressedBus->Fare=$bus->Getfare();

        return $compressedBus;
    }

    public static function GetBusByCity($from,$to,$category)
    {

        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $buses=array();

            $fromCode=DestinationManager::GetCodeFromCity($from);
            $toCode=DestinationManager::GetCodeFromCity($to);
            $query='SELECT * FROM Bus WHERE FromCityCode=? and ToCityCode=? and Category=?';
            $statement=$db->prepare($query);
            $statement->bindValue(1,$fromCode);
            $statement->bindValue(2,$toCode);
            $statement->bindValue(3,$category);

            $statement->execute();
            $busesDB=$statement->fetchAll();

            $i=0;
            foreach($busesDB as $bus)
            {
                $arrival=TimmingGrpManager::GetDateTimeFromTimestamp($bus['Arrival']);
                $departure=TimmingGrpManager::GetDateTimeFromTimestamp($bus['Departure']);
                $timmings=new TimmingGrp($departure,$arrival);

                $cat;
                if($bus['Category']=='Normal')
                {
                    $cat=BusCategory::Normal;
                }
                else if($bus['Category']=='SemiVolvo')
                {
                    $cat=BusCategory::SemiVolvo;
                }
                else if($bus['Category']=='Volvo')
                {
                    $cat=BusCategory::Volvo;
                }

                $buses[$i]=new Bus($bus['BusNo'],$bus['Company'],$from,$to,$timmings,(int)$bus['Availability'],$cat,(double)$bus['Fare']);
                $i++;
            }   

            return $buses;

        }

        /*
        $buses=array();

        $buses[0]=new Bus('B260','Vivek',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),50,BusCategory::Normal,867.2);
        $buses[1]=new Bus('B260','Vivek',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),50,BusCategory::Normal,867.2);
        $buses[2]=new Bus('B260','Vivek',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),50,BusCategory::Normal,867.2);
        $buses[3]=new Bus('B260','Vivek',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),50,BusCategory::Normal,867.2);
        $buses[4]=new Bus('B260','Vivek',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),50,BusCategory::Normal,867.2);
        $buses[5]=new Bus('B260','Vivek',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),50,BusCategory::Normal,867.2);

        return $buses;
        */
    }
}


?>