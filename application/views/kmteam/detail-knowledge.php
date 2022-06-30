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
            <div class="card-header border-0" style="padding-bottom: 0"><center>
              <h3 class="mb-0"><?=$knowledge->judul?></h3> 
            </div>  
            <!-- Light table --> 
            <br>
                
                <div class="col-xl-12 order-xl-1">
                  <form id="identifier" action="<?=base_url('kmteam/knowledge/')?>" method="POST" enctype="multipart/form-data"> 
                    <div class="row">

                      <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label" >Jenis</label><br>
                            <?=$knowledge->jenis?> 
                        </div> 
                      </div> 
                      <div class="col-lg-3">
                        <div class="form-group">
                          <label class="form-control-label" >Kategori</label><br> 
                          <?=$this->Kategori_m->get_row(['id_kategori' => $knowledge->id_kategori])->nama_kategori?>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="form-group">
                          <label class="form-control-label" >Tanggal Buat</label><br> 
                          <?=date('d-m-Y', strtotime($knowledge->tgl_buat))?>
                        </div>
                      </div> 
                      <div class="col-lg-3">
                        <div class="form-group">
                          <label class="form-control-label" >Status</label><br> 
                           <?php 
                      if ($knowledge->status == 0) {
                        echo "draft";
                      }
                      elseif ($knowledge->status == 1) {
                        echo "Menunggu Verifikasi";
                      }elseif ($knowledge->status == 2){
                        echo "Diterima";
                      }elseif ($knowledge->status == 3){
                        echo "Ditolak. " . $knowledge->keterangan;
                      }
                    ?>
                        </div>
                      </div> 

                    <div class="col-lg-12">  
                        

                        <?php if ($knowledge->jenis == 'Tacit') { ?>
                          <div class="form-group">
                          <label class="form-control-label" >Knowledge : </label>  
                          <br>
                          <textarea class="form-control" readonly style="height:350px; background-color: white"><?=$knowledge->isi?></textarea>
                        </div>
                        <?php }else{ ?>
                          <div class="form-group">
                          <label class="form-control-label" >Knowledge : </label>  
                          <?php if ($knowledge->isi != NULL) { ?>
                             <object data="<?=base_url($knowledge->isi)?>" type="application/pdf" width="100%" height="500">
                                      <p>Plugin penampil PDF tidak tersedia di browser Anda, <a href="<?=base_url('kmteam/download2/'.$knowledge->id_knowledge)?>">Silahkan klik disini untuk mendownload file.</a></p>
                                    </object>
                          <?php  } ?>
                         
                        </div>
                        <?php } ?> 

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
             
            </div> 

              
            </div>
              <div class="table-responsive py-4">
                <table class="table table-flush" id="datatable-basic">
                  <thead class="thead-light">
                    <tr>  
                      <th>No.</th>
                      <th>Referensi</th>  
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
                      
                     
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div> 
          </div> 
          
      </div>
  
 </div>

 