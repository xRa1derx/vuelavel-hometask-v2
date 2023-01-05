<template>
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"
            ><i class="fas fa-bars"></i
          ></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <router-link class="btn" :to="{ name: 'home' }">Home</router-link>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="btn">Contact</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
          <a
            class="nav-link"
            data-widget="navbar-search"
            href="#"
            role="button"
          >
            <i class="fas fa-search white"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input
                  class="form-control form-control-navbar"
                  type="search"
                  placeholder="Search"
                  aria-label="Search"
                />
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button
                    class="btn btn-navbar"
                    type="button"
                    data-widget="navbar-search"
                  >
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-comments white"></i>
            <span class="badge badge-danger navbar-badge">3</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <!-- <img src="../../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle"> -->
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Brad Diesel
                    <span class="float-right text-sm text-danger"
                      ><i class="fas fa-star"></i
                    ></span>
                  </h3>
                  <p class="text-sm">Call me whenever you can...</p>
                  <p class="text-sm text-muted">
                    <i class="far fa-clock mr-1"></i> 4 Hours Ago
                  </p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <!-- <img src="../../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3"> -->
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    John Pierce
                    <span class="float-right text-sm text-muted"
                      ><i class="fas fa-star"></i
                    ></span>
                  </h3>
                  <p class="text-sm">I got your message bro</p>
                  <p class="text-sm text-muted">
                    <i class="far fa-clock mr-1"></i> 4 Hours Ago
                  </p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <!-- <img src="../../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3"> -->
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Nora Silvester
                    <span class="float-right text-sm text-warning"
                      ><i class="fas fa-star"></i
                    ></span>
                  </h3>
                  <p class="text-sm">The subject goes here</p>
                  <p class="text-sm text-muted">
                    <i class="far fa-clock mr-1"></i> 4 Hours Ago
                  </p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer"
              >See All Messages</a
            >
          </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell white"></i>
            <span class="badge badge-warning navbar-badge">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer"
              >See All Notifications</a
            >
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt white"></i>
          </a>
        </li>
        <li class="nav-item">
          <a
            class="nav-link"
            data-widget="control-sidebar"
            data-slide="true"
            href="#"
            role="button"
          >
            <i class="fas fa-th-large white"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4 position-fixed ">
      <!-- Brand Logo -->
      <!-- <a href="../../index3.html" class="brand-link text-center"> </a> -->
      <router-link
        :to="{ name: 'admin' }"
        class="brand-link text-center"
        style="background-color: #242424"
      >
        <span class="brand-text font-weight-light">Admin page</span>
      </router-link>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img
              src="../../assets/images/avatar_admin.png"
              class="img-circle elevation-2"
              alt="User Image"
            />
          </div>
          <div class="info">
            <a href="#" class="d-block">Alyona Ozhered</a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input
              class="form-control form-control-sidebar"
              type="search"
              placeholder="Search"
              aria-label="Search"
            />
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2 overflow-auto">
          <ul
            class="nav nav-pills nav-sidebar flex-column"
            data-widget="treeview"
            role="menu"
            data-accordion="false"
          >
            <!-- Blog Header -->
            <li class="nav-header">
              <strong>BLOG SECTION</strong>
            </li>
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
              <router-link class="nav-link" :to="{ name: 'posts.create'}"
                ><i class="nav-icon fa fa-solid fa-blog"></i>Posts</router-link
              >
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/admin/categories"
                ><i class="nav-icon fas fa-solid fa-list"></i
                >Categories</router-link
              >
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/admin/comments"
                ><i class="nav-icon far fa-solid fa-envelope-open"></i
                >Comments<span class="badge badge-info right"
                  >2</span
                ></router-link
              >
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/admin/tags"
                ><i class="nav-icon fas fa-regular fa-tag"></i>Tags</router-link
              >
            </li>
            <li class="nav-header">
              <strong>CHAT SECTION</strong>
            </li>
            <li class="nav-item">
              <router-link
                class="nav-link"
                :class="{ active: isActive }"
                @click="isActive = !isActive"
                to="/admin/users"
              >
                <i class="nav-icon fas fa-regular fa-user"></i>
                Students
                <i class="right fas fa-angle-left"></i>
              </router-link>
              <ul class="nav nav-treeview" :class="{ openTreeview: isActive }">
                <li class="nav-item">
                  <router-link class="nav-link" :to="{ name: 'users.create' }">
                    <i class="nav-icon fas fa-solid fa-user-plus"></i>
                    Add new
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/messages"
                ><i class="nav-icon fas fa-thin fa-comment-dots"></i
                >Messages<span class="badge badge-info right"
                  >5</span
                ></router-link
              >
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper admin-content">
      <!-- Content Header (Page header) -->
      <section v-if="this.$route.name == 'admin'" class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Welcome to admin panel</h1>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12 mt-3">
              <router-view v-slot="slotProps">
                <transition name="route" mode="out-in">
                  <component :is="slotProps.Component"></component>
                </transition>
              </router-view>
            </div>
          </div>
        </div>
      </section>
      <!-- /.content -->
      <transition name="fade">
        <router-link
          v-if="$route.name == 'users'"
          :to="{ name: 'users.create' }"
        >
          <button class="btn btn-warning addUser">
            <i class="text-dark fas fa-solid fa-plus"></i>
          </button>
        </router-link>
      </transition>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer"></footer>

    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isActive: false,
    };
  },
};
</script>

<style scoped>
.wrapper {
  overflow: hidden;
}
.main-sidebar,
.main-footer,
.main-header {
  background-color: #242424;
}
.content-wrapper {
  position: relative;
  background-color: #3b3b3b;
}

.admin-content{
  margin-top: 55px;
}

.content{
  margin-top: 55px;
}

.main-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  border-bottom: 1px solid #ff7600;
}

.main-footer {
  border-top: 1px solid #ff7600;
}

.nav-icon,
.fa-bars {
  color: #ff7600;
}

.btn,
.white {
  color: #fff;
}

.active {
  background-color: #ff770024 !important;
}

.nav-treeview {
  margin-left: 15px;
}

.openTreeview {
  display: block !important;
  animation-duration: 0.3s;
  animation-name: slidein;
}

.mt-2 {
  height: 50vh;
}

.addUser {
  position: fixed;
  right: 30px;
  bottom: 50px;
  width: 50px;
  height: 50px;
  font-size: 1.3rem;
}

@keyframes slidein {
  from {
    margin-top: -20px;
    opacity: 0;
  }
}

.router-link-active {
  background-color: rgba(255, 255, 255, 0.1);
  color: #fff !important;
}

/* animations */

.route-enter-from {
  opacity: 0.1;
  transform: translateY(-15px);
}
.route-enter-active {
  transition: all 0.5s ease-out;
}
.route-leave-active {
  transition: all 0.1s ease-in;
}
.route-leave-to {
  opacity: 0;
  transform: translateY(15px);
}

.fade-enter-from {
  opacity: 0.1;
}
.fade-enter-active {
  transition: all 0.5s ease-out;
}
.fade-leave-active {
  transition: all 0.1s ease-in;
}
.fade-leave-to {
  opacity: 0;
}
</style>
