<?php

interface Message {
   
    function sendMessage ($sender , $db,$kind=0);
    function readMessage($reciever,$sender,$db , $type,$kind=0);
    function viewSpecificMessage ($reciever, $sender,$date, $db,$kind=0);
    function changeMessageStatus ($message_id , $db,$kind=0);
    function countMessages ($my_id , $db,$kind=0);
}
