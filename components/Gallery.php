<?php namespace Increative\Gallery\Components;

use Redirect;
use Cms\Classes\ComponentBase;
use Increative\Gallery\Models\Gallery as GalleryModel;
use Increative\Gallery\Models\GalleryMedia as GalleryMediaModel;

class Gallery extends ComponentBase
{
    public $pageParam;

    public $gallery;

    public $medias;

    public $title;

    public $childs;

    public function componentDetails()
    {
        return [
            'name'        => 'Gallery',
            'description' => 'Display gallery by slug'
        ];
    }

    public function onRun()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $limit = $this->property('perPage') ?: 9;
        $page = $this->property('pageNumber');

        $this->gallery = $this->gallery();
        $this->medias  = $this->gallery->medias()->paginate($limit, $page);
        $this->childs = $this->gallery->childs;

        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->medias->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }
    }

    protected function gallery()
    {
        $slug = $this->property('slug');
        $limit = $this->property('perPage') ?: 9;
        $page = $this->property('pageNumber') ?: 1;
        $withChild = $this->property('withChild') ?: 1;

        $model = GalleryModel::with(['medias' => function($query) use ($limit, $page){
            return $query->paginate($limit, $page);
        }]);

        if($withChild) {
            $model = $model->with('childs');
        }

        return $model->where('code', $slug)->first();
    }

    public function defineProperties()
    {
        return [
            'withChild' => [
                'title'             => 'With child',
                'description'       => 'Fetch childs or not',
                'type'              => 'checkbox',
                'default'           => true,
            ],
            'slug' => [
                'title'             => 'Slug',
                'description'       => 'Slug of gallery to show',
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
        return GalleryModel::orderBy('created_at', 'desc')->get()->lists('title', 'code');
    }

}
