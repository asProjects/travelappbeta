<?php
class JsonPacket
{
    public $Objects;
}

class JsonSerializer
{
    public static function SerializeAndSend($objects)
    {
        $g=3;
        /*
            echo $objects[$g]->PackageID;
            echo "   hhi   ";
            echo $objects[$g]->Name;
            echo "   hihi   ";
            echo $objects[$g]->Destination;
            echo "   hihi   ";
            echo $objects[$g]->Description;
            echo "   ihi   ";
            echo $objects[$g]->Duration;
            echo "   hihi   ";
            echo $objects[$g]->BasePrice;
            echo "   ihi   ";
            echo $objects[$g]->Images[0];
            echo "   hihi   ";
            echo $objects[$g]->Hotel->Name;
            echo "   hihi   ";
            echo $objects[$g]->Hotel->Category;
            echo "   hihi   ";
            echo $objects[$g]->TransportType;
            echo "   hihi   ";
        */
        $jsonPacket=new JsonPacket();
        $jsonPacket->Objects=$objects;
        $json=json_encode($jsonPacket);
        echo $json;
        /*
        switch(json_last_error())
        {
            case JSON_ERROR_NONE : echo '  - No Error';
            break;
            case JSON_ERROR_DEPTH : echo ' - Maximum Stack Depth Exceeded';
            break;
            case JSON_ERROR_STATE_MISMATCH : echo '  -Underflow or the modes mismatch';
            break;
            case JSON_ERROR_CTRL_CHAR : echo '  -Unexpected control char found';
            break;
            case JSON_ERROR_SYNTAX : echo ' -Syntax Error, Malformed Json';
            break;
            case JSON_ERROR_UTF8: echo '  -malformed UTF8 char';
            break;
            default : echo 'Unknown';
        }
        */
    }

    public static function SerializeAndSendInd($objects)
    {
        $json=json_encode($objects);
        echo $json; 
    }

    public static function DeSerialize($jsonString)
    {
        $obj=json_decode($jsonString);
        return $obj;
    }
}
?>