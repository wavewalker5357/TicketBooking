<? php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::scheme()->create('events, function($table')
{
    $table->increments('id');
    $table->string('title');
)};