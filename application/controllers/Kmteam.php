<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Kmteam extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
  
        $this->data['email'] = $this->session->userdata('email');
        $this->data['id_role']  = $this->session->userdata('id_role'); 
          if (!$this->data['email'] || ($this->data['id_role'] != 2))
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
    $this->data['kar'] = $this->KMTeam_m->get_row(['email' =>$this->data['email'] ]);   
     
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
      $this->data['content'] = 'kmteam/dashboard';
      $this->template($this->data,'kmteam');
}


public function hapusfixknowledge(){
  if ($this->POST('delete')) {
    if ($this->Knowledge_m->delete($this->POST('id_knowledge'))) {
      $this->flashmsg2('Knowledge berhasil dihapus.', 'success');
      redirect('kmteam/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/');
      exit();  
    }
  }else{
    redirect('kmteam');
    exit();
  }
}

public function id()
{     

  if ($this->uri->segment(3)) {
    $id = $this->uri->segment(3);


    $this->data['knowledge'] = $this->Knowledge_m->get_row(['id_knowledge' => $id ]); 
    $this->data['index'] = 1;
 
    $this->data['content'] = 'kmteam/fix-knowledge';
   
    $this->template($this->data,'kmteam');
  }
  else {
    redirect('kmteam');
    exit();
  }
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
      'jenis' => $this->POST('jeniskm')
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
    $this->data['content'] = 'kmteam/detail-diskusi';
    $this->template($this->data,'kmteam');
  }
  else {
    $this->data['diskusi'] = $this->Diskusi_m->get_by_order('tgl_buat','desc',[]);
    $this->data['index'] = 2;
    $this->data['content'] = 'kmteam/diskusi';
    $this->template($this->data,'kmteam');
  }
}


public function hapuskomentar(){ 
  if ($this->Komentar_m->delete($this->uri->segment(4))) {
    $this->flashmsg2('Komentar berhasil dihapus', 'success');
      redirect('kmteam/diskusi/'.$this->uri->segment(3));
      exit(); 
  }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/diskusi/'.$this->uri->segment(3));
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
  redirect('kmteam','refresh');
}

public function download2()
{  
  $this->load->helper('download');
  $explicit = $this->Knowledge_m->get_row(['id_knowledge' => $this->uri->segment(3)])->isi;
  $judul = $this->Knowledge_m->get_row(['id_knowledge' => $this->uri->segment(3)])->judul;
   
  $data = file_get_contents('./'.$explicit); 
  force_download($judul.'.pdf', $data); 
  redirect('kmteam','refresh');
}

public function kategori()
{     

  if ($this->POST('add')) {  
     
    $data = [
      'nama_kategori' => $this->POST('nama')  
    ];

    if ($this->Kategori_m->insert($data)) {
 
         $this->flashmsg2('Kategori berhasil ditambah', 'success');
          redirect('kmteam/kategori/');
          exit(); 
      

      
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/kategori/');
      exit();  
    }
  }
  elseif ($this->POST('edit')) { 
         
    $data = [
      'nama_kategori' => $this->POST('nama')  
    ];

    
    

    if ($this->Kategori_m->update($this->POST('id'),$data)) {

      
        $this->flashmsg2('Kategori berhasil diedit.', 'success');
        redirect('kmteam/kategori/');
        exit(); 
     

       
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/kategori/');
      exit();  
    } 
  } 
  elseif ($this->POST('delete')) {
    if ($this->Kategori_m->delete($this->POST('id'))) {
      $this->flashmsg2('Kategori berhasil dihapus.', 'success');
      redirect('kmteam/kategori/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/kategori/');
      exit();  
    }
  }
  else {
    $this->data['kategori'] = $this->Kategori_m->get();
    $this->data['index'] = 4;
    $this->data['content'] = 'kmteam/kategori';
    $this->template($this->data,'kmteam');
  }
}


public function knowledge()
{     

  if ($this->POST('add')) { 
    
 
    $data = [ 
      'judul' => $this->POST('judul'), 
      'id_kategori' => $this->POST('kategori'),
      'id_kmteam' => $this->data['kar']->id_kmteam,
      'tgl_buat' => date('Y-m-d H:i:s'),
      'status' => 0,
      'jenis' => $this->POST('jeniskm')
    ];

    if ($this->Knowledge_m->insert($data)) {
      $id = $this->db->insert_id();
      $this->flashmsg2('Knowledge berhasil dibuat', 'success');
      redirect('kmteam/knowledge/'.$id );
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/knowledge/');
      exit();  
    }
  } 
  elseif ($this->POST('simpan')) { 
    
    if ($this->POST('jeniskm') == 'Tacit') {
      $km = $this->POST('knowledge');
      $data = [ 
                'judul' => $this->POST('judul'),  
                'isi' => $km
              ];
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
          
 		if ($this->POST('isi') != NULL) {
            unlink('./'.$this->POST('isi'));
          }

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


              $data = [ 
                'judul' => $this->POST('judul'),  
                'isi' => $km,
                'filename' => $name
              ];
            }else{   
              $data = [ 
                'judul' => $this->POST('judul') 
              ];
            }

    }
    

    if ($this->Knowledge_m->update($this->POST('id'),$data)) { 
      $this->flashmsg2('Knowledge berhasil disimpan', 'success');
      redirect('kmteam/knowledge/'.$this->POST('id') );
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/knowledge/'.$this->POST('id'));
      exit();  
    }
  } 
  elseif ($this->POST('kirim')) { 
     
    if ($this->Knowledge_m->update($this->POST('id'),['status' => 1])) { 
      $this->flashmsg2('Pengajuan Knowledge berhasil dikirim', 'success');
      redirect('kmteam/knowledge/'.$this->POST('id') );
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/knowledge/'.$this->POST('id'));
      exit();  
    }
  } 
  elseif ($this->POST('delete')) {
    if ($this->Knowledge_m->delete($this->POST('id'))) {
      $this->flashmsg2('Knowledge berhasil dihapus.', 'success');
      redirect('kmteam/knowledge/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/knowledge/');
      exit();  
    }
  }elseif ($this->uri->segment(3)) {
    $id = $this->uri->segment(3);


    $this->data['knowledge'] = $this->Knowledge_m->get_row(['id_knowledge' => $id ]);
    $this->data['list_ref'] = $this->Referensi_m->get(['id_knowledge' => $id ]);
    $this->data['list_diskusi'] = $this->Diskusi_m->get();
    $this->data['index'] = 3;

    if ($this->data['knowledge']->status == 0) {
      $this->data['content'] = 'kmteam/detail-knowledge-draft';
    }else{
      $this->data['content'] = 'kmteam/detail-knowledge';
    }
    $this->template($this->data,'kmteam');
  }
  else {
    $this->data['knowledge'] = $this->Knowledge_m->get_by_order('tgl_buat','desc',[]);
    $this->data['list_kategori'] = $this->Kategori_m->get();
    $this->data['index'] = 3;
    $this->data['content'] = 'kmteam/knowledge';
    $this->template($this->data,'kmteam');
  }
}


public function tambahref(){
  $id_knowledge = $this->uri->segment(3);
  $id_diskusi = $this->uri->segment(4);

  $data = [
    'id_knowledge' => $id_knowledge,
    'id_diskusi' => $id_diskusi
  ];

  if ($this->Referensi_m->insert($data)) {$id = $this->db->insert_id();
      $this->flashmsg2('Referensi berhasil ditambah', 'success');
      redirect('kmteam/knowledge/'.$id_knowledge );
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/knowledge/'.$id_knowledge);
      exit();  
    }
}

public function hapusref(){
  $id_knowledge = $this->uri->segment(3);
  $id = $this->uri->segment(4);
 
  if ($this->Referensi_m->delete($id)) { 
      $this->flashmsg2('Referensi berhasil dihapus', 'success');
      redirect('kmteam/knowledge/'.$id_knowledge );
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('kmteam/knowledge/'.$id_knowledge);
      exit();  
    }
}


// PROFIL
  public function profile(){
    if ($this->POST('save')) {
      if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email') != $this->POST('emailx')) { 
        $this->flashmsg2('Email telah digunakan!', 'warning');
        redirect('kmteam/profile/');
        exit();  
      }

        if ($this->login_m->update($this->POST('emailx'),['email' => $this->POST('email')])) {

          $d = [ 
            'nama' =>  $this->POST('nama'),
            'email' => $this->POST('email'),     
            'jk' => $this->POST('jk') 
          ];

          if ($this->KMTeam_m->update($this->POST('id'),$d)) {
            $user_session = [
              'email' => $this->POST('email')
            ];
            $this->session->set_userdata($user_session);

            $this->flashmsg2('Berhasil!', 'success');
            redirect('kmteam/profile/');
            exit(); 
          }else{
            $this->flashmsg2('Gagal, Coba lagi!', 'warning');
            redirect('kmteam/profile/');
            exit();  
          } 
           
        }else{
          $this->flashmsg2('Gagal, Coba lagi!', 'warning');
          redirect('kmteam/profile/');
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
        redirect('kmteam/profile/');
        exit();  
      }

      $data = [ 
        'password' => md5($this->POST('passwordnew')) 
      ];
      if ($this->login_m->update($this->data['profil']->email, $data)) {
        $this->flashmsg2('Password berhasil diganti!', 'success');
        redirect('kmteam/profile/');
        exit();  
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('kmteam/profile/');
        exit();  
      } 
    }

    $this->data['index'] = 5;
    $this->data['content'] = 'kmteam/profile';
    $this->template($this->data,'kmteam');
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
 
  public function cekemail(){ echo $this->login_m->cekemail2($this->input->post('email')); } 
  public function cekpasslama(){ echo $this->login_m->cekpasslama2($this->data['email'],$this->input->post('password')); } 
  public function cekpass(){ echo $this->login_m->cek_password_length2($this->input->post('password')); }
  public function cekpass2(){ echo $this->login_m->cek_passwords2($this->input->post('password'),$this->input->post('password2')); }
// PROFIL
 
}

 ?>
