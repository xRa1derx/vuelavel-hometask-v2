<template>
    <div class="modal-mask" @click.self="isLoginOpen()">
        <div class="modal-wrapper">
            <div class="modal-container">
                <transition name="errors">
                    <div v-if="errors.value != ''">
                        <div
                            v-for="(v, k) in errors"
                            :key="k"
                            class="text-white"
                        >
                            <p
                                v-for="error in v"
                                :key="error"
                                class="bg-danger rounded text-sm mb-1 p-2"
                            >
                                {{ error[0] }}
                            </p>
                        </div>
                    </div>
                </transition>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Login</label>
                        <input
                            class="w-100"
                            type="email"
                            name="email"
                            id="email"
                            v-model="auth.email"
                        />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            class="w-100"
                            type="password"
                            name="password"
                            id="password"
                            v-model="auth.password"
                        />
                    </div>
                    <div class="form-group">
                        <span
                            v-if="disableLoginBtn"
                            class="spinner-border spinner-border-sm"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        <button
                            type="button"
                            class="signIn-btn"
                            @click="login()"
                            :disabled="disableLoginBtn"
                        >
                            <span
                                class="oval"
                                :class="{ 'oval-active': disableLoginBtn }"
                            ></span>
                            {{ !disableLoginBtn ? "Sign in" : "Loading..." }}
                        </button>
                    </div>
                </div>
                <button class="close-btn" @click="isLoginOpen()">
                    <i class="nav-icon fas fa-window-close"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { mapActions } from "vuex";
export default {
    emits: ["close"],
    data() {
        return {
            auth: {
                email: "",
                password: "",
            },
            errors: { value: "" },
            disableLoginBtn: false,
        };
    },
    created() {
        document.body.classList.toggle("overflow");
    },
    beforeUnmount() {
        document.body.classList.toggle("overflow");
    },
    methods: {
        ...mapActions({
            getRole: "auth/role",
            isLoginOpen: "loginOpen",
        }),
        async login() {
            this.errors.value = "";
            this.disableLoginBtn = true;
            await axios.get("/sanctum/csrf-cookie").then(() => {
                axios
                    .post("/login", this.auth)
                    .then((res) => {
                        localStorage.setItem(
                            "x_xsrf_token",
                            res.config.headers["X-XSRF-TOKEN"]
                        );
                        this.getRole();
                        this.isLoginOpen();
                    })
                    .catch((e) => {
                        if (e.response.status === 422) {
                            this.errors.value = e.response.data.errors;
                        }
                    })
                    .finally(() => (this.disableLoginBtn = false));
            });
        },
    },
};
</script>

<style scoped>
.spinner-border {
    color: var(--clr-accent);
}

.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
    box-shadow: 0px 0px 10px 1px rgba(0, 0, 0, 0.596);
}

.modal-container {
    width: 300px;
    margin: 0px auto;
    padding: 20px 30px;
    transition: all 0.3s ease;
    font-family: Helvetica, Arial, sans-serif;
}

.modal-body {
    margin: 20px 0;
}

.signIn-btn {
    background-color: inherit;
    border: none;
    color: var(--clr-accent);
    float: right;
    margin-bottom: 1rem;
    font-weight: bold;
    transition: all 0.3s ease-in;
}

.signIn-btn:hover {
    color: #fff;
}

.oval {
    position: absolute;
    width: 55px;
    height: 24px;
    border: 1px solid #ff770038;
    padding: 2px 7px;
    border-radius: 100px/50px;
    transform: rotate(-45deg);
    transition: all 0.3s linear;
}

.oval:hover {
    border-radius: 30px;
    transform: scale(1.3);
}

.oval-active {
    width: 75px;
    border-radius: 30px;
    transform: scale(1.3);
}

.close-btn {
    position: absolute;
    top: 0;
    right: 0;
    background-color: inherit;
    border: none;
    color: var(--clr-accent);
}

.errors-enter-from {
    opacity: 0;
    transform: scale(1.5);
}

.errors-enter-active {
    transition: all 0.2s ease-in;
}
.errors-leave-active {
    transition: all 0.3s ease-in-out;
}

.errors-leave-to {
    opacity: 0;
    transform: scale(0);
}
</style>
