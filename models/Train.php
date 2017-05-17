<?php

abstract class TrainCategory  //enum
{
    const Passenger=0;
    const Express=1;
    const SuperFast=2;
}

class Train
{
    private $trainNo;
    private $name;
    private $category;
    private $fromCityCode;
    private $toCityCode;

    private $timmings;
    private $class;
    private $fare;
    private $availability;

    private $distance;

    public function GetTrainNo()
    {
        return $this->trainNo;
    }
    public function GetName()
    {
        return $this->name;
    }
    public function GetCategory()
    {
        return $this->category;
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
    public function GetClass()
    {
        return $this->class;
    }
    public function GetFare()
    {
        return $this->fare;
    }
    public function GetAvailability()
    {
        return $this->availability;
    }

    public function GetDistance()
    {
        return $this->distance;
    }

    public function __construct($trainNo,$name,$category,$fromCityCode,$toCityCode,$timmings,$class,$fare,$availability,$distance)
    {
        $this->trainNo=$trainNo;
        $this->name=$name;
        $this->category=$category;
        $this->fromCityCode=$fromCityCode;
        $this->toCityCode=$toCityCode;

        $this->timmings=$timmings;
        $this->class=$class;
        $this->fare=$fare;
        $this->availability=$availability;

        $this->distance=$distance;   
    }
}

class TrainCompressed
{
    public $TrainNo;
    public $Name;
    public $Category;
    public $FromCityCode;
    public $ToCityCode;

    public $Timmings;
    public $Class;
    public $Fare;
    public $Availability;

    public $Distance;
}


class TrainManager
{
    public static function GetCompressedTrain($train)
    {
        $compressedTrain=new TrainCompressed();
        $compressedTrain->TrainNo=$train->GetTrainNo();
        $compressedTrain->Name=$train->GetName();
        $compressedTrain->Category=$train->GetCategory();
        $compressedTrain->FromCityCode=$train->GetFromCityCode();
        $compressedTrain->ToCityCode=$train->GetToCityCode();
        $compressedTrain->Timmings=TimmingGrpManager::GetTimestampRegEx($train->GetTimmings());
        $compressedTrain->Class=$train->GetClass();
        $compressedTrain->Fare=$train->GetFare();
        $compressedTrain->Availability=$train->GetAvailability();
        $compressedTrain->Distance=$train->GetDistance();

        return $compressedTrain;
    }

    public static function GetTrainsByCity($from,$to,$category)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $trains=array();
            $fromCityCode=DestinationManager::GetCodeFromCity($from);
            $toCityCode=DestinationManager::GetCodeFromCity($to);

            $query1='SELECT * FROM TrainSchedule WHERE FromCity=? and ToCity=?';
            $query2='SELECT * FROM TrainInfo WHERE TrainNo=? and LCASE(Category)=LCASE(?)';
            $statement1=$db->prepare($query1);
            $statement2=$db->prepare($query2);

            $statement1->bindValue(1,$fromCityCode);
            $statement1->bindValue(2,$toCityCode);

            $statement1->execute();

            $trainsDB=$statement1->fetchAll();
            $i=0;
            foreach($trainsDB as $train)
            {
                $distance=(int)$train['Distance'];
                $distanceForFare=(double)($train['Distance'].'.0001');
                $distance=abs($distance);
                $class=array();
                $fare=array();
                $availability=array();

                $statement2->bindValue(1,$train['TrainNo']);
                $statement2->bindValue(2,$category);
                $statement2->execute();
                $trainInfos=$statement2->fetchAll();

                $k=0;
                foreach($trainInfos as $trainInfo)
                {
                    $class[$k]=$trainInfo['Class'];
                    $fare[$k]=(double)$trainInfo['Price']*$distanceForFare;
                   // echo "  $fare[$k]  "; 
                    //$fare[$k]=$fare[$k];
                    //echo "  $distanceForFare  "; 
                    $availability[$k]=(int)$trainInfo['Seats'];
                    $k++;
                }

                $cat;
                if($trainInfos[0]['Category']=='Passenger')
                {
                    $cat=TrainCategory::Passenger;
                }
                else if($trainInfos[0]['Category']=='Express')
                {
                    $cat=TrainCategory::Express;
                }
                else if($trainInfos[0]['Category']=='SuperFast')
                {
                    $cat=TrainCategory::SuperFast;
                }

                
                $timmings;
                if($train['Distance']>0)
                {
                    $departure=TimmingGrpManager::GetDateTimeFromTimestamp($train['FromDayTime']);
                    $arrival=TimmingGrpManager::GetDateTimeFromTimestamp($train['ToDayTime']);
                    $timmings=new TimmingGrp($arrival,$departure);
                }
                else if($train['Distance']<0)
                {
                    $departure=TimmingGrpManager::GetDateTimeFromTimestamp($train['FromNightTime']);
                    $arrival=TimmingGrpManager::GetDateTimeFromTimestamp($train['ToNightTime']);
                    $timmings=new TimmingGrp($arrival,$departure);
                }

                $fromCity=DestinationManager::GetCityFromCode($fromCityCode);
                $toCity=DestinationManager::GetCityFromCode($toCityCode);

                $trains[$i]=new Train($train['TrainNo'],$trainInfos[0]['Name'],$cat,$fromCity,$toCity,$timmings,$class,$fare,$availability,$distance);
                $i++;
            }

            return $trains;

        }
        /*
        $trains=array();

        $trains[0]=new Train('T160','Shatabdi',TrainCategory::SuperFast,$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),array('Sleeper','Ac First','Ac Second'),array(454.3,962.8,524.4),array(70,70,70),400);
        $trains[1]=new Train('T160','Shatabdi',TrainCategory::SuperFast,$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),array('Sleeper','Ac First','Ac Second'),array(454.3,962.8,524.4),array(70,70,70),400);
        $trains[2]=new Train('T160','Shatabdi',TrainCategory::SuperFast,$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),array('Sleeper','Ac First','Ac Second'),array(454.3,962.8,524.4),array(70,70,70),400);
        $trains[3]=new Train('T160','Shatabdi',TrainCategory::SuperFast,$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),array('Sleeper','Ac First','Ac Second'),array(454.3,962.8,524.4),array(70,70,70),400);
        $trains[4]=new Train('T160','Shatabdi',TrainCategory::SuperFast,$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),array('Sleeper','Ac First','Ac Second'),array(454.3,962.8,524.4),array(70,70,70),400);
        $trains[5]=new Train('T160','Shatabdi',TrainCategory::SuperFast,$from,$to,new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00')),array('Sleeper','Ac First','Ac Second'),array(454.3,962.8,524.4),array(70,70,70),400);

        return $trains;
        */
    }
}

?>