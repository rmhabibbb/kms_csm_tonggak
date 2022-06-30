    <!-- Header -->
    <!-- Header -->
    <div class="header   pb-6" style="background-color: #C09366">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">  
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a  href="<?=base_url('karyawan')?>" ><i class="fas fa-home"></i></a></li> 
                  <li class="breadcrumb-item active" aria-current="page"><a  href="<?=base_url('karyawan/diskusi')?>" >Forum Diskusi</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Form Buat Diskusi </li>
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
              <h3 class="mb-0">Form Buat Diskusi</h3>
            </div>
            <!-- Light table --> 
            <form id="identifier" action="<?=base_url('karyawan/diskusi/')?>" method="POST" enctype="multipart/form-data"> 
                
            <div class="col-xl-12 order-xl-1">
                  <div class="row">

                    <div class="col-lg-12">

                      <input type="hidden" name="email" value="<?=$profil->email?>">
                        <input type="hidden" name="isi" value="NULL">
                      <div class="form-group">

                        <label class="form-control-label" >Judul</label>
                        <input required type="text" class="form-control" id="judul" name="judul" > 
                      </div>

                      <div class="form-group">
                        <label class="form-control-label" >Kategori</label>  
                        <select class="form-control" name="kategori" required>
                          <option value="">Pilih Kategori</option>
                          <?php foreach ($list_kategori as $row) { ?>
                          <option value="<?=$row->id_kategori?>"><?=$row->nama_kategori?></option>
                          <?php } ?>
                        </select>
                      </div>

                      <div class="form-group">
                          <label class="form-control-label" >Jenis</label>
                           <div class="custom-control custom-radio mb-3">
                            <input required class="custom-control-input" name="jeniskm" value="Tacit" id="tacit" type="radio">
                            <label class="custom-control-label" for="tacit">Tacit</label>
                          </div>
                          <div class="custom-control custom-radio mb-3">
                            <input required class="custom-control-input" name="jeniskm" value="Explicit" id="Explicit"  type="radio">
                            <label class="custom-control-label" for="Explicit">Explicit</label>
                          </div>

                      </div> 
                      <div class="form-group" id="divtexplicit">
                        <label class="form-control-label" >Knowledge (.pdf)</label> 
                        <input  type="file" class="form-control" id="file" name="foto" > 
                      </div>
                      <div class="form-group" id="divtacit">
                        <label class="form-control-label" >Knowledge</label> 
                        <textarea name="knowledge" class="form-control"  ></textarea>
                      </div>

                    </div>  
                  </div>
                
                    

                    <div class="row">
                      <div class="col-lg-12">
                        <center> 
                          <input type="submit" name="tambah" value="Tambah" class="btn bg-primary text-white">
                        </center>
                      </div>
                    </div>
                </div> 
              </form>
              <br>
          </div>
        </div>
      </div>
  
 