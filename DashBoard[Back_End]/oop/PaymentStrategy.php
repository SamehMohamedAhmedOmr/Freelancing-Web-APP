<?php


interface PaymentStrategy {
   function pay($db);
   function withdraw();
}
