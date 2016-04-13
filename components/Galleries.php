<?php namespace Increative\Gallery\Components;

use Cms\Classes\ComponentBase;
use Increative\Gallery\Models\Gallery as GalleryModel;
use Increative\Gallery\Models\GalleryMedia as GalleryMediaModel;

class Galleries extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'Gallery',
            'description' => 'Display collection of gallery'
        ];
    }

    public function medias()
    {
        $gallery = $this->property('gallery');
        $start = $this->property('start');
        $limit = $this->property('limit');

        $medias = GalleryMediaModel::where('gallery_id', $gallery);

        if($limit) {
            $medias->take($limit)
                   ->skip($start);
        }

        return $medias->get();
    }

    public function defineProperties()
    {
        return [
            'gallery' => [
                'title'             => 'Gallery',
                'description'       => 'Name of gallery to show',
                'type'              => 'dropdown',
                'options'           => $this->getGalleryOptions(),
                'required'          => true
            ],
            'start' => [
                'title'             => 'Start',
                'description'       => 'Starting page',
                'default'           => 0,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Start property can contain only numeric symbols',
                'required'          => true
            ],
            'limit' => [
                'title'             => 'Limit',
                'description'       => 'Limit page',
                'default'           => 0,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Limit property can contain only numeric symbols'
            ],
        ];
    }

    protected function getGalleryOptions()
    {
        return GalleryModel::orderBy('created_at', 'desc')->get()->lists('title', 'id');
    }

}