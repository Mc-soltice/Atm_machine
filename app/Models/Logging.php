<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    // protected $fillable = [
    //     'description',
    //     'time', 
    //     'atm_id'
    // ];

    // // Méthode pour enregistrer un événement
    // public static function store($description, $atm_id = null)
    // {
    //     self::create([
    //         'description' => $description,
    //         'dateHeure' => now(),
    //         'atm_id' => $atm_id,
    //     ]);
    // }



    protected $fillable = ['description'];

    public static function store($description)
    {
        self::create(['description' => $description]);
    }
}

    // Relation : Une journalisation appartient à un ATM
    // public function atm()
    // {
    //     return $this->belongsTo(Atm::class);
    // }


