<template>
    <div class="login-wrapper">
        <div class="login-form">
            <h2>Formularz logowania</h2>
            <el-form :model="form" label-width="150px">
                <el-form-item label="Nazwa użytkownika">
                    <el-input name="username" v-model="form.username" placeholder="Nazwa użytkownika" />
                </el-form-item>
                <el-form-item label="Hasło">
                    <el-input name="password" v-model="form.password" type="password" placeholder="Hasło" show-password />
                </el-form-item>
                <el-form-item>
                    <el-button name="submit" type="primary" @click="onSubmit">Zaloguj</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import axios from "axios";
    import { ElNotification } from 'element-plus';

    export default defineComponent({
        name: 'LoginForm',
        data () {
            return {
                form: {
                    username: '',
                    password: ''
                },
                info: null
            }
        },
        methods: {
            onSubmit() {
                console.log(this.form);
                axios.post('api/login', {
                    name: this.form.username,
                    password: this.form.password,
                }).then(function(response) {
                    if(response.data.token) {
                        localStorage.setItem('token', response.data.token);
                        this.$parent.isLogged = true;
                        this.$parent.token = response.data.token;
                        this.$parent.userUpdateInfo();
                        this.$parent.loadAddresses();

                        console.log(response.data.user);
                        ElNotification({
                            title: 'Zalogowano',
                            type: 'success',
                        });
                    }
                    else {
                        ElNotification({
                            title: 'Bład',
                            message: 'Nieprawidłow nazwa użytkownika lub hasło',
                            type: 'error',
                        });
                    }
                }.bind(this));
            }
        }
        
    })
</script>

<style>
    .login-wrapper {
        z-index: 999;
        display: flex;
        justify-content: center;
        align-items: center;

        background-color: var(--el-bg-color);

        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }
    .login-form {
        margin: 2em;
        width: 33%;
    }
    h2 {
        text-align: center;
        padding: 1em;
    }
    
</style>