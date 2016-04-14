<?php namespace Increative\Gallery\Components;

use Redirect;
use Cms\Classes\ComponentBase;
use Increative\Gallery\Models\Gallery as GalleryModel;
use Increative\Gallery\Models\GalleryMedia as GalleryMediaModel;

class Galleries extends ComponentBase
{
    public $pageParam;

    public function componentDetails()
    {
        return [
            'name'        => 'Gallery',
            'description' => 'Display collection of gallery'
        ];
    }

    public function onRun()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');

        $medias = $this->medias();

        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $medias->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }
    }

    public function medias()
    {
        $gallery = $this->property('gallery');
        $limit = $this->property('perPage') ?: GalleryMediaModel::count();
        $page = $this->property('pageNumber') ?: 1;

        $medias = GalleryMediaModel::where('gallery_id', $gallery);

        return $medias->paginate($limit, $page);
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
            'pageNumber' => [
                'title'             => 'Page number',
                'description'       => 'Page number',
                'type'              => 'string',
                'default'           => '{{ :page }}',
            ],
            'perPage' => [
                'title'             => 'Per page content',
                'description'       => 'Per page content',
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