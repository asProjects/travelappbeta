<?php

class HotelBooking
{
    private $bookingNo;
    private $userName;
    private $hotelId;
    private $packageId;

    private $duration;
    private $noOfHeads;
    private $noOfRooms;

    private $roomNos;

    private $dos;
    private $dob;

    public function GetBookingNo()
    {
        return $this->bookingNo;
    }
    public function GetUserName()
    {
        return $this->userName;
    }
    public function GetHotelId()
    {
        return $this->hotelId;
    }
    public function GetPackageId()
    {
        return $this->packageId;
    }

    public function GetDuration()
    {
        return $this->duration;
    }
    public function GetNoOfHeads()
    {
        return $this->noOfHeads;
    }
    public function GetNoOfRooms()
    {
        return $this->noOfRooms;
    }

    public function GetRoomNos()
    {
        return $this->roomNos;
    }

    public function GetDOS()
    {
        return $this->dos;
    }
    public function GetDOB()
    {
        return $this->dob;
    }


    public function __construct($bookingNo,$userName,$hotelId,$packageId,$duration,$noOfHeads,$noOfRooms,$roomNos,$dos,$dob)
    {
        $this->bookingNo=$bookingNo;
        $this->userName=$userName;
        $this->hotelId=$hotelId;
        $this->packageId=$packageId;

        $this->duration=$duration;
        $this->noOfHeads=$noOfHeads;
        $this->noOfRooms=$noOfRooms;

        $this->roomNos=$roomNos;

        $this->dos=$dos;
        $this->dob=$dob;

    }

}

class HotelBookingCompressed
{
    public $BookingNo;
    public $UserName;
    public $HotelId;
    public $PackageId;

    public $Duration;
    public $NoOfHeads;
    public $NoOfRooms;

    public $RoomNos;

    public $DOS;
    public $DOB;
}


class HotelBookingManager
{
    public static function GetCompressedHotelBookings($booking)
    {
        $compressedBooking=new HotelBookingCompressed();

        $compressedBooking->BookingNo=$booking->GetBookingNo();
        $compressedBooking->UserName=$booking->GetUserName();
        $compressedBooking->HotelId=$booking->GetHotelId();
        $compressedBooking->PackageId=$booking->GetPackageId();
        
        $compressedBooking->Duration=$booking->GetDuration();
        $compressedBooking->NoOfHeads=$booking->GetNoOfHeads();
        $compressedBooking->NoOfRooms=$booking->GetNoOfRooms();

        $compressedBooking->RoomNos=$booking->GetRoomNos();

        $compressedBooking->DOS=TimmingGrpManager::GetTimestampRegExInd($booking->GetDOS());
        $compressedBooking->DOB=TimmingGrpManager::GetTimestampRegExInd($booking->GetDOB());

        return $compressedBooking;

    }

    public static function GetHotelBookingByPackage($packageName,$username,$dobS)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $bookings=array();

            $query1='SELECT * FROM HotelBook WHERE UserName=? and Package=? and DOB=?';
            $query2='SELECT RoomNo FROM RoomNos WHERE BookingNo=?';

            $statement1=$db->prepare($query1);
            $statement2=$db->prepare($query2);

            $statement1->bindValue(1,$username);
            $statement1->bindValue(2,$packageName);
            $statement1->bindValue(3,$dobS);
            $statement1->execute();
            $bookingsDB=$statement1->fetchAll();

            $i=0;
            foreach($bookingsDB as $booking)
            {
                $roomNos=array();
                $statement2->bindValue(1,$booking['BookingNo']);
                $statement2->execute();
                $roomNosDB=$statement2->fetchAll();

                $k=0;
                foreach($roomNosDB as $roomNo)
                {
                    $roomNos[$k]=(int)$roomNo['RoomNo'];
                    $k++;
                }

                $dob=TimmingGrpManager::GetDateTimeFromTimestamp($booking['DOB']);
                $dos=TimmingGrpManager::GetDateTimeFromTimestamp($booking['DOS']);

                $bookings[$i]=new HotelBooking($booking['BookingNo'],$username,$booking['Hotel'],$booking['Package'],(int)$booking['Duration'],(int)$booking['NoOfHeads'],(int)$booking['NoOfRooms'],$roomNos,$dob,$dos);
                $i++;

            }

            return $bookings;


        }
        
    }





    public static function GetHotelBookingByUsername($username)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $bookings=array();

            $query1='SELECT * FROM HotelBook WHERE UserName=?';
            $query2='SELECT RoomNo FROM RoomNos WHERE BookingNo=?';

            $statement1=$db->prepare($query1);
            $statement2=$db->prepare($query2);

            $statement1->bindValue(1,$username);
            $statement1->execute();
            $bookingsDB=$statement1->fetchAll();

            $i=0;
            foreach($bookingsDB as $booking)
            {
                $roomNos=array();
                $statement2->bindValue(1,$booking['BookingNo']);
                $statement2->execute();
                $roomNosDB=$statement2->fetchAll();

                $k=0;
                foreach($roomNosDB as $roomNo)
                {
                    $roomNos[$k]=(int)$roomNo['RoomNo'];
                    $k++;
                }

                $dob=TimmingGrpManager::GetDateTimeFromTimestamp($booking['DOB']);
                $dos=TimmingGrpManager::GetDateTimeFromTimestamp($booking['DOS']);

                $bookings[$i]=new HotelBooking($booking['BookingNo'],$username,$booking['Hotel'],$booking['Package'],(int)$booking['Duration'],(int)$booking['NoOfHeads'],(int)$booking['NoOfRooms'],$roomNos,$dob,$dos);
                $i++;

            }

            return $bookings;


        }
        /*
        $bookings=array();

        $bookings[0]=new HotelBooking('BK123',$username,'H640','',5,6,3,array(4,6,2,),new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[1]=new HotelBooking('BK123',$username,'H640','',5,6,3,array(4,6,2,),new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[2]=new HotelBooking('BK123',$username,'H640','',5,6,3,array(4,6,2,),new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[3]=new HotelBooking('BK123',$username,'H640','',5,6,3,array(4,6,2,),new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));

        return $bookings;
        */
    }

    public static function BookHotel($hotel)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $query1='INSERT INTO HotelBook(BookingNo,UserName,Hotel,Package,Duration,NoOfHeads,NoOfRooms,DOB,DOS) VALUES(?,?,?,?,?,?,?,?,?)';
            $query2='INSERT INTO RoomNos(BookingNo,RoomNo) VALUES(?,?)';
            $statement1=$db->prepare($query1);
            $statement2=$db->prepare($query2);
            
            $bookingId;

            $statement1->bindValue(2,$hotel->UserName);
            $statement1->bindValue(3,$hotel->HotelId);
            $statement1->bindValue(4,$hotel->PackageId);
            $statement1->bindValue(5,$hotel->Duration);
            $statement1->bindValue(6,$hotel->NoOfHeads);
            $statement1->bindValue(7,$hotel->NoOfRooms);
            $statement1->bindValue(8,substr($hotel->DOB,6,10));
            $statement1->bindValue(9,substr($hotel->DOS,6,10));

            $flag=0;
            while($flag==0)
            {
                $randNum=mt_rand(100,999);
                $bookingId="BK$randNum";
                $statement1->bindValue(1,$bookingId);
                if($statement1->execute()==true)
                {
                    $flag=1;
                }
            }

            $statement2->bindValue(1,$bookingId);

            $roomNos=array();
            $roomNos[0]=mt_rand(100,900);

            $statement2->bindValue(2,$roomNos[0]);
            $statement2->execute();

            for($i=1;$i<$hotel->NoOfRooms;$i++)
            {
                $incr=mt_rand(1,4);
                $roomNos[$i]=$roomNos[$i-1]+$incr;
                $statement2->bindValue(2,$roomNos[$i]);
                $statement2->execute();
            }
        
            $newBooking=new HotelBooking($bookingId,$hotel->UserName,$hotel->HotelId,$hotel->PackageId,$hotel->Duration,$hotel->NoOfHeads,$hotel->NoOfRooms,$roomNos,TimmingGrpManager::GetDateTimeFromTimestampRegEx($hotel->DOS),TimmingGrpManager::GetDateTimeFromTimestampRegEx($hotel->DOB));

            return $newBooking;
        }
        
    }
}

?>