<?php namespace Increative\Gallery\Components;

use Redirect;
use Cms\Classes\ComponentBase;
use Increative\Gallery\Models\Gallery as GalleryModel;

class Galleries extends ComponentBase
{
    public $pageParam;

    public $galleries;

    public $title;

    public function componentDetails()
    {
        return [
            'name'        => 'Galleries',
            'description' => 'Display galleries'
        ];
    }

    public function onRun()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $limit = $this->property('perPage') ?: 9;
        $page = $this->property('pageNumber') ?: 1;

        $this->galleries = $this->gallerys($limit, $page);

        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->galleries->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }
    }

    protected function gallerys($limit, $page)
    {
        return GalleryModel::with(['medias' => function($query) use ($limit, $page){
            return $query->limit(1);
        }])->paginate($limit, $page);
    }

    public function defineProperties()
    {
        return [
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

}
