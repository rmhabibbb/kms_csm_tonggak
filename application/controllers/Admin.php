<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Admin extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
  
        $this->data['email'] = $this->session->userdata('email');
        $this->data['id_role']  = $this->session->userdata('id_role'); 
          if (!$this->data['email'] || ($this->data['id_role'] != 1))
          {
            $this->flashmsg('<i class="glyphicon glyphicon-remove"></i> Anda harus login terlebih dahulu', 'danger');
            redirect('login');
            exit;
          }  
    
    $this->load->model('login_m');  
    $this->load->model('Karyawan_m');   
    $this->load->model('Kategori_m');   
    $this->load->model('Diskusi_m');         
    $this->load->model('Komentar_m');     
    $this->load->model('Knowledge_m');     
    $this->load->model('KMTeam_m');        
    
    $this->data['profil'] = $this->login_m->get_row(['email' =>$this->data['email'] ]);   
     
    date_default_timezone_set("Asia/Jakarta");


  }

public function index()
{    
      $this->data['index'] = 1;
      $this->data['content'] = 'admin/dashboard';
      $this->template($this->data,'admin');
}
 
public function akun()
{     

  if ($this->POST('add')) { 
        
    if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0) {
      $this->flashmsg2('Email telah digunakan!', 'warning');
      redirect('admin/akun/');
      exit();  
    }

     
    $data = [
      'email' => $this->POST('email'), 
      'role' => $this->POST('role'),
      'password' => md5($this->POST('password')) 
    ];

    if ($this->login_m->insert($data)) {
      $this->flashmsg2('Akun berhasil ditambah', 'success');
      redirect('admin/akun/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/akun/');
      exit();  
    }
  }
  elseif ($this->POST('edit')) { 
        
    if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email_x') != $this->POST('email')) {
      $this->flashmsg2('Email telah digunakan!', 'warning');
      redirect('admin/akun/');
      exit();  
    }

   
    $data = [
      'email' => $this->POST('email'), 
      'role' => $this->POST('role')
    ];
    
    

    if ($this->login_m->update($this->POST('email_x'),$data)) {
      $this->flashmsg2('Akun berhasil diedit.', 'success');
      redirect('admin/akun/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/akun/');
      exit();  
    }
  }
  elseif ($this->POST('edit2')) { 
        
    if ($this->POST('password') != $this->POST('password2')) {
      $this->flashmsg2('Konfirmasi password tidak sama!', 'warning');
      redirect('admin/akun/');
      exit();  
    }

   
    $data = [
      'password' => md5($this->POST('password') )
    ];
    
    

    if ($this->login_m->update($this->POST('email'),$data)) {
      $this->flashmsg2('Password '.$this->POST('email'). ' berhasil diganti.', 'success');
      redirect('admin/akun/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/akun/');
      exit();  
    }
  }
  elseif ($this->POST('delete')) {
    if ($this->login_m->delete($this->POST('email'))) {
      $this->flashmsg2('Akun berhasil dihapus.', 'success');
      redirect('admin/akun/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/akun/');
      exit();  
    }
  }
  else {
    $this->data['users'] = $this->login_m->get(['email !=' => $this->data['email']  ]);
    $this->data['index'] = 2;
    $this->data['content'] = 'admin/users';
    $this->template($this->data,'admin');
  }
}
 
public function karyawan()
{     

  if ($this->POST('add')) { 
    $cek = 0;
    $msg = ''; 
    if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0) {
      $msg = $msg . 'Email telah digunakan!<br>';
      $cek++;
    }
    if ($this->Karyawan_m->get_num_row(['id_karyawan' => $this->POST('id')]) != 0) { 
      $msg = $msg . 'ID Karyawan telah digunakan!<br>'; 
      $cek++;
    }

    if ($cek != 0) {

      $this->flashmsg2($msg, 'warning');
      redirect('admin/karyawan/');
      exit();  
    }
     
    $data = [
      'email' => $this->POST('email'), 
      'role' => 4,
      'password' => md5($this->POST('password')) 
    ];

    if ($this->login_m->insert($data)) {

      $d = [
        'id_karyawan' =>  $this->POST('id'),
        'nama' =>  $this->POST('nama'),
        'email' => $this->POST('email'),  
        'alamat' => $this->POST('alamat'),  
        'jabatan' => $this->POST('jabatan'),  
        'jk' => $this->POST('jk') 
      ];

      if ($this->Karyawan_m->insert($d)) {
         $this->flashmsg2('Data Karyawan berhasil ditambah', 'success');
          redirect('admin/karyawan/');
          exit(); 
      }else{
        $this->login_m->delete($this->POST('email'));
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/karyawan/');
        exit();  
      }

      
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/karyawan/');
      exit();  
    }
  }
  elseif ($this->POST('edit')) { 
         
    $cek = 0;
    $msg = ''; 
    if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email_x') != $this->POST('email')) {
      $msg = $msg . 'Email telah digunakan!<br>';
      $cek++;
    }
    if ($this->Karyawan_m->get_num_row(['id_karyawan' => $this->POST('id')]) != 0 && $this->POST('id_x') != $this->POST('id')) { 
      $msg = $msg . 'ID Karyawan telah digunakan!<br>'; 
      $cek++;
    }

    if ($cek != 0) {

      $this->flashmsg2($msg, 'warning');
      redirect('admin/karyawan/');
      exit();  
    }
     
   
    $data = [
      'email' => $this->POST('email') 
    ];
    
    

    if ($this->login_m->update($this->POST('email_x'),$data)) {

      $d = [
        'id_karyawan' =>  $this->POST('id'),
        'nama' =>  $this->POST('nama'),
        'email' => $this->POST('email'),  
        'alamat' => $this->POST('alamat'),  
        'jabatan' => $this->POST('jabatan'),  
        'jk' => $this->POST('jk') 
      ];

      if ($this->Karyawan_m->update($this->POST('id_x'), $d)) {
        $this->flashmsg2('Data Karyawan berhasil diedit.', 'success');
        redirect('admin/karyawan/');
        exit(); 
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/karyawan/');
        exit();  
    }

       
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/karyawan/');
      exit();  
    } 
  } 
  elseif ($this->POST('delete')) {
    if ($this->login_m->delete($this->POST('email'))) {
      $this->flashmsg2('Data Karyawan berhasil dihapus.', 'success');
      redirect('admin/karyawan/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/karyawan/');
      exit();  
    }
  }
  else {
    $this->data['karyawan'] = $this->Karyawan_m->get();
    $this->data['index'] = 3;
    $this->data['content'] = 'admin/karyawan';
    $this->template($this->data,'admin');
  }
}

public function kmteam()
{     

  if ($this->POST('add')) { 
    $cek = 0;
    $msg = ''; 
    if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0) {
      $msg = $msg . 'Email telah digunakan!<br>';
      $cek++;
    }
    if ($this->KMTeam_m->get_num_row(['id_kmteam' => $this->POST('id')]) != 0) { 
      $msg = $msg . 'ID KM Team telah digunakan!<br>'; 
      $cek++;
    }

    if ($cek != 0) {

      $this->flashmsg2($msg, 'warning');
      redirect('admin/kmteam/');
      exit();  
    }
     
    $data = [
      'email' => $this->POST('email'), 
      'role' => 2,
      'password' => md5($this->POST('password')) 
    ];

    if ($this->login_m->insert($data)) {

      $d = [
        'id_kmteam' =>  $this->POST('id'),
        'nama' =>  $this->POST('nama'),
        'email' => $this->POST('email'),   
        'jk' => $this->POST('jk') 
      ];

      if ($this->KMTeam_m->insert($d)) {
         $this->flashmsg2('Data KM Team berhasil ditambah', 'success');
          redirect('admin/kmteam/');
          exit(); 
      }else{
        $this->login_m->delete($this->POST('email'));
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/kmteam/');
        exit();  
      }

      
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/kmteam/');
      exit();  
    }
  }
  elseif ($this->POST('edit')) { 
         
    $cek = 0;
    $msg = ''; 
    if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email_x') != $this->POST('email')) {
      $msg = $msg . 'Email telah digunakan!<br>';
      $cek++;
    }
    if ($this->KMTeam_m->get_num_row(['id_kmteam' => $this->POST('id')]) != 0 && $this->POST('id_x') != $this->POST('id')) { 
      $msg = $msg . 'ID KM Team telah digunakan!<br>'; 
      $cek++;
    }

    if ($cek != 0) {

      $this->flashmsg2($msg, 'warning');
      redirect('admin/kmteam/');
      exit();  
    }
     
   
    $data = [
      'email' => $this->POST('email') 
    ];
    
    

    if ($this->login_m->update($this->POST('email_x'),$data)) {

      $d = [
        'id_kmteam' =>  $this->POST('id'),
        'nama' =>  $this->POST('nama'),
        'email' => $this->POST('email'),   
        'jk' => $this->POST('jk') 
      ];

      if ($this->KMTeam_m->update($this->POST('id_x'), $d)) {
        $this->flashmsg2('Data KM Team berhasil diedit.', 'success');
        redirect('admin/kmteam/');
        exit(); 
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/kmteam/');
        exit();  
    }

       
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/kmteam/');
      exit();  
    } 
  } 
  elseif ($this->POST('delete')) {
    if ($this->login_m->delete($this->POST('email'))) {
      $this->flashmsg2('Data KM Team berhasil dihapus.', 'success');
      redirect('admin/kmteam/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/kmteam/');
      exit();  
    }
  }
  else {
    $this->data['kmteam'] = $this->KMTeam_m->get();
    $this->data['index'] = 4;
    $this->data['content'] = 'admin/kmteam';
    $this->template($this->data,'admin');
  }
}
 
// PROFIL
  public function profile(){
    if ($this->POST('save')) {
      if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email') != $this->POST('emailx')) { 
        $this->flashmsg2('Email telah digunakan!', 'warning');
        redirect('admin/profile/');
        exit();  
      }

        if ($this->login_m->update($this->POST('emailx'),['email' => $this->POST('email')])) {
          $user_session = [
            'email' => $this->POST('email')
          ];
          $this->session->set_userdata($user_session);

          $this->flashmsg2('Berhasil!', 'success');
          redirect('admin/profile/');
          exit();  
        }else{
          $this->flashmsg2('Gagal, Coba lagi!', 'warning');
          redirect('admin/profile/');
          exit();  
        } 
       

    } 

    if ($this->POST('gpw')) { 

      $cek = 0;
      $msg = ''; 
      if (md5($this->POST('passwordold')) != $this->data['profil']->password) {
        $msg = $msg . 'Password lama salah! <br>';
        $cek++;
      }

      if ($this->POST('passwordnew') != $this->POST('passwordnew2')) {
        $msg = $msg . 'Password baru tidak sama!';
        $cek++;
      }

      if ($cek != 0) {

        $this->flashmsg2($msg, 'warning');
        redirect('admin/profile/');
        exit();  
      }

      $data = [ 
        'password' => md5($this->POST('passwordnew')) 
      ];
      if ($this->login_m->update($this->data['profil']->email, $data)) {
        $this->flashmsg2('Password berhasil diganti!', 'success');
        redirect('admin/profile/');
        exit();  
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/profile/');
        exit();  
      } 
    }

    $this->data['index'] = 5;
    $this->data['content'] = 'admin/profile';
    $this->template($this->data,'admin');
  }
  public function proses_edit_profil(){
    if ($this->POST('edit')) {
      
      


      
    } 
    elseif ($this->POST('edit2')) { 
      
      
      $this->login_m->update($this->data['email'],$data);
  
      $this->flashmsg('PASSWORD BARU TELAH TERSIMPAN!', 'success');
      redirect('admin/profil');
      exit();    
    }   
    else{ 
      redirect('admin/profil');
      exit();
    } 
  }  
 
  public function cekemail(){ echo $this->login_m->cekemail2($this->input->post('email')); } 
  public function cekpasslama(){ echo $this->login_m->cekpasslama2($this->data['email'],$this->input->post('password')); } 
  public function cekpass(){ echo $this->login_m->cek_password_length2($this->input->post('password')); }
  public function cekpass2(){ echo $this->login_m->cek_passwords2($this->input->post('password'),$this->input->post('password2')); }
// PROFIL
 
}

 ?>
