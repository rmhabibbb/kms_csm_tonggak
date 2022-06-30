<?php
$data =[ 
  'index' => $index
];
$this->load->view('kmteam/template/header',$data); 
$this->load->view('kmteam/template/navbar');
$this->load->view($content);
$this->load->view('kmteam/template/footer');
 ?>
