<?php

namespace App\Services;

use App\Models\Mitra;
use App\Models\User;

class UserServices {

   private User $user;
   private Mitra $mitra;

   public function __construct()
   {
       $this->user = new User();
       $this->mitra = new Mitra();
   }

   public function fetchAll()
   {  
       $data = $this->user->with('mitra')->orderByDesc('id')->get();
       $user = $this->user->paginate(10);
       $mitra = $this->mitra->all();

       return compact('data', 'user', 'mitra');
   }
}
