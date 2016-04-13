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

    public function formAfterUpdate($model)
    {
        $model->medias()->delete();
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
        $medias = Request::input('medias');

        foreach ($medias as $media) {

            if($media) {
                $title = $this->buildMediaName($media);

                $model->medias()->create([
                    'media_url' => $media,
                    'title' => $title
                ]);
            }
        }
    }
}