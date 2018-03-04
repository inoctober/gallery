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
        $topLevelOnly = $this->property('topLevelOnly');

        $this->galleries = $this->galleries($limit, $page, $topLevelOnly);

        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->galleries->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }
    }

    protected function galleries($limit, $page, $topLevelOnly)
    {
        $model = GalleryModel::with(['medias' => function($query) use ($limit, $page){
            return $query->limit(1);
        }]);

        if($topLevelOnly) {
            $model = $model->where('parent_id', null);
        }

        return $model->paginate($limit, $page);
    }

    public function defineProperties()
    {
        return [
            'topLevelOnly' => [
                'title'             => 'Top level only',
                'description'       => 'Fetch top level galleries only or all galleries',
                'type'              => 'checkbox',
                'default'           => true,
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

}
