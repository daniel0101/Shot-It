<?php

namespace App;

use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes,Searchable;

    protected $fillable = ['user_id', 'item_url', 'title','description','category_id','public_id','tags','item_type'];

 
     protected $indexConfigurator = FotoIndexConfigurator::class;

    // We don't analyze numbers, all text is in English
     protected $mapping = [
         'properties' => [
             'id' => [
                 'type' => 'keyword',
             ],
             'title' => [
                 'type' => 'text',
                 'analyzer' => 'english'
             ],
             'description' => [
                 'type' => 'text',
                 'analyzer' => 'english'
             ],
             'tags' => [
                 'type' => 'text',
                 'analyzer' => 'english'
             ],
             'item_type' => [
                'type' => 'text',
                'analyzer' => 'english'
            ],
             'public_id' => [
                 'type' => 'text',
                 'analyzer' => 'english'
             ]
         ]
     ];
}
