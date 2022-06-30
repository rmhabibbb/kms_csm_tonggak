<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Pimpinan extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
  
        $this->data['email'] = $this->session->userdata('email');
        $this->data['id_role']  = $this->session->userdata('id_role'); 
          if (!$this->data['email'] || ($this->data['id_role'] != 3))
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
    $this->load->model('Referensi_m');   
    
    $this->data['profil'] = $this->login_m->get_row(['email' =>$this->data['email'] ]);   
     
    date_default_timezone_set("Asia/Jakarta");


  }

public function index()
{    

      $this->data['list_knowledge'] = $this->Knowledge_m->get(['status' => 1]);
      $this->data['index'] = 1;
      $this->data['content'] = 'pimpinan/dashboard';
      $this->template($this->data,'pimpinan');
}
 

public function verifikasi()
{     

  
  if ($this->POST('terima')) { 
     
    if ($this->Knowledge_m->update($this->POST('id_knowledge'),['status' => 2])) { 
      $this->flashmsg2('Pengajuan Knowledge berhasil diterima', 'success');
      redirect('pimpinan/' );
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('pimpinan/');
      exit();  
    }
  }elseif ($this->POST('tolak')) { 
     
    if ($this->Knowledge_m->update($this->POST('id_knowledge'),['status' => 3, 'keterangan' => $this->POST('keterangan')])) { 
      $this->flashmsg2('Pengajuan Knowledge berhasil ditolak', 'success');
      redirect('pimpinan/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('pimpinan/');
      exit();  
    }
  }  
  elseif ($this->uri->segment(3)) {
    $id = $this->uri->segment(3);


    $this->data['knowledge'] = $this->Knowledge_m->get_row(['id_knowledge' => $id ]);
    $this->data['list_ref'] = $this->Referensi_m->get(['id_knowledge' => $id ]);
    $this->data['list_diskusi'] = $this->Diskusi_m->get();
    $this->data['index'] = 1;
 
      $this->data['content'] = 'pimpinan/verif-knowledge';
    
    $this->template($this->data,'pimpinan');
  }
  else {
   redirect('pimpinan');
   exit();
  }
}


public function knowledge()
{     

  if ($this->uri->segment(3)) {
    $id = $this->uri->segment(3);


    $this->data['knowledge'] = $this->Knowledge_m->get_row(['id_knowledge' => $id ]);
    $this->data['list_ref'] = $this->Referensi_m->get(['id_knowledge' => $id ]);
    
    $this->data['index'] = 2;
 
      $this->data['content'] = 'pimpinan/fix-knowledge'; 
    $this->template($this->data,'pimpinan');
  }
  else {
    $this->data['list_kategori'] = $this->Kategori_m->get();
      if ($this->POST('cari')) {
        if ($this->POST('key') == '' && $this->POST('kategori') == '' && $this->POST('jenis') == '') {
          $this->data['list_knowledge'] = $this->Knowledge_m->get(['status' => 2]);  
        }elseif ($this->POST('key') == '' && $this->POST('kategori') != '' && $this->POST('jenis') == '') {
          $this->data['list_knowledge'] = $this->Knowledge_m->get(['status' => 2,'id_kategori' => $this->POST('kategori')]);
        }elseif ($this->POST('key') == '' && $this->POST('kategori') == '' && $this->POST('jenis') != '') {
          $this->data['list_knowledge'] = $this->Knowledge_m->get(['status' => 2 , 'jenis' => $this->POST('jenis')]);  
        }elseif ($this->POST('key') == '' && $this->POST('kategori') != '' && $this->POST('jenis') != '') {
          $this->data['list_knowledge'] = $this->Knowledge_m->get(['status' => 2,'id_kategori' => $this->POST('kategori'), 'jenis' => $this->POST('jenis')]);  
        }


        elseif ($this->POST('key') != '' && $this->POST('kategori') == '' && $this->POST('jenis') == '') {
          $this->data['list_knowledge'] = $this->Knowledge_m->getDataLike2($this->POST('jenis'),$this->POST('key'),  ['status' => 2]);  
        }


        elseif ($this->POST('key') != '' && $this->POST('kategori') != '' && $this->POST('jenis') == '') {
          $this->data['list_knowledge'] = $this->Knowledge_m->getDataLike2($this->POST('jenis'),$this->POST('key'),  ['status' => 2 , 'id_kategori' => $this->POST('kategori')]);  
        }

        elseif ($this->POST('key') != '' && $this->POST('kategori') == '' && $this->POST('jenis') != '') {
          $this->data['list_knowledge'] = $this->Knowledge_m->getDataLike2($this->POST('jenis'),$this->POST('key'),  ['status' => 2 , 'jenis' => $this->POST('jenis')]);  
        }

        elseif ($this->POST('key') != '' && $this->POST('kategori') != '' && $this->POST('jenis') != '') {
          $this->data['list_knowledge'] = $this->Knowledge_m->getDataLike2($this->POST('jenis'),$this->POST('key'),  ['status' => 2 , 'id_kategori' => $this->POST('kategori'), 'jenis' => $this->POST('jenis')]);  
        }
        

        $this->data['key'] = $this->POST('key');
        $this->data['kategori'] = $this->POST('kategori');
        $this->data['jenis'] = $this->POST('jenis');
      }
    $this->data['index'] = 2;
    $this->data['content'] = 'pimpinan/knowledge';
    $this->template($this->data,'pimpinan');
  }
}

public function diskusi()
{ 
  if ($this->uri->segment(3)) {
    $id = $this->uri->segment(3);
    $this->data['diskusi'] = $this->Diskusi_m->get_row(['id_diskusi' => $id ]);
    $this->data['list_komentar'] = $this->Komentar_m->get(['id_diskusi' => $id ]);
    $this->data['index'] = 3;
    $this->data['content'] = 'pimpinan/detail-diskusi';
    $this->template($this->data,'pimpinan');
  }
  else {
    $this->data['diskusi'] = $this->Diskusi_m->get_by_order('tgl_buat','desc',[]);
    $this->data['index'] = 3;
    $this->data['content'] = 'pimpinan/diskusi';
    $this->template($this->data,'pimpinan');
  }
}

 
public function download()
{  
  $this->load->helper('download');
  $explicit = $this->Diskusi_m->get_row(['id_diskusi' => $this->uri->segment(3)])->isi;
  $judul = $this->Diskusi_m->get_row(['id_diskusi' => $this->uri->segment(3)])->judul;
   
  $data = file_get_contents('./'.$explicit); 
  force_download($judul.'.pdf', $data); 
  redirect('pimpinan','refresh');
}


public function download2()
{  
  $this->load->helper('download');
  $explicit = $this->Knowledge_m->get_row(['id_knowledge' => $this->uri->segment(3)])->isi;
  $judul = $this->Knowledge_m->get_row(['id_knowledge' => $this->uri->segment(3)])->judul;
   
  $data = file_get_contents('./'.$explicit); 
  force_download($judul.'.pdf', $data); 
  redirect('pimpinan','refresh');
}
// PROFIL
  public function profile(){
    if ($this->POST('save')) {
      if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email') != $this->POST('emailx')) { 
        $this->flashmsg2('Email telah digunakan!', 'warning');
        redirect('pimpinan/profile/');
        exit();  
      }

        if ($this->login_m->update($this->POST('emailx'),['email' => $this->POST('email')])) {
          $user_session = [
            'email' => $this->POST('email')
          ];
          $this->session->set_userdata($user_session);

          $this->flashmsg2('Berhasil!', 'success');
          redirect('pimpinan/profile/');
          exit();  
        }else{
          $this->flashmsg2('Gagal, Coba lagi!', 'warning');
          redirect('pimpinan/profile/');
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
        redirect('pimpinan/profile/');
        exit();  
      }

      $data = [ 
        'password' => md5($this->POST('passwordnew')) 
      ];
      if ($this->login_m->update($this->data['profil']->email, $data)) {
        $this->flashmsg2('Password berhasil diganti!', 'success');
        redirect('pimpinan/profile/');
        exit();  
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('pimpinan/profile/');
        exit();  
      } 
    }

    $this->data['index'] = 5;
    $this->data['content'] = 'pimpinan/profile';
    $this->template($this->data,'pimpinan');
  }
  public function proses_edit_profil(){
    if ($this->POST('edit')) {
      
      


      
    } 
    elseif ($this->POST('edit2')) { 
      
      
      $this->login_m->update($this->data['email'],$data);
  
      $this->flashmsg('PASSWORD BARU TELAH TERSIMPAN!', 'success');
      redirect('pimpinan/profil');
      exit();    
    }   
    else{ 
      redirect('pimpinan/profil');
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
