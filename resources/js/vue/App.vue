<template>
    <Topbar />
    <AddressesList :tableData="addresses"/>

    <LoginForm v-if="!isLogged" />
</template>

<script>
    import axios from "axios";

    import LoginForm from './components/LoginForm.vue';
    import Topbar from './components/TopBar.vue';
    import AddressesList from './components/AddressesList.vue';

    export default {
        components: {
            LoginForm,
            Topbar,
            AddressesList
        },
        data() {
            return {
                isLogged: false,
                token: '',
                addresses: [],
                user: {
                    id: 0,
                    name: '',
                    email: '',
                    isAdmin: false,
                }
            }
        },
        mounted() {            
            this.token = localStorage.getItem('token');
            this.isLogged = this.token !== null;

            if(this.isLogged) {
                this.userUpdateInfo();
                this.loadAddresses();
            }
        },
        methods: {
            userUpdateInfo() {
                axios.get('api/user', {
                    headers: {
                        Authorization: `Bearer ${this.token}`
                    }
                }).then(function(response) {
                    this.user.id = response.data.id;
                    this.user.name = response.data.name;
                    this.user.email = response.data.email;
                    this.user.isAdmin = response.data.is_admin;
                }.bind(this)).catch(function(error) {
                    this.token = '';
                    this.isLogged = false;
                    localStorage.removeItem('token');
                }.bind(this));
            },
            loadAddresses() {
                axios.get('api/addresses?limit=10', {
                    headers: {
                        Authorization: `Bearer ${this.token}`
                    }
                }).then(function(response) {
                    this.addresses = response.data;
                }.bind(this));
            }
        }
    }
</script>

<style>
    html, body {
        margin: 0;
    }
    body {
        background-color: var(--el-bg-color);
        
    }
</style>