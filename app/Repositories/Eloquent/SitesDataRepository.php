<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AbstractRepository;
use App\SiteData;

/**
 * Class SitesDataRepository
 * @package App\Repositories\Eloquent
 */
class SitesDataRepository extends AbstractRepository
{
    /**
     * @var string Model class name
     */
    protected $entityClassName = SiteData::class;

    /**
     * @param array $data
     */
    public function createNewRecord(array $data)
    {
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $this->create([
                    'link' => trim($key),
                    'title' => !empty($value['title']) ? trim($value['title']) : '',
                    'code' => !empty($value['code']) ? trim(preg_replace("/\r\n|\r|\n/", '', $value['code'])) : '',
                    'status' => !empty($value['status']) ? trim($value['status']) : '',
                    'sizes' => !empty($value['sizes']) ? trim($value['sizes']) : '',
                    'price' => !empty($value['price'])? trim($value['price']) : '',
                    'price2' => !empty($value['price2'])? trim($value['price2']) : '',
                    'img' => !empty($value['img'])? trim($value['img']) : '',
                    'colors' => !empty($value['colors'])? trim($value['colors']) : '',
                    'description' => !empty($value['description'])? trim($value['description']) : '',
                ]);
            }

        }
    }

    /**
     * @param array $data
     */
    public function updateRecord(array $data)
    {
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                if (!$model = $this->entity->where('link', $key)->first()) {
                    continue;
                };

                $model->title = !empty($value['title']) ? trim($value['title']) : '';
                $model->code = !empty($value['code']) ? trim(preg_replace("/\r\n|\r|\n/", '', $value['code'])) : '';
                $model->status = !empty($value['status']) ? trim($value['status']) : '';
                $model->sizes = !empty($value['sizes']) ? trim($value['sizes']) : '';
                $model->price = !empty($value['price'])? trim($value['price']) : '';
                $model-> price2 = !empty($value['price2'])? trim($value['price2']) : '';
                $model->img = !empty($value['img'])? trim($value['img']) : '';
                $model->colors = !empty($value['colors'])? trim($value['colors']) : '';
                $model->description = !empty($value['description'])? trim($value['description']) : '';

                $model->save();
            }

        }
    }


    /**
     * @return array
     */
    public function getLinks()
    {
        $items = [];
        foreach ($this->entity->select('link')->get()->toArray() as $value ) {
            $items[] = $value['link'];
        }

        return $items;
    }
}
