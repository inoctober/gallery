<?php namespace Increative\Gallery;

use DB;
use Backend;
use Lang;
use Event;
use Increative\Gallery\Models\GalleryMedia;
use System\Classes\PluginBase;
use Cms\Widgets\MediaManager;

/**
 * Gallery Plugin Information File
 */
class Plugin extends PluginBase
{
    public function boot()
    {
        Event::listen('media.file.move', function($widgets, $originalPath, $newPath) {
            $nameArray = explode('/', $originalPath);
            $name =  array_pop($nameArray);
            $folder = implode('/', $nameArray);

            GalleryMedia::where('path', $name)
                        ->where('folder', $folder)
                        ->update(['folder' => $newPath]);
        });

        Event::listen('media.file.rename', function($widgets, $originalPath, $newPath) {
            $nameArray = explode('/', $originalPath);
            $name =  array_pop($nameArray);
            $folder = implode('/', $nameArray);

            $newNameArray = explode('/', $newPath);
            $newName =  array_pop($newNameArray);

            GalleryMedia::where('path', $name)
                        ->where('folder', $folder)
                        ->update(['path' => $newName]);
        });

        Event::listen('media.file.delete', function($widgets, $path) {
            $nameArray = explode('/', $path);
            $name =  array_pop($nameArray);
            $folder = implode('/', $nameArray);

            GalleryMedia::where('path', $name)
                        ->where('folder', '#'.$folder)
                        ->delete();
        });

        Event::listen('media.folder.move', function($widgets, $path, $dest) {
            $nameArray = explode('/', $path);
            $name =  array_pop($nameArray);

            DB::update('update increative_gallery_gallery_media set folder = REPLACE(folder, :source, :dest) where folder like :toupdate', [
                'source' => '#'.$path,
                'dest' => $dest == '/' ?  '#/' .$name : '#'.$dest.'/'.$name,
                'toupdate' => '#'.$path.'%'
            ]);
        });

        Event::listen('media.folder.rename', function($widgets, $path, $dest) {
            $dest = str_replace('//', '/', $dest);

            DB::update('update increative_gallery_gallery_media set folder = REPLACE(folder, :source, :dest) where folder like :toupdate', [
                'source' => '#'.$path,
                'dest' => '#'.$dest,
                'toupdate' => '#'.$path.'%'
            ]);
        });

        Event::listen('media.folder.delete', function($widgets, $path) {
            GalleryMedia::where('folder', 'like', '#'.$path.'%')
                        ->delete();
        });
    }

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Simple Gallery Manager',
            'description' => 'Manage your set of images here, display is themes concern!',
            'author'      => 'Increative',
            'icon'        => 'icon-picture-o'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Increative\Gallery\Components\Gallery' => 'gallery',
            'Increative\Gallery\Components\Galleries' => 'galleries',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'increative.gallery.manage' => [
                'tab' => 'Gallery',
                'label' => Lang::get('increative.gallery::lang.plugin.permissions.manage'),
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'gallery' => [
                'label'       => 'Gallery',
                'url'         => Backend::url('increative/gallery/galleries'),
                'icon'        => 'icon-picture-o',
                'permissions' => ['increative.gallery.manage'],
                'order'       => 500,
            ],
        ];
    }

}
