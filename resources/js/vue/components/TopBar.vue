<template>
    <el-menu
        :default-active="activeIndex"
        class="el-menu-demo"
        mode="horizontal"
        :ellipsis="false"
    >
    <div class="user-info">Zalogowany jako {{ $parent.user.name }}<span v-if="$parent.user.isAdmin">(Administrator)</span></div>
    <AddressesSearchForm :token="$parent.token" @updateAddresses="$parent.addresses = $event" />
    <AddAddressForm :user_id="$parent.user.id" :token="$parent.token" />
    <el-menu-item index="1" @click="logout">Wyloguj</el-menu-item>
    </el-menu>

    
</template>

<script>
    import axios from "axios";
    import AddAddressForm from './AddAddressForm.vue';
    import AddressesSearchForm from './AddressesSearchForm.vue';

    export default {
        name: 'TopBar',
        components: {
            AddAddressForm,
            AddressesSearchForm
        },
        
        methods: {
            logout() {
                axios.get('http://localhost/api/logout', {
                    headers: {
                        Authorization: `Bearer ${this.$parent.token}`
                    }
                }).then(function () {
                    localStorage.removeItem('token');
                    this.$parent.isLogged = false;
                    this.$parent.addresses = [];
                }.bind(this));
            }
        }
    }
</script>

<style>
    .user-info {
        display: inline-flex;
        margin: auto;
        padding: 0 var(--el-menu-base-level-padding);
    }
</style>