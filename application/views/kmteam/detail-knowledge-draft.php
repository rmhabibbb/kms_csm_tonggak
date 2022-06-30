    <!-- Header -->
    <!-- Header -->
    <div class="header   pb-6" style="background-color: #C09366">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-12">  
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a  href="<?=base_url('kmteam')?>" ><i class="fas fa-home"></i></a></li> 
                  <li class="breadcrumb-item active" aria-current="page"><a  href="<?=base_url('kmteam/knowledge')?>" >Pengajuan Knowledge</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?=$knowledge->id_knowledge?></li>
                </ol>
              </nav>
            </div>  
          </div>
        </div>
        <?= $this->session->flashdata('msg2') ?>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row"> 
          <div class="card col-lg-12">
            <!-- Card header -->
            <div class="card-header border-0">  
            </div>
            <!-- Light table --> 
            
                
                <div class="col-xl-12 order-xl-1">
                  <form id="identifier" action="<?=base_url('kmteam/knowledge/')?>" method="POST" enctype="multipart/form-data"> 
                    <div class="row">

                      <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label" >Jenis</label><br>
                            <?=$knowledge->jenis?> 
                        </div> 
                      </div> 
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label class="form-control-label" >Kategori</label><br> 
                          <?=$this->Kategori_m->get_row(['id_kategori' => $knowledge->id_kategori])->nama_kategori?>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label class="form-control-label" >Tanggal Buat</label><br> 
                          <?=date('d-m-Y', strtotime($knowledge->tgl_buat))?>
                        </div>
                      </div> 

                    <div class="col-lg-12"> 
                        <input type="hidden" name="id" value="<?=$knowledge->id_knowledge?>">
                        <input type="hidden" name="jeniskm" value="<?=$knowledge->jenis?>">
                        <input type="hidden" name="isi" value="<?=$knowledge->isi?>">
                        <div class="form-group">

                          <label class="form-control-label" >Judul</label>
                          <input required type="text" class="form-control" id="judul" name="judul" value="<?=$knowledge->judul?>"> 
                        </div>

                        

                        <?php if ($knowledge->jenis == 'Tacit') { ?>
                          <div class="form-group">
                          <label class="form-control-label" >Knowledge</label>  
                            <textarea name="knowledge" class="form-control"  required><?=$knowledge->isi?></textarea>
                        </div>
                        <?php }else{ ?>
                          <div class="form-group">
                          <?php if ($knowledge->isi != NULL) { ?>
                              
                             	<object data="<?=base_url($knowledge->isi)?>" type="application/pdf" width="100%" height="500">
                                      <p>Plugin penampil PDF tidak tersedia di browser Anda, <a href="<?=base_url('kmteam/download2/'.$knowledge->id_knowledge)?>">Silahkan klik disini untuk mendownload file.</a></p>
                                    </object>
                          <?php  } ?>
                          <label class="form-control-label" >Knowledge (.pdf)</label> 
                          <input  type="file" class="form-control" id="file" name="foto" > 
                        </div>
                        <?php } ?> 

                      </div> 


                      <div class="col-lg-12">
                        <center> 
                          <a  href="#" data-toggle="modal" data-target="#delete" class="btn bg-danger text-white">
                          Hapus
                          </a>
                          <input type="submit" name="simpan" value="Simpan" class="btn bg-primary text-white"><br><br>
                        </center>
                      </div>
                  </div>
                
                    
                  </form>
                </div> 
               
          </div>

          <div class="card col-lg-12">
            <!-- Card header -->
            <div class="card-header border-0"> 
              <div class="col">
                <h3 class="text-left">Referensi Diskusi
              </h3>
              <div class="  text-right">
              <a href="#" data-toggle="modal" data-target="#tambahref" class="btn bg-primary text-white">Tambah Referensi</a>
              </div>
            </div> 

              
            </div>
              <div class="table-responsive py-4">
                <table class="table table-flush" id="datatable-basic">
                  <thead class="thead-light">
                    <tr>  
                      <th>No.</th>
                      <th>Referensi</th> 
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody class="list">

                   <?php $i = 1; foreach ($list_ref as $row): ?> 
                   <?php $dis = $this->Diskusi_m->get_row(['id_diskusi' => $row->id_diskusi])?>
                    <tr> 
                      <td >
                        <?=$i?>
                      </td>
                      <td style="white-space:normal" > 
                        <a href="<?=base_url('kmteam/diskusi/'.$row->id_diskusi)?>" target="_blank" style="color: black">
                        <b><?=$dis->judul?></b><br>
                        <i><?=$this->Karyawan_m->get_row(['id_karyawan' => $dis->id_karyawan])->nama?> - <?=date('d/m/Y', strtotime($dis->tgl_buat)) ?></i><br>
                        <span><?=$dis->jenis?> - <?=$this->Kategori_m->get_row(['id_kategori' => $dis->id_kategori])->nama_kategori?></span>
                        </a>
                      </td> 
                      
                      <td class="text-right">
                       
                        <a href="<?=base_url('kmteam/hapusref/'.$knowledge->id_knowledge.'/'.$row->id)?>">
                          <button type="button" class="btn btn-instagram btn-icon"> 
                            <span class="btn-inner--text">Delete</span>
                          </button>
                        </a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div> 
          </div> 
          <div class="card col-lg-12">
            <!-- Card header -->
            <div class="card-header border-0">  
            </div> 

              <a href="#" data-toggle="modal" data-target="#kirim" class="btn bg-primary text-white">Kirim Pengajuan Knowledge</a>

              <br>
            </div> 
          </div> 
      </div>
  
 </div>

<div class="modal fade" id="tambahref" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Daftar Referensi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       
      <div class="modal-body">
          
              <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-basic2">
                <thead class="thead-light">
                  <tr>  
                    <th>No.</th>
                    <th>Diskusi</th> 
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody class="list">

                 <?php $i = 1; foreach ($list_diskusi as $row): ?> 
                 <?php if ($this->Referensi_m->get_num_row(['id_diskusi' => $row->id_diskusi]) == 0) { ?>
                  <tr> 
                    <td >
                      <?=$i?>
                    </td>
                    <td style="white-space:normal" >
                      <b><?=$row->judul?></b><br>
                      <i><?=$this->Karyawan_m->get_row(['id_karyawan' => $row->id_karyawan])->nama?> - <?=date('d/m/Y', strtotime($row->tgl_buat)) ?></i><br>
                      <span><?=$row->jenis?> - <?=$this->Kategori_m->get_row(['id_kategori' => $row->id_kategori])->nama_kategori?></span>
                    </td> 
                    
                    <td class="text-right">
                     
                      <a href="<?=base_url('kmteam/tambahref/'.$knowledge->id_knowledge.'/'.$row->id_diskusi)?>" >
                        <button type="button" class="btn bg-primary btn-icon"> 
                          <span class="btn-inner--text text-white" >+</span>
                        </button>
                      </a>
                    </td>
                  </tr>
                  <?php } endforeach; ?>
                </tbody>
              </table>
            </div> 
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
      </div> 
    </div>
  </div>
</div>

<div class="modal fade" id="kirim" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-primar modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-success"> 
              
                  <div class="modal-body">
                    
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x text-white"></i>
                          <h4 class="heading mt-4 text-white"> Kirim Pengajuan Knowledge sekarang ?</h4> 
                      </div>
                      
                  </div>
                  
                  <form action="<?= base_url('kmteam/knowledge')?>" method="Post" >  
                  <div class="modal-footer">

                   
                      <input type="hidden" value="<?=$knowledge->id_knowledge?>" name="id">  
                      <input type="submit" class="btn btn-white" name="kirim" value="Kirim">
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Batal</button>
                  </div>
                </form>
          </div>
  </div>
</div>

<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-primar modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger"> 
              
                  <div class="modal-body">
                    
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x text-white"></i>
                          <h4 class="heading mt-4 text-white"> Hapus Pengajuan Knowledge ?</h4> 
                      </div>
                      
                  </div>
                  
                  <form action="<?= base_url('kmteam/knowledge')?>" method="Post" >  
                  <div class="modal-footer">

                   
                      <input type="hidden" value="<?=$knowledge->id_knowledge?>" name="id">  
                      <input type="submit" class="btn btn-white" name="delete" value="Hapus">
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Batal</button>
                  </div>
                </form>
          </div>
  </div>
</div>