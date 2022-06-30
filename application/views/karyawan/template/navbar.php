  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark  border-bottom" style="background-color: #946038">
      <div class="container-fluid">

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
            <a  href="<?=base_url('admin')?>">
              <img src="<?=base_url('assets/argon/')?>img/brand/logo.PNG" class="navbar-brand-img" style="width: 200px"  alt="...">   
          </a>
          </form>
          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center  ml-md-auto ">
            
             
            <li class="nav-item d-xl-none">
              <!-- Sidenav toggler -->
              <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
           <li class="nav-item ">
              <a class="nav-link pr-0" href="<?=base_url('karyawan/')?>" >
                <div class="media align-items-center"> 
                  <div class="media-body  ml-2  d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold">Library Knowledge</span>
                  </div>
                </div>
              </a> 
            </li>
            <li class="nav-item ">
              <a class="nav-link pr-0" href="<?=base_url('karyawan/diskusi')?>" >
                <div class="media align-items-center"> 
                  <div class="media-body  ml-2  d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold">Forum Diskusi</span>
                  </div>
                </div>
              </a> 
            </li> 
            <li class="nav-item ">
              <a class="nav-link pr-0" href="<?=base_url('karyawan/profile')?>" >
                <div class="media align-items-center"> 
                  <div class="media-body  ml-2  d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold">Profil</span>
                  </div>
                </div>
              </a> 
            </li>
            <li class="nav-item ">
              <a class="nav-link pr-0" href="<?=base_url('logout')?>" >
                <div class="media align-items-center"> 
                  <div class="media-body  ml-2  d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold">Logout</span>
                  </div>
                </div>
              </a> 
            </li>

 
          </ul>
        </div>
      </div>
    </nav>
    <!-- Header -->
   