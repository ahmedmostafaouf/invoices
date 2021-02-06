<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable=[
    'category_name',
    'description',
    'created_by'
  ];
    public function products(){
        return $this->hasMany('App\Models\Product');
    }
    public function invoices(){
        return $this->hasMany('App\Models\Invoices');
    }
}
