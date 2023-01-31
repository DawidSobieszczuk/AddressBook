<template>
    <div class="addresses-search-form">
        <el-input name="search" v-model="searchInput" placeholder="Adres" @input="search">
            <template #prepend>Wyszukaj adres</template>
        </el-input>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: 'AddressesSearchForm',
        props: ['token'],
        data() {
            return {
                searchInput: '',
            }
        },
        methods: {
            search() {
                axios.post('api/addresses/search',
                {
                    search: this.searchInput,
                    limit: 10
                },
                {
                    headers: {
                        Authorization: `Bearer ${this.token}`
                    }
                }).then(function (response) {
                    this.$emit('updateAddresses', response.data);
                }.bind(this));
            }
        }
    }
</script>

<style>
    .addresses-search-form {
        display: inline-flex;
        padding: 1em var(--el-menu-base-level-padding);
        flex-grow: 1;
    }
</style>