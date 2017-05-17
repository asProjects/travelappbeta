<?php
//models
require('../models/DatabaseManager.php');
require('../models/Package.php');
require('../models/Hotel.php');
require('../models/Destination.php');
require('../models/Flight.php');
require('../models/TimmingGrp.php');
require('../models/Train.php');
require('../models/Bus.php');
require('../models/HotelBooking.php');
require('../models/Ticket.php');
require('../models/PackageBooking.php');
require('../models/User.php');
require('../models/ResultBit.php');



//views
require('../views/JsonSerializer.php');
require('../views/Error.php');
require('../views/ResultSender.php');

//controller
require('../controller/Controller.php');

error_reporting(0);

$actionGET=$_GET['action'];

$actionPOST=$_POST['action'];


if(isset($actionGET))
{
    if($_GET['action']=='get_flights_by_city')
    {
        $from=$_GET['from'];
        $to=$_GET['to'];
        if(isset($from) && isset($to) )
        {

            Controller::RequestFlightsByCity($from,$to);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='reserve_ticket')
    {
        $ticket=$_GET['ticket'];
        if(isset($ticket))
        {
            
            Controller::ReserveTicket($ticket);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='get_trains_by_city')
    {
        $from=$_GET['from'];
        $to=$_GET['to'];
        $cat=$_GET['cat'];
        if(isset($from) && isset($to) && isset($cat))
        {
            
            Controller::RequestTrainsByCity($from,$to,$cat);

        }
        else
        {
            Error::ShowError();
        }
    }
    
    else if($_GET['action']=='get_tickets_by_username')
    {
        $username=$_GET['username'];
        $type=$_GET['type'];
        if(isset($username) && isset($type))
        {
            
            Controller::RequestTicketsByUserName($username,$type);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='login')
    {
        $username=$_GET['username'];
        $password=$_GET['password'];
        if(isset($username) && isset($password))
        {
            
            Controller::Login($username,$password);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='get_package_bookings_by_username')
    {
        $username=$_GET['username'];
        if(isset($username))
        {
            
            Controller::RequestPackageBookingByUserName($username);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='get_buses_by_city')
    {
        $from=$_GET['from'];
        $to=$_GET['to'];
        $cat=$_GET['cat'];
        if(isset($from) && isset($to) && isset($cat))
        {
            
            Controller::RequestBusesByCity($from,$to,$cat);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='get_packages_by_city')
    {
        $destination=$_GET['destination'];
        if(isset($destination))
        {
            
            Controller::RequestPackagesByCity($destination);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='get_regions_by_city_like')
    {
        $city=$_GET['city'];
        $like=$_GET['like'];
        if(isset($city) && isset($like))
        {
            
            Controller::RequestRegionByCityLike($city,$like);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='test')
    {
        
        $jsonString=$_GET['json'];
        
        if(isset($jsonString))
        {
            
            
            Controller::Test($jsonString);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='get_hotel_bookings_by_username')
    {
        $username=$_GET['username'];
        if(isset($username))
        {
            
            Controller::RequestHotelBookingsByUserName($username);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='get_destinations_like')
    {
        $like=$_GET['like'];
        if(isset($like))
        {
            
            Controller::RequestDestinationsLike($like);

        }
        else
        {
            Error::ShowError();
        }
    }    
    else if($_GET['action']=='get_featured_destinations')
    {
        Controller::RequestFeaturedDestinations();
    }
    else if($_GET['action']=='get_featured_packages')
    {
        Controller::RequestFeaturedPackages();
    }
    else if($_GET['action']=='get_hotels_by_city')
    {
        $city=$_GET['city'];
        
        if(isset($city))
        {
            
            Controller::RequestHotelsByCity($city);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_GET['action']=='get_hotel_by_id')
    {
        $id=$_GET['id'];
        
        if(isset($id))
        {
            
            Controller::RequestHotelById($id);

        }
        else
        {
            Error::ShowError();
        }
    }
    
}
else if(isset($actionPOST))
{
    if($_POST['action']=='login')
    {
        $username=$_POST['username'];
        $password=$_POST['password'];
        if(isset($username) && isset($password))
        {
            
            Controller::Login($username,$password);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_POST['action']=='test')
    {
        $jsonString=$_POST['json'];
        
        if(isset($jsonString))
        {
            
            Controller::Test($jsonString);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_POST['action']=='signup')
    {
        $user=$_POST['user'];
        $password=$_POST['password'];
        if(isset($user) && isset($password))
        {
            
            Controller::Signup($user,$password);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_POST['action']=='book_hotel')
    {
        $hotel=$_POST['hotel'];
        if(isset($hotel))
        {
            
            Controller::BookHotel($hotel);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_POST['action']=='reserve_ticket')
    {
        $ticket=$_POST['ticket'];
        if(isset($ticket))
        {
            
            Controller::ReserveTicket($ticket);

        }
        else
        {
            Error::ShowError();
        }
    }
    else if($_POST['action']=='book_package')
    {
        $package=$_POST['package'];
        if(isset($package))
        {
            
            Controller::BookPackage($package);

        }
        else
        {
            Error::ShowError();
        }
    }
    else
    {
        Error::ShowLOL();
        Error::ShowError();
    }

}
else
{
    //Error::ShowLOL();
    Error::ShowError();
}


?>