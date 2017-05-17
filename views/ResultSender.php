<?php

class ResultSender
{
    public static function SendResult($result)
    {
        if($result==ResultBit::Success)
        {
            echo 1;
        }
        else if($result==ResultBit::Fail)
        {
            echo 0;
        }
    }

    
}

?>