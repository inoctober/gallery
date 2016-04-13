<?php namespace Increative\Gallery\Models;

use Model;

/**
 * GalleryMedia Model
 */
class GalleryMedia extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'increative_gallery_gallery_media';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['media_url', 'title', 'description'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'gallery' => 'Increative\Gallery\Models\Gallery'
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}