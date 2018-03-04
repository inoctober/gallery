<?php namespace Increative\Gallery\Controllers;

use Request;
use BackendMenu;
use Backend\Classes\Controller;
use Increative\Gallery\Models\GalleryMedia as GalleryMediaModel;

/**
 * Galleries Back-end Controller
 */
class Galleries extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        $this->bodyClass = 'compact-container';

        $this->addCss('/plugins/increative/gallery/assets/css/increative.gallery.mediafinder.css');
        $this->addJs('/plugins/increative/gallery/assets/js/increative.gallery.mediafinder.js');

        BackendMenu::setContext('Increative.Gallery', 'gallery', 'galleries');
    }

    public function formAfterCreate($model)
    {
        $this->syncMedias($model);
    }

    public function formBeforeUpdate($model)
    {
        $model->medias()->delete();
    }

    public function formAfterUpdate($model)
    {
        $this->syncMedias($model);
    }

    public function formAfterDelete($model)
    {
        GalleryMediaModel::where('gallery_id', $model->id)->delete();
    }

    protected function buildMediaName($name)
    {
        $file = explode('/', $name);
        $file = end($file);

        return pathinfo($file, PATHINFO_FILENAME);
    }

    protected function syncMedias($model)
    {
        $titles = Request::input('medias_title');
        $paths = Request::input('paths');
        $folders = Request::input('folders');
        $descriptions = Request::input('medias_description');

        foreach ($paths as $key => $path) {

            if($path) {
                $title = $titles[$key] ?: $this->buildMediaName($path);

                $model->medias()->create([
                    'media_url' => $path,
                    'title' => $title,
                    'folder' => $folders[$key],
                    'path' => $path,
                    'description' => $descriptions[$key],
                ]);
            }
        }
    }
}
