    <!-- Header -->
    <!-- Header -->
  <?php 
  function humanTiming ($time)
        {

            $time = time() - $time; // to get the time since that moment
            $time = ($time<1)? 1 : $time;
            $tokens = array (
                31536000 => 'tahun yang lalu',
                2592000 => 'bulan yang lalu',
                604800 => 'minggu yang lalu',
                86400 => 'haru yang lalu',
                3600 => 'jam yang lalu',
                60 => 'menit yang lalu',
                1 => 'detik yang lalu'
            );

            foreach ($tokens as $unit => $text) {
                if ($time < $unit) continue;
                $numberOfUnits = floor($time / $unit);
                return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'':'');
            }

        }?>


    <div class="header   pb-6" style="background-color: #C09366">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">  
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a  href="<?=base_url('karyawan')?>" ><i class="fas fa-home"></i></a></li> 
                  <li class="breadcrumb-item active" aria-current="page"><a  href="<?=base_url('karyawan/diskusi')?>" >Forum Diskusi</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?=$diskusi->id_diskusi?> </li>
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
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0"><center>
              <h3 class="mb-0"><?=$diskusi->judul?> (<?=$diskusi->jenis?> - <?=$this->Kategori_m->get_row(['id_kategori' => $diskusi->id_kategori])->nama_kategori?> )</h3>
              <p style="margin-bottom: 0"><?=$this->Karyawan_m->get_row(['id_karyawan' => $diskusi->id_karyawan])->nama?> - <?=date('d/m/Y',strtotime($diskusi->tgl_buat))?></p></center></h3>
            </div>  
            <div class="card-body">
              <?php if ($diskusi->jenis == 'Tacit') { ?>
                  <textarea class="form-control" readonly style="height:350px; background-color: white"><?=$diskusi->isi?></textarea>

                 <?php }else { ?>
                  <object data="<?=base_url($diskusi->isi)?>" type="application/pdf" width="100%" height="500">
                                      <p>Plugin penampil PDF tidak tersedia di browser Anda, <a href="<?=base_url('karyawan/download/'.$diskusi->id_diskusi)?>">Silahkan klik disini untuk mendownload file.</a></p>
                                    </object>
                   
              <?php 
                 } 
              ?>  
              <?php if ($diskusi->status == 1 && $diskusi->id_karyawan == $kar->id_karyawan) { ?> 
              <hr>
                <a href="" data-toggle="modal" data-target="#tutup">
                        <button type="button" class="btn bg-warning btn-icon text-white" > 
                          <span class="btn-inner--text">Tutup</span>
                        </button>
                      </a>
                      <a href="" data-toggle="modal" data-target="#delete">
                        <button type="button" class="btn btn-instagram btn-icon"> 
                          <span class="btn-inner--text">Hapus</span>
                        </button>
                      </a>
              <?php  } ?>
            </div>
          </div>
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
              <!-- Title -->
              <h5 class="h3 mb-0">Komentar</h5>
            </div>
            <!-- Card body -->
            <div class="card-body p-0">
              <!-- List group -->
              <div class="list-group list-group-flush">
                
                <?php foreach ($list_komentar as $k) { ?> 
                <a   class="list-group-item list-group-item-action flex-column align-items-start py-4 px-4">
                  <div class="d-flex w-100 justify-content-between">
                    <div>
                      <div class="d-flex w-100 align-items-center"> 
                        <h5 class="mb-1"><?=$this->Karyawan_m->get_row(['id_karyawan' => $k->id_karyawan])->nama?> 

                        <?php if ($k->id_karyawan == $kar->id_karyawan) { ?> 
                          <i  href="#" data-toggle="modal" data-target="#hapuskomen-<?=$k->id?>" style="color:red">(Hapus)</i>
                        <?php } ?>
                      </h5>
                      </div>
                    </div>
                    <small>
                      <?php 
                        echo humanTiming( strtotime($k->dt) ); 
                      ?>


                    </small>
                  </div> 
                  <p class="text-sm mb-0"><?=$k->komentar?></p>

                </a> 

                <?php } ?>

              </div>

                
              <?php if ($diskusi->status == 1) { ?> 

                <form class="p-4" action="<?=base_url('karyawan/berikomentar')?>" method="POST">
                  <input type="hidden" name="id" value="<?=$diskusi->id_diskusi?>">
                  <textarea required class="form-control" placeholder="Berikan Komentar anda" name="komentar"></textarea>
                  <input style="margin-top: 5px" type="submit" name="kirim" value="Kirim" class="btn btn-block bg-primary text-white">
                </form>
                <?php }else{
                  if (sizeof($list_komentar) == 0) {
                    echo "<center><p style='margin-top:5px'>Tidak ada komentar.</p></center>";
                  }
                } ?>
            </div>
          </div>
        </div>
      </div>
  
 <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger"> 
              
                  <div class="modal-body">
                    
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Hapus diskusi ini? </h4> 
                      </div>
                      
                  </div>
                  
                  <form action="<?= base_url('karyawan/diskusi')?>" method="Post" >  
                  <div class="modal-footer">

                   
                      <input type="hidden" value="<?=$diskusi->id_diskusi?>" name="id_diskusi">  
                      <input type="submit" class="btn btn-white" name="delete" value="Ya!">
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                  </div>
                </form>
          </div>
  </div>
</div>

 <div class="modal fade" id="tutup" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-warning"> 
              
                  <div class="modal-body">
                    
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Tutup diskusi ini? </h4> 
                      </div>
                      
                  </div>
                  
                  <form action="<?= base_url('karyawan/diskusi')?>" method="Post" >  
                  <div class="modal-footer">

                   
                      <input type="hidden" value="<?=$diskusi->id_diskusi?>" name="id">  
                      <input type="submit" class="btn btn-white" name="tutup" value="Tutup">
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                  </div>
                </form>
          </div>
  </div>
</div>


<?php foreach ($list_komentar as $k) { ?> 
  <?php if ($k->id_karyawan == $kar->id_karyawan) { ?>
 <div class="modal fade" id="hapuskomen-<?=$k->id?>" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger"> 
              
                  <div class="modal-body">
                    
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Hapus Komentar ini? </h4> 
                      </div>
                      
                  </div> 
                  <div class="modal-footer">

                     <a href="<?=base_url('karyawan/hapuskomentar/'.$diskusi->id_diskusi.'/'.$k->id)?>">

                      <input type="submit" class="btn btn-white" name="delete" value="Ya!">
                     </a>
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                  </div>
               
          </div>
  </div>
</div>
<?php } }  ?>