<?php
class Destination
{
    private $cityCode;
    private $name;
    private $region;
    private $description;
    private $images;

    public function GetCityCode()
    {
        return $this->cityCode;
    }
    public function GetName()
    {
        return $this->name;
    }
    public function GetRegion()
    {
        return $this->region;
    }
    public function GetDescription()
    {
        return $this->description;
    }
    public function GetImages()
    {
        return $this->images;
    }
    
    public function __construct($cityCode,$name,$region,$description,$images)
    {
        $this->cityCode=$cityCode;
        $this->name=$name;
        $this->region=$region;
        $this->description=$description;
        $this->images=$images;
    }
}


class DestinationCompressed
{
    public $CityCode;
    public $Name;
    public $Region;
    public $Description;
    public $Images;
}

class DestinationManager
{
    public static function GetCodeFromCity($city)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;

        }
        else
        {
            $query='SELECT CityCode FROM Destination WHERE LCASE(Name)=LCASE(?)';
            $statement=$db->prepare($query);
            $statement->bindValue(1,$city);
            $statement->execute();
            $destination=$statement->fetch();
            $cityCode=$destination['CityCode'];

            return $cityCode;
        }

    }

    public static function GetCityFromCode($cityCode)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;

        }
        else
        {
            $query='SELECT Name FROM Destination WHERE LCASE(CityCode)=LCASE(?)';
            $statement=$db->prepare($query);
            $statement->bindValue(1,$cityCode);
            $statement->execute();
            $destination=$statement->fetch();
            $city=$destination['Name'];

            return $city;
        }

    }

    public static function GetCompressedDestination($destination)
    {
        $compressedDestination=new DestinationCompressed();
        $compressedDestination->CityCode=$destination->GetCityCode();
        $compressedDestination->Name=$destination->GetName();
        $compressedDestination->Region=$destination->GetRegion();
        $compressedDestination->Description=$destination->GetDescription();
        $compressedDestination->Images=$destination->GetImages();

        return $compressedDestination;
    }

    public static function GetImagesByCity($cityCode)
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;
        }
        else
        {
            $query='SELECT Path FROM DestinationPics WHERE CityCode=?';
            $statement=$db->prepare($query);
            $statement->BindValue(1,$cityCode);
            $statement->execute();
            $cities=$statement->fetchAll();
            $images=array();

            $i=0;
            foreach($cities as $city)
            {
                $images[$i]=$city['Path'];
                $i++;
            }
            return $images;

/*
                $images[0]='img1';
                $images[1]='img2';
            $images[2]='img3';
            $images[3]='img4';
            */
        }

        
    }

    public static function GetFeaturedDestinations()
    {
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;

        }
        else
        {
            $cities=array();
            $query='SELECT * FROM FeaturedDestination';
            $statement=$db->prepare($query);
            $statement->execute();
            $citiesDB=$statement->fetchAll();
            $count=count($citiesDB);

            
            for($i=0;$i<$count;$i+=3)
            {
                $images=array($citiesDB[$i]['Image'],$citiesDB[$i+1]['Image'],$citiesDB[$i+2]['Image']);
                $cities[$i/3]=new Destination($citiesDB[$i]['CityCode'],$citiesDB[$i]['Name'],$citiesDB[$i]['Region'],$citiesDB[$i]['Description'],$images);

            }

            return $cities;

        }
        /*
        $cities=array();
        $cities[0]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));
        $cities[1]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));
        $cities[2]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));
        $cities[3]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));
        $cities[4]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));
        $cities[5]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));

        return $cities;
        */
    }

    public static function GetDestinationsLike($like)
    {
        
        $db=DatabaseManager::GetDatabase();
        if($db=='-1')
        {
            return -1;

        }
        else
        {
            $cities=array();
            $like=$like.'%';
            $query='SELECT * FROM DestinationInfo WHERE LCASE(Name) LIKE ?';
            $statement=$db->prepare($query);
            $statement->bindValue(1,$like);
            $statement->execute();
            $citiesDB=$statement->fetchAll();
            $count=count($citiesDB);

            
            for($i=0;$i<$count;$i+=3)
            {
                $images=array($citiesDB[$i]['Image'],$citiesDB[$i+1]['Image'],$citiesDB[$i+2]['Image']);
                $cities[$i/3]=new Destination($citiesDB[$i]['CityCode'],$citiesDB[$i]['Name'],$citiesDB[$i]['Region'],$citiesDB[$i]['Description'],$images);

            }

            return $cities;

        }
        
        /*
        $cities=array();
        $cities[0]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));
        $cities[1]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));
        $cities[2]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));
        $cities[3]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));
        $cities[4]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));
        $cities[5]=new Destination('NDLS','New Delhi','Narela','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus, mi nec fermentum congue, turpis nibh porttitor ex, sed commodo neque eros non libero. Donec mauris felis, malesuada',array('img1','img2','img3'));

        return $cities;
        */
    }

    public static function GetRegionsByCityLike($cityCode,$like)
    {
        $regions=array();
        $regions[0]='Malviya Nagar';
        $regions[1]='Lajpat Nagar';
        $regions[2]='Sarojni Nagar';
        $regions[3]='Saket';

        return $regions;
    }
}
?>