<?php
/**
 * Created by PhpStorm.
 * User: petherson
 * Date: 08/08/18
 * Time: 16:58
 */

namespace ShoppingFacilitator\Crawler;


use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class TelhanorteProductCrawler
{
    const BASE_URL = "http://www.telhanorte.com.br/";
    private $crawler;
    private $html;


    public static function constructFromUrlFull(string $urlFull)
    {
        return new self(self::getBody($urlFull));
    }
    public static function constructFromSku(string $sku)
    {
        $bodyBusca = self::getBody('http://busca.telhanorte.com.br/busca?q='.$sku);
        $crawlerBusca = new Crawler($bodyBusca);
        $productLink = $crawlerBusca->filter('ul.neemu-products-container li.nm-product-item h2 a');
        if(count($productLink) == 0)
        {
//            var_dump("not found");
            return null; // not found
        }
        if(count($productLink) > 1)
        {
//            var_dump("Found more than one");
            return null; // Found more than one
        }
//        $filtered = $liItens->filter('a');
//        var_dump(count($filtered));
//        var_dump($filtered->nodeName());
//        var_dump($filtered->attr('href'));
//        return null;
        return new self(self::getBodyTest($productLink->attr('href')));
    }
    private static function getBody(string $urlFull)
    {
        echo $urlFull."\n";
        $client = new Client();
        $res = $client->request('GET', $urlFull);
        return $res->getBody()->getContents();
    }
    private static function getBodyTest(string $urlFull)
    {
        echo $urlFull."\n";
        $client = new Client();
        $res = $client->request('GET', $urlFull);
        $html = $res->getBody()->getContents();
        file_put_contents("retorno.html",$html);
        return $html;
    }
    public function __construct($html)
    {
        $this->html = $html;
        $this->crawler = new Crawler($html);
    }
    public function getImageUrl()
    {
        return $this->crawler->filter('meta[itemprop="url"]')->attr('content');
    }
    public function getPriceFrom()
    {
        //It is needed javascript processing to work
        return $this->crawler->filter('.valor-de .skuListPrice')->html();
    }
    public function getPriceBy()
    {
        return $this->crawler->filter('.valor-por .skuBestPrice')->html();
    }
}