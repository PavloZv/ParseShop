<?php

namespace App\Parsers;

use App\Parsers\AngryCurlCallable\AngryCurlCallable;
use App\Parsers\Contracts\ParserInterface;
use AngryCurl;
use App\Repositories\AbstractRepository;

/**
 * Class AbstractParser
 * @package App\Parsers
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * @var string
     */
    protected $configName;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var AbstractRepository
     */
    protected $sitesDataRepository;

    /**
     * @var integer
     */
    private $count;

    /**
     * @param $htmlPage
     * @return mixed
     */
    abstract protected function paginationMaxNumber($htmlPage);

    /**
     * @param $url
     * @param $number
     * @return mixed
     */
    abstract protected function makeUrlPage($url, $number);

    /**
     * @param $htmlPage
     * @return mixed
     */
    abstract protected function itemsLink($htmlPage);

    /**
     * @param $htmlPage
     * @return mixed
     */
    abstract protected function itemData($htmlPage);

    /**
     * AbstractParser constructor.
     * @param AbstractRepository $sitesDataRepository
     */
    public function __construct(AbstractRepository $sitesDataRepository)
    {
        $this->sitesDataRepository = $sitesDataRepository;
        $this->config = config("sites.{$this->configName}");
    }

    /**
     * @param $urls
     * @param null $count
     * @return AngryCurlCallable
     */
    public function getHtmlPage($urls, $count = null)
    {
        $callback = new AngryCurlCallable();
        $angryCurl = new AngryCurl([$callback, 'callback_function']);

        if (is_array($urls)) {
            foreach ($urls as $url) {
                $angryCurl->get($url);
            }
        } else {
            $angryCurl->get($urls);
        }

        $angryCurl->execute($count);

        return $callback;
    }

    /**
     * @param null $count
     * @throws \Exception
     */
    public function doParse($count = null)
    {
        $this->count = $count;

        if (!isset($this->config['categories'])) {
            throw new \Exception("Categories not found!");
        }

        foreach ($this->config['categories'] as $categoryUrl) {
            $pages = $this->getCountPages($categoryUrl);
            $urls = $this->getUrlPages($categoryUrl, $pages);
            $links = $this->getItemsLinks($urls);
            $itemsData = $this->getNewItemsData($links);

            $this->sitesDataRepository->createNewRecord($itemsData);
        }

        echo 'Data parse and save!', PHP_EOL;
        return;
    }

    /**
     * @param null $count
     * @return null
     * @throws \Exception
     */
    public function doParseUpdate($count = null)
    {
        $this->count = $count;

        if (!isset($this->config['categories'])) {
            throw new \Exception("Categories not found!");
        }

        $links = $this->sitesDataRepository->getLinks();
        $itemsData = $this->getItemsData($links);

        $this->sitesDataRepository->updateRecord($itemsData);

        echo 'Data parse and update!', PHP_EOL;
        return null;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function getCountPages($url)
    {
        $html = $this->getHtmlPage($url);
        return $this->paginationMaxNumber($html->getResponse()[$url]);
    }

    /**
     * @param $url
     * @param $pagesCount
     * @return array
     */
    public function getUrlPages($url, $pagesCount)
    {
        $urlPages = [];
        foreach (range(1, $pagesCount) as $value) {
            $urlPages[] = $this->makeUrlPage($url, intval($value));
        }

        return $urlPages;
    }

    /**
     * @param array $urlPages
     * @return array
     */
    public function getItemsLinks(array $urlPages)
    {
        if ($this->count != null) {
            $urlPages = array_slice($urlPages, 0, $this->count);
        }

        $html = $this->getHtmlPage($urlPages, $this->count);
        $links = [];
        foreach ($html->getResponse() as $document) {
            $links = array_merge($links, $this->itemsLink($document));
        }

        return $links;
    }

    /**
     * @param $links
     * @return array
     */
    public function getNewItemsData($links)
    {
        if ($saveLinks = $this->sitesDataRepository->getLinks()) {
            $links = array_filter($links, function ($item) use ($saveLinks) {
                return !in_array($item, $saveLinks);
            });
        }
        $links = array_merge($links, []);

        return $this->getItemsData($links);
    }

    /**
     * @param $links
     * @return array
     */
    public function getItemsData($links)
    {
        if ($this->count != null) {
            $links = array_slice($links, 0, $this->count);
        }

        $html = $this->getHtmlPage($links, $this->count);

        $itemsData = [];
        foreach ($html->getResponse() as $key => $document) {
            $itemsData[$key] = $this->itemData($document);
        }

        foreach ($itemsData as $key => $value) {
            if (count($value) == 1) {
                $itemsData[$key] = $value[0];
            }
        }

        foreach ($itemsData as &$value) {
            if (!empty($value)) {
                foreach ($value as $key => $item) {
                    if (!empty($item)) {
                        $value[$key] = $item[0];
                    }
                }
            }
        }

        return $itemsData;
    }
}
