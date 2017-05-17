<?php

class PackageBooking
{
    private $bookingId;
    private $userName;
    private $packageId;
    private $transport;
    private $hotel;

    private $dot;
    private $dob;

    public function GetBookingId()
    {
        return $this->bookingId;
    }
    public function GetUserName()
    {
        return $this->userName;
    }
    public function GetPackageId()
    {
        return $this->packageId;
    }
    public function GetTransport()
    {
        return $this->transport;
    }
    public function GetHotel()
    {
        return $this->hotel;
    }

    public function GetDOT()
    {
        return $this->dot;
    }
    public function GetDOB()
    {
        return $this->dob;
    }

    public function __construct($bookingId,$userName,$packageId,$transport,$hotel,$dot,$dob)
    {
        $this->bookingId=$bookingId;
        $this->userName=$userName;
        $this->packageId=$packageId;
        $this->transport=$transport;
        $this->hotel=$hotel;

        $this->dot=$dot;
        $this->dob=$dob;
    }

}

class PackageBookingCompressed
{
    public $BookingId;
    public $UserName;
    public $PackageId;
    public $Transport;
    public $Hotel;

    public $DOT;
    public $DOB;
}

class PackageBookingManager
{
    public static function GetCompressedPackageBooking($booking)
    {
        $compressedBooking=new PackageBookingCompressed();

        $compressedBooking->BookingId=$booking->GetBookingId();
        $compressedBooking->UserName=$booking->GetUserName();
        $compressedBooking->PackageId=$booking->GetPackageId();
        $compressedBooking->Transport=TicketManager::GetCompressedTicket($booking->GetTransport());
        $compressedBooking->Hotel=HotelBookingManager::GetCompressedHotelBookings($booking->GetHotel());

        $compressedBooking->DOT=TimmingGrpManager::GetTimestampRegExInd($booking->GetDOT());
        $compressedBooking->DOB=TimmingGrpManager::GetTimestampRegExInd($booking->GetDOB());

        return $compressedBooking;

    }

    public static function GetPackageBookingsByUserName($username)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;

        }
        else
        {
            $bookings=array();

            $query='SELECT * FROM PackageBook WHERE UserName=?';

            $statement=$db->prepare($query);

            $statement->bindValue(1,$username);
            $statement->execute();

            $bookingsDB=$statement->fetchAll();

            $i=0;
            foreach($bookingsDB as $book)
            {
                $tickets=TicketManager::GetTicketsByPackage($book['Package'],$username,$book['DOB']);
                $SelectedTicket=$tickets[0];

                $hotels=HotelBookingManager::GetHotelBookingByPackage($book['Package'],$username,$book['DOB']);
                $SelectedHotel=$hotels[0];

                $Dob=TimmingGrpManager::GetDateTimeFromTimestamp($book['DOB']);
                $Dot=TimmingGrpManager::GetDateTimeFromTimestamp($book['DOT']);

                $bookings[$i]=new PackageBooking($book['BookingNo'],$username,$book['Package'],$SelectedTicket,$SelectedHotel,$Dot,$Dob);

            }

            return $bookings;


        }
        
        /*
        $bookings=array();

        $timmings=new TimmingGrp(new DateTime('2016-10-12 07:00:00'),new DateTime('2016-10-12 15:20:00'));

        $i=0;

        $passengers[0]=new TicketFormat('Aman',32,'ASD123',++$i);
        $passengers[1]=new TicketFormat('Aman',32,'ASD123',++$i);
        $passengers[2]=new TicketFormat('Aman',32,'ASD123',++$i);

        $transport=new Ticket('TKT101',$username,'Flight','','A380','','','AC',new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'),$timmings,$passengers);

        $hotel=new HotelBooking('BK123',$username,'H640','',5,6,3,array(4,6,2,),new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00')); 

        $bookings[0]=new PackageBooking('BK555',$username,'PKG234',$transport,$hotel,new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[1]=new PackageBooking('BK555',$username,'PKG234',$transport,$hotel,new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[2]=new PackageBooking('BK555',$username,'PKG234',$transport,$hotel,new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[3]=new PackageBooking('BK555',$username,'PKG234',$transport,$hotel,new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[4]=new PackageBooking('BK555',$username,'PKG234',$transport,$hotel,new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[5]=new PackageBooking('BK555',$username,'PKG234',$transport,$hotel,new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[6]=new PackageBooking('BK555',$username,'PKG234',$transport,$hotel,new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[7]=new PackageBooking('BK555',$username,'PKG234',$transport,$hotel,new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[8]=new PackageBooking('BK555',$username,'PKG234',$transport,$hotel,new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));
        $bookings[9]=new PackageBooking('BK555',$username,'PKG234',$transport,$hotel,new DateTime('2016-10-22 00:00:00'),new DateTime('2016-10-12 00:00:00'));


        return $bookings;
        */
    }

    public static function BookPackage($package)
    {

        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $query='INSERT INTO PackageBook(BookingNo,UserName,Package,DOB,DOT) VALUES(?,?,?,?,?)';
            $statement=$db->prepare($query);

            $statement->bindValue(2,$package->UserName);
            $statement->bindValue(3,$package->PackageId);
            $statement->bindValue(4,substr($package->DOB,6,10));
            $statement->bindValue(5,substr($package->DOT,6,10));

            $bookingId="";

            $flag=0;
            while($flag==0)
            {
                $randVar=mt_rand(100,999);
                $bookingId="BK$randVar";
                $statement->bindValue(1,$bookingId);
                if($statement->execute()==true)
                {
                    $flag=1;
                }
            }

            $transport=TicketManager::ReserveTicket($package->Transport);
            $hotel=HotelBookingManager::BookHotel($package->Hotel);

            $dot=TimmingGrpManager::GetDateTimeFromTimestampRegEx($package->DOT);
            $dob=TimmingGrpManager::GetDateTimeFromTimestampRegEx($package->DOB);

            $newBooking=new PackageBooking($bookingId,$package->UserName,$package->PackageId,$transport,$hotel,$dot,$dob);

            return $newBooking;
        
        }

        

    }

}

?>
