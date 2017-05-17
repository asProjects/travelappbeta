<?php

class ClassFare
{
    public $EconomyFare;
    public $BuisnessFare;

    public function __construct($economyFare,$buisnessFare)
    {
        $this->EconomyFare=$economyFare;
        $this->BuisnessFare=$buisnessFare;
    }
}

class ClassAvailability
{
    public $EconomySeats;
    public $BuisnessSeats;

    public function __construct($economySeats,$buisnessSeats)
    {
        $this->EconomySeats=$economySeats;
        $this->BuisnessSeats=$buisnessSeats;
    }
}

class Flight
{
    private $flightNo;
    private $company;
    private $fromCityCode;
    private $toCityCode;
    
    private $timmings;
    private $classFares;
    private $availability;

    public function GetFlightNo()
    {
        return $this->flightNo;
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
    public function GetClassFares()
    {
        return $this->classFares;
    }
    public function GetAvailability()
    {
        return $this->availability;
    }



    public function __construct($flightNo,$company,$fromCityCode,$toCityCode,$timmings,$classFares,$availability)
    {
        $this->flightNo=$flightNo;
        $this->company=$company;
        $this->fromCityCode=$fromCityCode;
        $this->toCityCode=$toCityCode;

        $this->timmings=$timmings;
        $this->classFares=$classFares;
        $this->availability=$availability;   
    }
}


class FlightCompressed
{
    public $FlightNo;
    public $Company;
    public $FromCityCode;
    public $ToCityCode;
    
    public $Timmings;
    public $Fare;
    public $Availability;

}

class FlightManager
{

    public static function GetCompressedFlight($flight)
    {
        $compressedFlight=new FlightCompressed();
        $compressedFlight->FlightNo=$flight->GetFlightNo();
        $compressedFlight->Company=$flight->GetCompany();
        $compressedFlight->FromCityCode=$flight->GetFromCityCode();
        $compressedFlight->ToCityCode=$flight->GetToCityCode();
        $compressedFlight->Timmings=TimmingGrpManager::GetTimestampRegEx($flight->GetTimmings());
        $compressedFlight->Fare=$flight->GetClassFares();
        $compressedFlight->Availability=$flight->GetAvailability();

        return $compressedFlight;
    }

    public static function GetFlightsByCity($from,$to)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $flights=array();

            $fromCode=DestinationManager::GetCodeFromCity($from);
            $toCode=DestinationManager::GetCodeFromCity($to);
            $query='SELECT * FROM Flight WHERE FromCityCode=? and ToCityCode=?';
            $statement=$db->prepare($query);
            $statement->bindValue(1,$fromCode);
            $statement->bindValue(2,$toCode);

            $statement->execute();
            $flightsDB=$statement->fetchAll();

            $i=0;
            foreach($flightsDB as $flight)
            {
                $arrival=TimmingGrpManager::GetDateTimeFromTimestamp($flight['Arrival']);
                $departure=TimmingGrpManager::GetDateTimeFromTimestamp($flight['Departure']);
                $timmings=new TimmingGrp($departure,$arrival);
                $flights[$i]=new Flight($flight['FlightNo'],$flight['Company'],$from,$to,$timmings,new ClassFare((double)$flight['EconomyFare'],(double)$flight['BuisnessFare']),new ClassAvailability((int)$flight['EconomySeats'],(int)$flight['BuisnessSeats']));
                $i++;
            }   

            return $flights;

        }
        

        /*
        $flights=array();
        $flights[0]=new Flight('A380','Indigo',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),new ClassFare(757.4, 999.3),new ClassAvailability(100,100));
        $flights[1]=new Flight('A380','Indigo',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),new ClassFare(757.4, 999.3),new ClassAvailability(100,100));
        $flights[2]=new Flight('A380','Indigo',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),new ClassFare(757.4, 999.3),new ClassAvailability(100,100));
        $flights[3]=new Flight('A380','Indigo',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),new ClassFare(757.4, 999.3),new ClassAvailability(100,100));
        $flights[4]=new Flight('A380','Indigo',$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),new ClassFare(757.4, 999.3),new ClassAvailability(100,100));
        */

        return $flights;
    }
}

?>