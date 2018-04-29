<?php

namespace App\Parsers\Sites;

use App\Parsers\AbstractParser;
use App\Repositories\AbstractRepository;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class GlemComUa
 * @package App\Parsers\Sites
 */
class GlemComUa extends AbstractParser
{
    /**
     * @var string
     */
    protected $configName = 'glem_com_ua';

    /**
     * GlemComUa constructor.
     * @param AbstractRepository $sitesDataRepository
     */
    public function __construct(AbstractRepository $sitesDataRepository)
    {
        parent::__construct($sitesDataRepository);
    }

    /**
     * @param $htmlPage
     * @return mixed
     */
    protected function paginationMaxNumber($htmlPage)
    {
        $crawler = new Crawler($htmlPage);
        $result = $crawler->filter('div.split-count')
            ->each(function (Crawler $node) {
                return $node->text();
            });

        $results = explode(' ', reset($result));

        return (int) $results[count($results) - 1];
    }

    /**
     * @param $url
     * @param $number
     * @return string
     */
    protected function makeUrlPage($url, $number)
    {
        return $number > 1 ? "$url?page=$number" : $url;
    }

    /**
     * @param $htmlPage
     * @return array
     */
    protected function itemsLink($htmlPage)
    {
        $crawler = new Crawler($htmlPage);
        $result = $crawler->filter('div.list-products-name a')
            ->each(function (Crawler $node) {
                return $this->config['domain'] . $node->attr('href');
            });

        return $result;
    }

    /**
     * @param $htmlPage
     * @return array
     */
    protected function itemData($htmlPage)
    {
        $crawler = new Crawler($htmlPage);
        $result = $crawler->filter('div.content')
            ->each(function (Crawler $node) {
                return
                    [
                        'title' => $node->filter('div.product-h2 h2')
                            ->each(function (Crawler $node) {
                                return $node->text();
                            }),
                        'sizes' =>  [implode('; ', array_merge([], array_filter($node->filter('div.product-params-sizes div.product-sizes')
                            ->each(function (Crawler $node) {
                                if ($node->attr('style') === null) {
                                    return $node->children()->children()
                                        ->each(function (Crawler $node) {
                                            return $node->text();
                                        });
                                }
                                return null;
                            }), function ($value) {
                                    return is_array($value) ? true : false;
                            }
                        ))[0])],
                        'price' => $node->filter('div.product-sizes li.size-current input:nth-child(4)')->first()
                            ->each(function (Crawler $node) {
                                return $node->attr('value');
                            }),
                        'price2' => $node->filter('div.product-sizes li.size-current input:nth-child(5)')->first()
                            ->each(function (Crawler $node) {
                                return $node->attr('value');
                            }),
                        'img' => [implode('; ', $node->filter('div.product-image-color a.fancybox-gallery')
                            ->each(function (Crawler $node) {
                                return $node->attr('href');
                            }))],
                        'colors' => [implode('; ', $node->filter('div.product-colors img')
                            ->each(function (Crawler $node) {
                                return $node->attr('title');
                            }))],
                        'description' => $node->filter('div.product-text2 p:last-child')
                            ->each(function (Crawler $node) {
                                return $node->text();
                            }),
                    ];
            });

        return $result;
    }
}
