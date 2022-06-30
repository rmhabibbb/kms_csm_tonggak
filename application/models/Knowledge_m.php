<?php 
class Knowledge_m extends MY_Model
{

  function __construct()
  {
    parent::__construct();
    $this->data['primary_key'] = 'id_knowledge';
    $this->data['table_name'] = 'knowledge';
  }
}

 ?>
