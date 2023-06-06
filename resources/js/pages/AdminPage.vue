<template>
    <div class="wrapper" id="wrap">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a
                        class="nav-link burger"
                        data-widget="pushmenu"
                        href="#"
                        role="button"
                        ><i class="fas fa-bars"></i
                    ></a>
                </li>
                <li class="">
                    <router-link class="btn" :to="{ name: 'home' }"
                        >Home</router-link
                    >
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
                                    <button
                                        class="btn btn-navbar"
                                        type="submit"
                                    >
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
                    <div
                        class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                    >
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span
                                            class="float-right text-sm text-danger"
                                            ><i class="fas fa-star"></i
                                        ></span>
                                    </h3>
                                    <p class="text-sm">
                                        Call me whenever you can...
                                    </p>
                                    <p class="text-sm text-muted">
                                        <i class="far fa-clock mr-1"></i> 4
                                        Hours Ago
                                    </p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span
                                            class="float-right text-sm text-muted"
                                            ><i class="fas fa-star"></i
                                        ></span>
                                    </h3>
                                    <p class="text-sm">
                                        I got your message bro
                                    </p>
                                    <p class="text-sm text-muted">
                                        <i class="far fa-clock mr-1"></i> 4
                                        Hours Ago
                                    </p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span
                                            class="float-right text-sm text-warning"
                                            ><i class="fas fa-star"></i
                                        ></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted">
                                        <i class="far fa-clock mr-1"></i> 4
                                        Hours Ago
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
                    <div
                        class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                    >
                        <span class="dropdown-item dropdown-header"
                            >15 Notifications</span
                        >
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm"
                                >3 mins</span
                            >
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm"
                                >12 hours</span
                            >
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm"
                                >2 days</span
                            >
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer"
                            >See All Notifications</a
                        >
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside
            class="main-sidebar sidebar-dark-primary elevation-4 position-fixed"
        >
            <!-- Brand Logo -->
            <router-link
                :to="{ name: 'admin' }"
                class="brand-link text-center"
                style="background-color: #242424"
            >
                <span class="brand-text font-weight-light">Admin page</span>
            </router-link>

            <!-- Sidebar -->
            <sidebar-component
                v-if="$route.meta.sidebar === 'chat'"
            ></sidebar-component>
            <div class="sidebar" v-else>
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image d-flex">
                        <img
                            src="../../assets/images/avatar_admin.png"
                            class="img-circle elevation-2 align-self-center"
                            alt="User Image"
                        />
                    </div>
                    <div class="info">
                        <router-link class="nav-link" :to="{ name: 'users' }"
                            >Alyona Ozhered</router-link
                        >
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="sidebar-menu mt-2 overflow-auto">
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
                            <router-link
                                class="nav-link"
                                :to="{ name: 'posts' }"
                                @click.self="closeBurgerOnMobile()"
                                :class="{
                                    selectedNav: selected.some(
                                        (item) => item == 1
                                    ),
                                }"
                                ><i class="nav-icon fa fa-solid fa-blog"></i
                                >Posts
                                <i
                                    @click.prevent="toggleSubMenu($event, 1)"
                                    class="right fas fa-angle-left"
                                ></i
                            ></router-link>
                            <ul class="nav closed">
                                <li class="nav-item">
                                    <router-link
                                        class="nav-link sidebar-btn"
                                        :to="{ name: 'posts.create' }"
                                    >
                                        <i
                                            class="nav-icon fas fa-solid fa-user-plus"
                                        ></i>
                                        Add new
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <router-link
                                class="nav-link sidebar-btn"
                                to="/admin/categories"
                                ><i class="nav-icon fas fa-solid fa-list"></i
                                >Categories</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                class="nav-link sidebar-btn"
                                to="/admin/comments"
                                ><i
                                    class="nav-icon far fa-solid fa-envelope-open"
                                ></i
                                >Comments<span
                                    v-if="newCommentsQuantity > 0"
                                    class="badge badge-info right"
                                    >{{ newCommentsQuantity }}</span
                                ></router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                class="nav-link sidebar-btn"
                                to="/admin/tags"
                                ><i class="nav-icon fas fa-regular fa-tag"></i
                                >Tags</router-link
                            >
                        </li>
                        <li class="nav-header">
                            <strong>CHAT SECTION</strong>
                        </li>
                        <li class="nav-item">
                            <router-link
                                class="nav-link"
                                :class="{
                                    selectedNav: selected.some(
                                        (item) => item == 2
                                    ),
                                }"
                                to="/admin/users"
                                @click.self="closeBurgerOnMobile()"
                            >
                                <i class="nav-icon fas fa-regular fa-user"></i>
                                Students
                                <i
                                    @click.prevent="toggleSubMenu($event, 2)"
                                    class="right fas fa-angle-left"
                                ></i>
                            </router-link>
                            <ul class="nav closed">
                                <li class="nav-item">
                                    <router-link
                                        class="nav-link sidebar-btn"
                                        :to="{ name: 'users.create' }"
                                    >
                                        <i
                                            class="nav-icon fas fa-solid fa-user-plus"
                                        ></i>
                                        Add new
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <router-link
                                class="nav-link"
                                :to="{ name: 'chat.admin' }"
                                @click="sidebarSectionToggle = true"
                                ><i
                                    class="nav-icon fas fa-thin fa-comment-dots"
                                ></i
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
            <section v-else class="content">
                <div class="container-fluid">
                    <div class="row h-100">
                        <div class="col-12">
                            <router-view
                                :key="$route.fullPath"
                                v-slot="slotProps"
                            >
                                <transition name="route" mode="out-in">
                                    <component
                                        :is="slotProps.Component"
                                    ></component>
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

        <!-- <footer class="main-footer"></footer> -->

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
</template>

<script>
import axios from "axios";
import SidebarComponent from "../components/Admin/SidebarComponent.vue";
export default {
    components: {
        SidebarComponent,
    },
    data() {
        return {
            selected: [],
            newCommentsQuantity: null,
            windowWidth: window.innerWidth,
            counterInterval: null,
            sidebarSectionToggle: false,
        };
    },
    mounted() {
        this.$nextTick(() => {
            window.addEventListener("resize", this.onResize);
            if (this.windowWidth <= 992) {
                $(".burger").PushMenu("collapse");
            }
        });
        if (document.body.classList.contains("overflow")) {
            document.body.classList.remove("overflow");
        }
        const sidebarButtons = document.querySelectorAll(".sidebar-btn");
        sidebarButtons.forEach((btn) =>
            btn.addEventListener("click", () => {
                if (this.windowWidth <= 992) {
                    $(".burger").PushMenu("collapse");
                }
            })
        );
        this.getNewCommentsQuantity();
        // this.counterInterval = setInterval(() => {
        //     this.getNewCommentsQuantity();
        // }, 10000);
    },
    unmounted() {
        // clearInterval(this.counterInterval);
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.onResize);
    },
    methods: {
        closeBurgerOnMobile() {
            if (this.windowWidth <= 992) {
                $(".burger").PushMenu("collapse");
            }
        },
        toggleSubMenu(event, val) {
            this.selected.push(val);
            event.target.parentElement.nextSibling.classList.toggle("opened");
            event.target.parentElement.nextSibling.classList.toggle("closed");
            if (!event.target.style.transform) {
                event.target.style.transform = `rotate(-90deg)`;
            } else {
                event.target.style.transform = "";
                this.selected = this.selected.filter((item) => item != val);
            }
        },
        onResize() {
            this.windowWidth = window.innerWidth;
        },
        getNewCommentsQuantity() {
            axios.get("/api/admin/comments").then((res) => {
                let newCommentsQuantity = res.data.filter(
                    (comment) => comment.new === 1
                );
                this.newCommentsQuantity = newCommentsQuantity.length;
            });
        },
    },
};
</script>

<style scoped>
.wrapper {
    height: 100%;
}
.container-fluid {
    padding-top: 55px;
    position: relative;
    height: 100%;
}

.main-sidebar,
.main-footer,
.main-header {
    background-color: #242424;
}
.content-wrapper {
    height: 100%;
    position: relative;
    background-color: #3b3b3b;
}

.admin-content {
    /* height: 100vh; */
}

.content {
    height: 100%;
    background-color: #3b3b3b;
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

.closed {
    visibility: hidden;
    margin-top: -40px;
    transition: all 0.3s linear;
    opacity: 0;
}

.opened > li {
    width: 100%;
}

.opened {
    margin-left: 15px;
    visibility: visible;
    animation: slidein 0.3s ease-in;
}

@keyframes slidein {
    0% {
        margin-top: -40px;
        opacity: 0;
    }
    50% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

.sidebar-menu {
    height: 60vh;
}

.addUser {
    position: fixed;
    right: 30px;
    bottom: 50px;
    width: 50px;
    height: 50px;
    font-size: 1.3rem;
}

.router-link-active {
    background-color: rgba(255, 255, 255, 0.1);
    color: #fff !important;
}

.selectedNav {
    background-color: #f8f9fa !important;
    color: #000 !important;
}

.opened > .nav-item > a:hover {
    color: #fff !important;
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar-menu::-webkit-scrollbar {
    width: 0.8rem;
    background-color: #242424f6;
}
.sidebar-menu::-webkit-scrollbar:horizontal {
    height: 12px;
    margin-right: 20px;
}
.sidebar-menu::-webkit-scrollbar-thumb {
    background-color: #efe4e4;
    border: 1px solid #000000;
    border-radius: 16px;
}
.sidebar-menu::-webkit-scrollbar-thumb:hover {
    background-color: #d3c9c9;
    border: 1px solid #333333;
}
.sidebar-menu::-webkit-scrollbar-thumb:active {
    background-color: #9b8a8b;
    border: 1px solid #333333;
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
