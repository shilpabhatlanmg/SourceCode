<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ChatRecord extends Model
{
      public function incidentResponses(){
        return $this->hasMany('App\Model\AidRequestResponse')->where('status','Inactive');
    }
}
