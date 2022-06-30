<?php
$data =[ 
  'index' => $index
];
$this->load->view('karyawan/template/header',$data); 
$this->load->view('karyawan/template/navbar');
$this->load->view($content);
$this->load->view('karyawan/template/footer');
 ?>
