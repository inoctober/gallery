<?php namespace Increative\Gallery\Models;

use Model;

/**
 * Gallery Model
 */
class Gallery extends Model
{
    use \October\Rain\Database\Traits\Validation, \October\Rain\Database\Traits\Sluggable;

    /*
     * Validation
     */
    public $rules = [
        'title' => 'required|max:255'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'increative_gallery_galleries';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['code' => 'title'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'medias' => 'Increative\Gallery\Models\GalleryMedia',
        'medias_count' => ['Increative\Gallery\Models\GalleryMedia', 'count' => true],
        'childs' => ['Increative\Gallery\Models\Gallery', 'key' => 'parent_id']
    ];
    public $belongsTo = [
        'parent' => ['Increative\Gallery\Models\Gallery']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * Get the gallery first media path.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->medias()->orderBy('created_at', 'desc')->first()->url;
    }

}
