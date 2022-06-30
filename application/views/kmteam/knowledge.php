    <!-- Header -->
    <!-- Header -->
    <div class="header   pb-6" style="background-color: #C09366">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7"> 
              <h6 class="h2 text-white d-inline-block mb-0">Daftar Knowledge</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a  href="<?=base_url('kmteam')?>"><i class="fas fa-home"></i></a></li> 
                  <li class="breadcrumb-item active" aria-current="page">Knowledge</li>
                </ol>
              </nav>
            </div> 

            <div class="col-lg-6 col-5 text-right">
              <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-neutral"" class="btn btn-sm btn-neutral">Buat Pengajuan Baru</a> 
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
              <h3 class="mb-0">Daftar Pengajuan Knowledge</h3>
            </div>
            <!-- Light table -->

            <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-basic">
                <thead class="thead-light">
                  <tr>  
                    <th >No.</th>
                    <th >Judul</th> 
                    <th >Kategori</th> 
                    <th >Jenis</th> 
                    <th >KM Team</th> 
                    <th >Tanggal Buat</th> 
                    <th >Status Pengajuan</th> 
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody class="list">

                 <?php $i = 1; foreach ($knowledge as $row): ?> 
                  <tr> 
                    <td><?=$i++?></td>
                    <td style="white-space:normal" > <?=$row->judul?> </td> 
                    <td> <?=$this->Kategori_m->get_row(['id_kategori' => $row->id_kategori])->nama_kategori?> </td> 
                    <td> <?=$row->jenis?> </td> 
                    <td> <?=$this->KMTeam_m->get_row(['id_kmteam ' => $row->id_kmteam ])->nama?> </td>
                    <td> <?= date('d-m-Y',strtotime($row->tgl_buat)) ?> </td> 
                    <td> <?php 
                      if ($row->status == 0) {
                        echo "draft";
                      }
                      elseif ($row->status == 1) {
                        echo "Menunggu Verifikasi";
                      }elseif ($row->status == 2){
                        echo "Diterima";
                      }elseif ($row->status == 3){
                        echo "Ditolak. " . $row->keterangan;
                      }
                    ?> </td> 
                    <td class="text-right">
                      <a href="<?=base_url('kmteam/knowledge/'.$row->id_knowledge)?>"  >
                        <button type="button" class="btn btn-twitter btn-icon"> 
                          <span class="btn-inner--text">Lihat</span>
                        </button>
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
  
 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form Buat Knowledge</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <?= form_open_multipart('kmteam/knowledge/') ?>
      <div class="modal-body">
          
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
            
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" name="add" value="Submit"> 
      </div>
      </form>
    </div>
  </div>
</div>
