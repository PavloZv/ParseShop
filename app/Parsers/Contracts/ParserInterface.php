<?php

namespace App\Parsers\Contracts;

/**
 * Interface ParserInterface
 * @package App\Parsers\Contracts
 */
interface ParserInterface
{
    public function getHtmlPage($urls, $count = null);
    public function doParse($count);
    public function doParseUpdate($count);
    public function getCountPages($url);
    public function getUrlPages($url, $pagesCount);
    public function getItemsLinks(array $urlPages);
    public function getNewItemsData($links);
    public function getItemsData($links);
}