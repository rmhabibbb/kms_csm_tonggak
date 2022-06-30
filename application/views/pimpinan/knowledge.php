    <!-- Header -->
    <!-- Header -->
    <div class="header   pb-6" style="background-color: #C09366">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7"> 
              <h6 class="h2 text-white d-inline-block mb-0">Library Knowledge</h6>
             
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
              <form action="<?=base_url('pimpinan/Knowledge/')?>" method="post">
                <div class="row">
                  <div class="col-4">
                    <input type="text" name="key" placeholder="Masukkan kata kunci pencarian .." class="form-control" value="<?php if(isset($key)){ echo $key; } ?>">
                  </div>
                  <div class="col-3">
                    <select class="form-control" name="kategori">
                          <?php if (isset($kategori) && $kategori != '') { ?>
                            <option value="<?=$kategori?>"><?=$this->Kategori_m->get_row(['id_kategori' => $kategori])->nama_kategori?></option>
                          <?php } ?>
                          <option value="">Semua Kategori</option>
                          <?php foreach ($list_kategori as $row) { 
                              if ($row->id_kategori != $kategori) {  ?>
                          <option value="<?=$row->id_kategori?>"><?=$row->nama_kategori?></option>
                          <?php } } ?>
                        </select>
                  </div>
                  <div class="col-3">
                    <select class="form-control" name="jenis">
                      <?php if (isset($jenis) && $jenis != '') { ?>
                        <?php if ($jenis == 'Tacit') { ?> 
                            <option value="<?=$jenis?>"><?=$jenis?></option>
                            <option value="Explicit">Explicit</option> 
                              <option value="">Semua Jenis</option> 
                           <?php }else{ ?>
                            <option value="<?=$jenis?>"><?=$jenis?></option>
                            <option value="Tacit">Tacit</option> 
                              <option value="">Semua Jenis</option> 
                            <?php } ?>
                          <?php }else{  ?> 
                              <option value="">Semua Jenis</option> 
                            <option value="Explicit">Explicit</option> 
                            <option value="Tacit">Tacit</option> 
                          <?php } ?>
 
                        </select>
                  </div>
                  <div class="col-2">
                      <input type="submit" name="cari" class="btn btn-block" style="background-color: #C09366; color: white" value="Cari">
                  </div>
                </div>
              </form>
            </div>
            <!-- Light table -->

           
          </div>


          <?php if (isset($list_knowledge)) { ?>
          <div class="row align-items-center justify-content-center">

            <?php $i = 1; foreach ($list_knowledge as $row): ?> 
            <div class="col-xl-10 col-md-8">
              <div class="card card-stats">
                <!-- Card body -->
                <a href="<?=base_url('pimpinan/knowledge/'.$row->id_knowledge)?>"  >
                <div class="card-body">
                  <div class="row">

                    <div class="col-auto">
                      <div class="icon icon-shape   rounded-circle shadow" style="background-color: #C09366; color: white">
                        <?php if ($row->jenis == 'Tacit') { ?>  
                          <i class="ni ni-bulb-61"></i>
                        <?php }else{ ?> 
                          <i class="ni ni-folder-17"></i>
                         <?php } ?>
                      </div>
                    </div>
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0"><?=$row->jenis?> - <?=$this->Kategori_m->get_row(['id_kategori' => $row->id_kategori])->nama_kategori?></h5>
                      <span class="h2 font-weight-bold mb-0"><?=$row->judul?></span>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"></i><?=$this->KMTeam_m->get_row(['id_kmteam ' => $row->id_kmteam ])->nama?></span>
                    <span class="text-nowrap"><?= date('d-m-Y',strtotime($row->tgl_buat)) ?></span>
                  </p>
                </div>
              </div>
              </a> 
            </div> 
            <?php endforeach; ?>
          </div>
          <?php }?>
        </div>
      </div>
   