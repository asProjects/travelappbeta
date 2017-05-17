<?php

class TicketFormat
{
    public $Name;
    public $Age;
    public $Id;
    public $Seat;

    public function __construct($name,$age,$id,$seat)
    {
        $this->Name=$name;
        $this->Age=$age;
        $this->Id=$id;
        $this->Seat=$seat;
    }
}

class Ticket
{
    private $ticketNo;
    private $userName;
    private $type;

    private $packageNo;
    private $flightNo;
    private $trainNo;
    private $busNo;

    private $classCoach;
    private $dot;
    private $dob;

    private $timmings;
    private $passengers;

    public function GetTicketNo()
    {
        return $this->ticketNo;
    }
    public function GetUserName()
    {
        return $this->userName;
    }
    public function GetType()
    {
        return $this->type;
    }

    public function GetPackageNo()
    {
        return $this->packageNo;
    }
    public function GetFlightNo()
    {
        return $this->flightNo;
    }
    public function GetTrainNo()
    {
        return $this->trainNo;
    }
    public function GetBusNo()
    {
        return $this->busNo;
    }

    public function GetClassCoach()
    {
        return $this->classCoach;
    }
    public function GetDOT()
    {
        return $this->dot;
    }
    public function GetDOB()
    {
        return $this->dob;
    }

    public function GetTimmings()
    {
        return $this->timmings;
    }
    public function GetPassengers()
    {
        return $this->passengers;
    }

    public function __construct($ticketNo,$username,$type,$packageNo,$flightNo,$trainNo,$busNo,$classCoach,$dot,$dob,$timmings,$passengers)
    {
        $this->ticketNo=$ticketNo;
        $this->userName=$username;
        $this->type=$type;

        $this->packageNo=$packageNo;
        $this->flightNo=$flightNo;
        $this->trainNo=$trainNo;
        $this->busNo=$busNo;

        $this->classCoach=$classCoach;
        $this->dot=$dot;
        $this->dob=$dob;

        $this->timmings=$timmings;
        $this->passengers=$passengers;

    }

}

class TicketCompressed
{
    public $TicketNo;
    public $UserName;
    public $Type;

    public $PackageNo;
    public $FlightNo;
    public $TrainNo;
    public $BusNo;

    public $ClassCoach;
    public $DOT;
    public $DOB;

    public $Timmings;
    public $Passengers;
}

class TicketManager
{
    public static function GetCompressedTicket($ticket)
    {
        $compressedTicket=new TicketCompressed();

        $compressedTicket->TicketNo=$ticket->GetTicketNo();
        $compressedTicket->UserName=$ticket->GetUserName();
        $compressedTicket->Type=$ticket->GetType();

        $compressedTicket->PackageNo=$ticket->GetPackageNo();
        $compressedTicket->FlightNo=$ticket->GetFlightNo();
        $compressedTicket->TrainNo=$ticket->GetTrainNo();
        $compressedTicket->BusNo=$ticket->GetBusNo();

        $compressedTicket->ClassCoach=$ticket->GetClassCoach();
        $compressedTicket->DOT=TimmingGrpManager::GetTimestampRegExInd($ticket->GetDOT());
        $compressedTicket->DOB=TimmingGrpManager::GetTimestampRegExInd($ticket->GetDOB());

        $compressedTicket->Timmings=TimmingGrpManager::GetTimestampRegEx($ticket->GetTimmings());
        $compressedTicket->Passengers=$ticket->GetPassengers();

        return $compressedTicket;

    }

    public static function GetTicketsByPackage($packageName,$username,$dobS)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $tickets=array();

            $query1='SELECT * FROM Reservation WHERE UserName=? and DOB=? and Package=?';
            $query2='SELECT Name FROM PassengerName WHERE TicketNo=?';
            $query3='SELECT Age FROM PassengerAge WHERE TicketNo=?';
            $query4='SELECT Id FROM PassengerId WHERE TicketNo=?';
            $query5='SELECT SeatNo FROM PassengerSeat WHERE TicketNo=?';

            $statement1=$db->prepare($query1);
            $statement2=$db->prepare($query2);
            $statement3=$db->prepare($query3);
            $statement4=$db->prepare($query4);
            $statement5=$db->prepare($query5);

            $statement1->bindValue(1,$username);
            $statement1->bindValue(2,$dobS);
            $statement1->bindValue(3,$packageName);
            $statement1->execute();
            $ticketsDB=$statement1->fetchAll();

            $i=0;
            foreach($ticketsDB as $ticket)
            {
                $passengers=array();

                $statement2->bindValue(1,$ticket['TicketNo']);
                $statement2->execute();
                $passengerNames=$statement2->fetchAll();

                $statement3->bindValue(1,$ticket['TicketNo']);
                $statement3->execute();
                $passengerAges=$statement3->fetchAll();

                $statement4->bindValue(1,$ticket['TicketNo']);
                $statement4->execute();
                $passengerIds=$statement4->fetchAll();

                $statement5->bindValue(1,$ticket['TicketNo']);
                $statement5->execute();
                $passengerSeats=$statement5->fetchAll();

                $count=count($passengerNames);
                for($k=0;$k<$count;$k++)
                {
                    $passengers[$k]=new TicketFormat($passengerNames[$k]['Name'],(int)$passengerAges[$k]['Age'],$passengerIds[$k]['Id'],(int)$passengerSeats[$k]['SeatNo']);
                }

                $type='';
               if($ticket['FlightNo']!=null)
               {
                   $type='Flight';
               }
               else if($ticket['TrainNo']!=null)
               {
                   $type='Train';
               }
               else if($ticket['BusNo']!=null)
               {
                   $type='Bus';
               }

                $arrival=TimmingGrpManager::GetDateTimeFromTimestamp($ticket['Arrival']);
                $departure=TimmingGrpManager::GetDateTimeFromTimestamp($ticket['Departure']);
                $timmings=new TimmingGrp($departure,$arrival);

                $dob=TimmingGrpManager::GetDateTimeFromTimestamp($ticket['DOB']);
                $dot=TimmingGrpManager::GetDateTimeFromTimestamp($ticket['DOT']);

                $tickets[$i]=new Ticket($ticket['TicketNo'],$username,$type,$ticket['Package'],$ticket['FlightNo'],$ticket['TrainNo'],$ticket['BusNo'],$ticket['ClassCoach'],$dot,$dob,$timmings,$passengers);
                $i++;

            }

            return $tickets;


        }

        

    }

    public static function GetTicketsByUserName($username,$type)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $tickets=array();

            $query1='SELECT * FROM Reservation WHERE UserName=? and Type=? and Package is null';
            $query2='SELECT Name FROM PassengerName WHERE TicketNo=?';
            $query3='SELECT Age FROM PassengerAge WHERE TicketNo=?';
            $query4='SELECT Id FROM PassengerId WHERE TicketNo=?';
            $query5='SELECT SeatNo FROM PassengerSeat WHERE TicketNo=?';

            $statement1=$db->prepare($query1);
            $statement2=$db->prepare($query2);
            $statement3=$db->prepare($query3);
            $statement4=$db->prepare($query4);
            $statement5=$db->prepare($query5);

            $statement1->bindValue(1,$username);
            $statement1->bindValue(2,$type);
            $statement1->execute();
            $ticketsDB=$statement1->fetchAll();

            $i=0;
            foreach($ticketsDB as $ticket)
            {
                $passengers=array();

                $statement2->bindValue(1,$ticket['TicketNo']);
                $statement2->execute();
                $passengerNames=$statement2->fetchAll();

                $statement3->bindValue(1,$ticket['TicketNo']);
                $statement3->execute();
                $passengerAges=$statement3->fetchAll();

                $statement4->bindValue(1,$ticket['TicketNo']);
                $statement4->execute();
                $passengerIds=$statement4->fetchAll();

                $statement5->bindValue(1,$ticket['TicketNo']);
                $statement5->execute();
                $passengerSeats=$statement5->fetchAll();

                $count=count($passengerNames);
                for($k=0;$k<$count;$k++)
                {
                    $passengers[$k]=new TicketFormat($passengerNames[$k]['Name'],(int)$passengerAges[$k]['Age'],$passengerIds[$k]['Id'],(int)$passengerSeats[$k]['SeatNo']);
                }

                
                $arrival=TimmingGrpManager::GetDateTimeFromTimestamp($ticket['Arrival']);
                $departure=TimmingGrpManager::GetDateTimeFromTimestamp($ticket['Departure']);
                $timmings=new TimmingGrp($departure,$arrival);

                $dob=TimmingGrpManager::GetDateTimeFromTimestamp($ticket['DOB']);
                $dot=TimmingGrpManager::GetDateTimeFromTimestamp($ticket['DOT']);

                $tickets[$i]=new Ticket($ticket['TicketNo'],$username,$type,$ticket['Package'],$ticket['FlightNo'],$ticket['TrainNo'],$ticket['BusNo'],$ticket['ClassCoach'],$dot,$dob,$timmings,$passengers);
                $i++;

            }

            return $tickets;


        }

        /*
        $tickets=array();

        $timmings=new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00'));

        $passengers=array();

        $i=0;

        $passengers[0]=new TicketFormat('Aman',32,'ASD123',++$i);
        $passengers[1]=new TicketFormat('Aman',32,'ASD123',++$i);
        $passengers[2]=new TicketFormat('Aman',32,'ASD123',++$i);

        $tickets[0]=new Ticket('TKT101',$username,$type,'','A380','','','AC',new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'),$timmings,$passengers);
        $tickets[1]=new Ticket('TKT101',$username,$type,'','A380','','','AC',new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'),$timmings,$passengers);
        $tickets[2]=new Ticket('TKT101',$username,$type,'','A380','','','AC',new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'),$timmings,$passengers);
        $tickets[3]=new Ticket('TKT101',$username,$type,'','A380','','','AC',new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'),$timmings,$passengers);
        $tickets[4]=new Ticket('TKT101',$username,$type,'','A380','','','AC',new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'),$timmings,$passengers);
        $tickets[5]=new Ticket('TKT101',$username,$type,'','A380','','','AC',new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'),$timmings,$passengers);

        return $tickets;
        */

    }

    public static function ReserveTicket($ticket)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $query1='INSERT INTO Reservation(TicketNo,UserName,Package,FlightNo,TrainNo,BusNo,Type,ClassCoach,DOB,DOT,Arrival,Departure) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)';
            $query2='INSERT INTO PassengerName(TicketNo,Name) VALUES(?,?)';
            $query3='INSERT INTO PassengerAge(TicketNo,Age) VALUES(?,?)';
            $query4='INSERT INTO PassengerId(TicketNo,Id) VALUES(?,?)';
            $query5='INSERT INTO PassengerSeat(TicketNo,SeatNo) VALUES(?,?)';

            $statement1=$db->prepare($query1);
            $statement2=$db->prepare($query2);
            $statement3=$db->prepare($query3);
            $statement4=$db->prepare($query4);
            $statement5=$db->prepare($query5);


            $ticketNo;

            $statement1->bindValue(2,$ticket->UserName);
            $statement1->bindValue(3,$ticket->PackageNo);
            $statement1->bindValue(4,$ticket->FlightNo);
            $statement1->bindValue(5,$ticket->TrainNo);
            $statement1->bindValue(6,$ticket->BusNo);
            $statement1->bindValue(7,$ticket->Type);
            $statement1->bindValue(8,$ticket->ClassCoach);
            $statement1->bindValue(9,substr($ticket->DOB,6,10));
            $statement1->bindValue(10,substr($ticket->DOT,6,10));
            $statement1->bindValue(11,substr($ticket->Timmings->Arrival,6,10));
            $statement1->bindValue(12,substr($ticket->Timmings->Departure,6,10));

            $flag=0;
            while($flag==0)
            {
                $randNum=mt_rand(100,999);
                $ticketNo="TKT$randNum";
                $statement1->bindValue(1,$ticketNo);
                if($statement1->execute()==true)
                {
                    $flag=1;
                }
            }
            

            $randVar=mt_rand(1,20);
            $passengers=array();
            $i=0;
            foreach($ticket->Passengers as $passenger)
            {
                $statement2->bindValue(1,$ticketNo);
                $statement2->bindValue(2,$passenger->Name);
                $statement2->execute();

                $statement3->bindValue(1,$ticketNo);
                $statement3->bindValue(2,$passenger->Age);
                $statement3->execute();

                $statement4->bindValue(1,$ticketNo);
                $statement4->bindValue(2,$passenger->Id);
                $statement4->execute();

                $incr=mt_rand(1,2);
                $randVar+=$incr;

                $statement5->bindValue(1,$ticketNo);
                $statement5->bindValue(2,$randVar);
                $statement5->execute();

                $passengers[$i]=new TicketFormat($passenger->Name,$passenger->Age,$passenger->Id,$randVar);
                $i++;
            }

            $dot=TimmingGrpManager::GetDateTimeFromTimestampRegEx($ticket->DOT);
            $dob=TimmingGrpManager::GetDateTimeFromTimestampRegEx($ticket->DOB);
            $timmings=TimmingGrpManager::GetTimmingsFromTimestampRegEx($ticket->Timmings);

            $newTicket=new Ticket($ticketNo,$ticket->UserName,$ticket->Type,$ticket->PackageNo,$ticket->FlightNo,$ticket->TrainNo,$ticket->BusNo,$ticket->ClassCoach,$dot,$dob,$timmings,$passengers);

            return $newTicket;
        }
        
    }

}

?>