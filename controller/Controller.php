<?php

class Controller
{

    public static function RequestFeaturedDestinations()
    {
        $destinations=DestinationManager::GetFeaturedDestinations();

        $compressedDestinations=array();

        $i=0;
        foreach($destinations as $destination)
        {
            $compressedDestinations[$i]=DestinationManager::GetCompressedDestination($destination);
            $i++;
        }

        JsonSerializer::SerializeAndSend($compressedDestinations);
    }

    public static function RequestDestinationsLike($like)
    {
        $destinations=DestinationManager::GetDestinationsLike($like);

        $compressedDestinations=array();

        $i=0;
        foreach($destinations as $destination)
        {
            $compressedDestinations[$i]=DestinationManager::GetCompressedDestination($destination);
            $i++;
        }

        JsonSerializer::SerializeAndSend($compressedDestinations);
    }

    public static function RequestFlightsByCity($from,$to)
    {
        $flights=FlightManager::GetFlightsByCity($from,$to);
                    
        $compressedFlights=array();

        $i=0;
        foreach($flights as $flight)
        {
            $compressedFlights[$i]=FlightManager::GetCompressedFlight($flight);
            $i++;

        }
        
        JsonSerializer::SerializeAndSend($compressedFlights);
    }

    public static function Login($username,$password)
    {
        $user=UserManager::Login($username,$password);
        if(!is_object($user))
        {
            ResultSender::SendResult($user);
        }
        else
        {
            $compressedUser=UserManager::GetCompressedUser($user);
               
            JsonSerializer::SerializeAndSendInd($compressedUser);
        }
                    
        
    }

    public static function Signup($userJson,$password)
    {
        $user=jsonSerializer::DeSerialize($userJson);
        $result=UserManager::Signup($user,$password);
        
        ResultSender::SendResult($result);
    }

    public static function BookHotel($hotelJson)
    {
        $hotel=jsonSerializer::DeSerialize($hotelJson);
        $newBooking=HotelBookingManager::BookHotel($hotel);
        
        $newCompressedBooking=HotelBookingManager::GetCompressedHotelBookings($newBooking);
        
        JsonSerializer::SerializeAndSendInd($newCompressedBooking);
    }

    public static function ReserveTicket($ticketJson)
    {
        $ticket=jsonSerializer::DeSerialize($ticketJson);
        $newTicket=TicketManager::ReserveTicket($ticket);
        
        $newCompressedTicket=TicketManager::GetCompressedTicket($newTicket);
        
        JsonSerializer::SerializeAndSendInd($newCompressedTicket);
    }

    public static function BookPackage($packageJson)
    {
        $package=jsonSerializer::DeSerialize($packageJson);
        $newBooking=PackageBookingManager::BookPackage($package);
        
        $newCompressedBooking=PackageBookingManager::GetCompressedPackageBooking($newBooking);
        
        JsonSerializer::SerializeAndSendInd($newCompressedBooking);
    }

    public static function RequestRegionByCityLike($city,$like)
    {
        $regions=DestinationManager::GetRegionsByCityLike($city,$like);
        
        JsonSerializer::SerializeAndSend($regions);
    }


    public static function Test($jsonString)
    {
        
        $obj=JsonSerializer::DeSerialize($jsonString);

        echo $obj->Objects[0]->FlightNo;
        echo "\n";
        echo $obj->Objects[0]->ClassFares->EconomyFare;
        echo "\n";
        $timmingsRegEx=$obj->Objects[0]->Timmings;
        $timmings=TimmingGrpManager::GetTimmingsFromTimestampRegEx($timmingsRegEx);
        echo $timmings->Arrival->format('M. j,Y \a\t g:i a');
        echo "\n";
        echo $timmings->Departure->format('M. j,Y \a\t g:i a');
    }

    public static function RequestHotelsByCity($city)
    {
        $hotels=HotelManager::GetHotelsByCity($city);

        $compressedHotels=array();

        $i=0;
        foreach($hotels as $hotel)
        {
            $compressedHotels[$i]=HotelManager::GetCompressedHotel($hotel);
            $i++;
        }

        JsonSerializer::SerializeAndSend($compressedHotels);
    }

    public static function RequestHotelById($Id)
    {
        $hotel=HotelManager::GetHotelById($Id);

        $compressedHotel=HotelManager::GetCompressedHotel($hotel);
        JsonSerializer::SerializeAndSendInd($compressedHotel);
    }

    public static function RequestTrainsByCity($from,$to,$cat)
    {
        $trains=TrainManager::GetTrainsByCity($from,$to,$cat);

        $compressedTrains=array();

        $i=0;
        foreach($trains as $train)
        {
            $compressedTrains[$i]=TrainManager::GetCompressedTrain($train);
            $i++;
        }

        JsonSerializer::SerializeAndSend($compressedTrains);
    }

    public static function RequestBusesByCity($from,$to,$cat)
    {
        $buses=BusManager::GetBusByCity($from,$to,$cat);

        $compressedBuses=array();

        $i=0;
        foreach($buses as $bus) 
        {
            $compressedBuses[$i]=BusManager::GetCompressedBus($bus);
            $i++;
        }

        JsonSerializer::SerializeAndSend($compressedBuses);
    }

    public static function RequestTicketsByUserName($username,$type)
    {
        $tickets=TicketManager::GetTicketsByUserName($username,$type);

        $compressedTickets=array();

        $i=0;
        foreach($tickets as $ticket) 
        {
            $compressedTickets[$i]=TicketManager::GetCompressedTicket($ticket);
            $i++;
        }

        JsonSerializer::SerializeAndSend($compressedTickets);
    }

    public static function RequestPackageBookingByUserName($username)
    {
        $bookings=PackageBookingManager::GetPackageBookingsByUserName($username);

        $compressedBookings=array();

        $i=0;
        foreach($bookings as $booking) 
        {
            $compressedBookings[$i]=PackageBookingManager::GetCompressedPackageBooking($booking);
            $i++;
        }

        JsonSerializer::SerializeAndSend($compressedBookings);
    }

    public static function RequestPackagesByCity($destination)
    {
        $packages=PackageManager::GetPackagesByCity($destination);

        $smallPackages=array();

        $i=0;
        foreach($packages as $package)
        {
            $smallPackages[$i]=PackageManager::GetCompressedPackage($package);
            $i++;
        }

        //echo $smallPackages[1]->Name;

        JsonSerializer::SerializeAndSend($smallPackages);
    }

    public static function RequestHotelBookingsByUserName($username)
    {
        $bookings=HotelBookingManager::GetHotelBookingByUsername($username);

        $smallBookings=array();

        $i=0;
        foreach($bookings as $booking)
        {
            $smallBookings[$i]=HotelBookingManager::GetCompressedHotelBookings($booking);
            $i++;
        }

        JsonSerializer::SerializeAndSend($smallBookings);
    }



    public static function RequestFeaturedPackages()
    {
        $packages=PackageManager::GetFeaturedPackages();

        $smallPackages=array();

        $i=0;
        foreach($packages as $package)
        {
            $smallPackages[$i]=PackageManager::GetCompressedPackage($package);
            $i++;
        }

        JsonSerializer::SerializeAndSend($smallPackages);
    } 
}

?>
