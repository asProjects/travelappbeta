<?php

class TimmingGrp
{
    public $Arrival;
    public $Departure;

    public function __construct($arrival,$departure)
    {
        $this->Arrival=$arrival;
        $this->Departure=$departure;
    }

    
}

class TimmingGrpManager
{

    public static function GetTimestampRegEx($timmings)
    {
        //$timestamp=$timmings->Arrival;
        $timestamp=$timmings->Arrival->getTimestamp();
        $arrival="/Date(${timestamp}000+0530)/";
        //$timestamp=$timmings->Departure;
        $timestamp=$timmings->Departure->getTimestamp();
        $departure="/Date(${timestamp}000+0530)/";

        $timestampRegEx=new TimmingGrp($arrival,$departure);

        return $timestampRegEx;
    }

    public static function GetTimestampRegExInd($timmings)
    {
        //$timestamp=$timmings;
        $timestamp=$timmings->getTimestamp();
        $arrival="/Date(${timestamp}000+0530)/";
        
        $timestampRegEx=$arrival;

        return $timestampRegEx;
    }

    public static function GetDateTimeFromTimestampRegEx($timestampRegEx)
    {
        $timestamp=substr($timestampRegEx,6,10);
        
        //$dateTime=$timestamp;
        $dateTime=new DateTime('@'.$timestamp);
        //$dateTime->setTimestamp('@'.$timestamp);

        return $dateTime;
    }

    public static function GetDateTimeFromTimestamp($timestampRegEx)
    {
        $timestamp=$timestampRegEx;
        //$timestamp=substr($timestampRegEx,6,10);
        
        //$dateTime=$timestamp;
        $dateTime=new DateTime('@'.$timestamp);
        //$dateTime->setTimestamp('@'.$timestamp);

        return $dateTime;
    }

    public static function GetTimmingsFromTimestampRegEx($timestampTimmings)
    {
        $timestamp=substr($timestampTimmings->Arrival,6,10);
        //$arrival=$timestamp;
        $arrival=new DateTime('@'.$timestamp);
        
        //$arrival->setTimestamp('@'.$timestamp);

        $timestamp=substr($timestampTimmings->Departure,6,10);
        //$departure=$timestamp;
        $departure=new DateTime('@'.$timestamp);
        //$departure->setTimestamp('@'.$timestamp);

        $timmings=new TimmingGrp($arrival,$departure);
        return $timmings;
    }

    public static function GetTimmingsFromTimestamp($timestampTimmings)
    {
        $timestamp=$timestampTimmings->Arrival;
        //$timestamp=substr($timestampTimmings->Arrival,6,10);
        //$arrival=$timestamp;
        $arrival=new DateTime('@'.$timestamp);
        
        //$arrival->setTimestamp('@'.$timestamp);

        $timestamp=$timestampTimmings->Departure;
        //$timestamp=substr($timestampTimmings->Departure,6,10);
        //$departure=$timestamp;
        $departure=new DateTime('@'.$timestamp);
        //$departure->setTimestamp('@'.$timestamp);

        $timmings=new TimmingGrp($arrival,$departure);
        return $timmings;
    }


}

?>