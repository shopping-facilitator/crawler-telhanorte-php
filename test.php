<?php
require __DIR__.'/vendor/autoload.php';


$sku = '1358030';
//$sku = 'tapete';

$crawler = \ShoppingFacilitator\Crawler\TelhanorteProductCrawler::constructFromSku($sku);
//var_dump($crawler->getImageUrl());
var_dump($crawler->getPriceFrom());
//var_dump($crawler->getPriceBy());