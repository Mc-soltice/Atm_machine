<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atm extends Model
{
    protected $fillable = ['localisation', 'status'];

    // Relation : Un ATM peut avoir plusieurs journaux d'événements
    public function logging ()
    {
        return $this->hasMany(logging::class);
    }
}
