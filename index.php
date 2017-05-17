<?php
require('./models/Package.php');
require('./models/Hotel.php');
require('./views/JsonSerializer.php');
require('./models/Destination.php');
require('./models/Flight.php');
require('./models/TimmingGrp.php');
require('./models/Train.php');
require('./models/Bus.php');

/*
$packages=PackageManager::GetPackagesByCity('Singapore');

$smallPackages=array();

$i=0;
foreach($packages as $package)
{
    $smallPackages[$i]=PackageManager::GetCompressedPackage($package);
    $i++;
}

JsonSerializer::SerializeAndSend($smallPackages);
*/

/*
$flights=FlightManager::GetFlightsByCity('Delhi','Mumbai');

$compressedFlights=array();

$i=0;
foreach($flights as $flight)
{
    $compressedFlights[$i]=FlightManager::GetCompressedFlight($flight);
    $i++;
}

JsonSerializer::SerializeAndSend($compressedFlights);
*/
/*
$trains=TrainManager::GetTrainsByCity('Delhi','Mumbai');

$compressedTrains=array();

$i=0;
foreach($trains as $train)
{
    $compressedTrains[$i]=TrainManager::GetCompressedTrain($train);
    $i++;
}

JsonSerializer::SerializeAndSend($compressedTrains);
*/
/*
$buses=BusManager::GetBusByCity('Delhi','Mumbai');

$compressedBuses=array();

$i=0;
foreach($buses as $bus)
{
    $compressedBuses[$i]=BusManager::GetCompressedBus($bus);
    $i++;
}

JsonSerializer::SerializeAndSend($compressedBuses);
*/
?>

<html>
<head>
<title>Lorem Ipsum</title>
</head>

<body>
    <p>
        <h1>
            Welcome To Lorem Ipsum
        </h1>
    </p>
</body>
</html>