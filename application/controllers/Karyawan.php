<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Karyawan extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
  
        $this->data['email'] = $this->session->userdata('email');
        $this->data['id_role']  = $this->session->userdata('id_role'); 
          if (!$this->data['email'] || ($this->data['id_role'] != 4))
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
    $this->data['kar'] = $this->Karyawan_m->get_row(['email' =>$this->data['email'] ]);   
     
    date_default_timezone_set("Asia/Jakarta");


  }

public function index()
{    

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


      $this->data['index'] = 1;
      $this->data['content'] = 'karyawan/dashboard';
      $this->template($this->data,'karyawan');
}
 
public function diskusi()
{     

  if ($this->POST('tambah')) { 
    

    if ($this->POST('jeniskm') == 'Tacit') {
      $km = $this->POST('knowledge');
    }else{
      if ($_FILES['foto']['name'] !== '') {  
          if ($_FILES['foto']['type'] != 'application/pdf') {
            $this->flashmsg2('Format file harus .pdf ', 'warning');
                redirect('karyawan/form-diskusi/');
                exit();  
          }
              $files = $_FILES['foto'];
              $_FILES['foto']['name'] = $files['name'];
              $_FILES['foto']['type'] = $files['type'];
              $_FILES['foto']['tmp_name'] = $files['tmp_name'];
              $_FILES['foto']['size'] = $files['size'];
              

               $upload_path = realpath(APPPATH . '../explicit/');
          
 		 
          $name = preg_replace('/\s+/', '_', $_FILES['foto']['name']);
          $km = 'explicit/'.$name;
          $config = [ 
            'file_name'     => $name,
            'allowed_types'   => 'pdf',
            'upload_path'   => $upload_path
          ];
          $this->load->library('upload');
          $this->upload->initialize($config); 
         	$this->upload->do_upload('foto');


            }else{   
               
                $this->flashmsg2('File harus diisi!', 'warning');
                redirect('karyawan/form-diskusi/');
                exit();  
            }

    }

     
    $data = [ 
      'judul' => $this->POST('judul'),
      'isi' => $km,
      'id_kategori' => $this->POST('kategori'),
      'id_karyawan' => $this->data['kar']->id_karyawan,
      'tgl_buat' => date('Y-m-d H:i:s'),
      'status' => 1,
      'jenis' => $this->POST('jeniskm'),
      'filename' => $name
    ];

    if ($this->Diskusi_m->insert($data)) {
      $id = $this->db->insert_id();
      $this->flashmsg2('Diskusi berhasil dibuat', 'success');
      redirect('karyawan/diskusi/'.$id );
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('karyawan/diskusi/');
      exit();  
    }
  }
  elseif ($this->POST('tutup')) { 
    
 
     
    $data = [ 
      'status' => 2 
    ];

    if ($this->Diskusi_m->update($this->POST('id'),$data)) {
      $id = $this->db->insert_id();
      $this->flashmsg2('Diskusi berhasil dibuat', 'success');
      redirect('karyawan/diskusi/'.$this->POST('id') );
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('karyawan/diskusi/');
      exit();  
    }
  }
  elseif ($this->POST('delete')) {
    if ($this->Diskusi_m->delete($this->POST('id_diskusi'))) {
      $this->flashmsg2('Diskusi berhasil dihapus.', 'success');
      redirect('karyawan/diskusi/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('karyawan/diskusi/');
      exit();  
    }
  }elseif ($this->uri->segment(3)) {
    $id = $this->uri->segment(3);
    $this->data['diskusi'] = $this->Diskusi_m->get_row(['id_diskusi' => $id ]);
    $this->data['list_komentar'] = $this->Komentar_m->get(['id_diskusi' => $id ]);
    $this->data['index'] = 2;
    $this->data['content'] = 'karyawan/detail-diskusi';
    $this->template($this->data,'karyawan');
  }
  else {
    $this->data['diskusi'] = $this->Diskusi_m->get_by_order('tgl_buat','desc',[]);
    $this->data['index'] = 2;
    $this->data['content'] = 'karyawan/diskusi';
    $this->template($this->data,'karyawan');
  }
}

public function hapuskomentar(){
 

  if ($this->Komentar_m->delete($this->uri->segment(4))) {
    $this->flashmsg2('Komentar berhasil dihapus', 'success');
      redirect('karyawan/diskusi/'.$this->uri->segment(3));
      exit(); 
  }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('karyawan/diskusi/'.$this->uri->segment(3));
      exit();  
    }
}


public function id()
{     

  if ($this->uri->segment(3)) {
    $id = $this->uri->segment(3);


    $this->data['knowledge'] = $this->Knowledge_m->get_row(['id_knowledge' => $id ]); 
    $this->data['index'] = 1;
 
    $this->data['content'] = 'karyawan/fix-knowledge';
   
    $this->template($this->data,'karyawan');
  }
  else {
    redirect('karyawan');
    exit();
  }
}



public function form_diskusi()
{    
      $this->data['list_kategori'] = $this->Kategori_m->get();
      $this->data['index'] = 2;
      $this->data['content'] = 'karyawan/form-diskusi';
      $this->template($this->data,'karyawan');
}

public function berikomentar(){
  $data = [
    'id_diskusi' => $this->POST('id'),
    'id_karyawan' => $this->data['kar']->id_karyawan,
    'komentar' => $this->POST('komentar'),
    'dt' => date('Y-m-d H:i:s')
  ];

  if ($this->Komentar_m->insert($data)) {
    $this->flashmsg2('Komentar berhasil dikirim', 'success');
      redirect('karyawan/diskusi/'.$this->POST('id'));
      exit(); 
  }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('karyawan/diskusi/'.$this->POST('id'));
      exit();  
    }
}


 public function downloadexplicit()
  {  
    $this->load->helper('download');
    $explicit = $this->Diskusi_m->get_row(['id_diskusi' => $this->uri->segment(3)])->isi;
    $judul = $this->Diskusi_m->get_row(['id_diskusi' => $this->uri->segment(3)])->judul;
     
    $data = file_get_contents(base_url('assets/'.$explicit)); 
    force_download($judul.'.pdf', $data); 
    redirect('karyawan','refresh');
  }
   
 
// PROFIL
  public function profile(){
    if ($this->POST('save')) {
      if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email') != $this->POST('emailx')) { 
        $this->flashmsg2('Email telah digunakan!', 'warning');
        redirect('karyawan/profile/');
        exit();  
      }

        if ($this->login_m->update($this->POST('emailx'),['email' => $this->POST('email')])) {

          $d = [ 
            'nama' =>  $this->POST('nama'),
            'email' => $this->POST('email'),  
            'alamat' => $this->POST('alamat'),   
            'jk' => $this->POST('jk') 
          ];

          if ($this->Karyawan_m->update($this->POST('id'),$d)) {
            $user_session = [
              'email' => $this->POST('email')
            ];
            $this->session->set_userdata($user_session);

            $this->flashmsg2('Berhasil!', 'success');
            redirect('karyawan/profile/');
            exit(); 
          }else{
            $this->flashmsg2('Gagal, Coba lagi!', 'warning');
            redirect('karyawan/profile/');
            exit();  
          } 
           
        }else{
          $this->flashmsg2('Gagal, Coba lagi!', 'warning');
          redirect('karyawan/profile/');
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
        redirect('karyawan/profile/');
        exit();  
      }

      $data = [ 
        'password' => md5($this->POST('passwordnew')) 
      ];
      if ($this->login_m->update($this->data['profil']->email, $data)) {
        $this->flashmsg2('Password berhasil diganti!', 'success');
        redirect('karyawan/profile/');
        exit();  
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('karyawan/profile/');
        exit();  
      } 
    }

    $this->data['index'] = 5;
    $this->data['content'] = 'karyawan/profile';
    $this->template($this->data,'karyawan');
  }
  public function proses_edit_profil(){
    if ($this->POST('edit')) {
      
      


      
    } 
    elseif ($this->POST('edit2')) { 
      
      
      $this->login_m->update($this->data['email'],$data);
  
      $this->flashmsg('PASSWORD BARU TELAH TERSIMPAN!', 'success');
      redirect('karyawan/profil');
      exit();    
    }   
    else{ 
      redirect('karyawan/profil');
      exit();
    } 
  }  
 		

  public function download()
{  
  $this->load->helper('download');
  $explicit = $this->Diskusi_m->get_row(['id_diskusi' => $this->uri->segment(3)])->isi;
  $judul = $this->Diskusi_m->get_row(['id_diskusi' => $this->uri->segment(3)])->judul;
   
  $data = file_get_contents('./'.$explicit); 
  force_download($judul.'.pdf', $data); 
  redirect('karyawan','refresh');
}


public function download2()
{  
  $this->load->helper('download');
  $explicit = $this->Knowledge_m->get_row(['id_knowledge' => $this->uri->segment(3)])->isi;
  $judul = $this->Knowledge_m->get_row(['id_knowledge' => $this->uri->segment(3)])->judul;
   
  $data = file_get_contents('./'.$explicit); 
  force_download($judul.'.pdf', $data); 
  redirect('karyawan','refresh');
}


  public function cekemail(){ echo $this->login_m->cekemail2($this->input->post('email')); } 
  public function cekpasslama(){ echo $this->login_m->cekpasslama2($this->data['email'],$this->input->post('password')); } 
  public function cekpass(){ echo $this->login_m->cek_password_length2($this->input->post('password')); }
  public function cekpass2(){ echo $this->login_m->cek_passwords2($this->input->post('password'),$this->input->post('password2')); }
// PROFIL
 
}

 ?>
