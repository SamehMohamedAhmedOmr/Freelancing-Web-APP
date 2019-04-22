<?php
session_start();
require '../config/includes.php';
$pageType="Home.php";
Person::logout($pageType);