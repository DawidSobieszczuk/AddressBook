<template>
    <el-menu-item index="0" @click="dialogFormVisible=true">Dodaj nowy adres</el-menu-item>

    <el-dialog v-model="dialogFormVisible" title="Dodaj nowy adres">
        <el-form :model="form">
            <el-autocomplete name="new_address" style="display: block;" v-model="form.address" :fetch-suggestions="querySearchAsync" placeholder="Adres" @select="handleSelect"  />
        </el-form>
        <template #footer>
            <el-button name="submit" type="primary" @click="onSubmit">Dodaj</el-button>
        </template>
    </el-dialog>
</template>

<script>
    import axios from "axios";
    import { ElNotification } from 'element-plus';

    export default {
        name: 'AddressesSearchForm',
        props: ['user_id', 'token'],
        data() {
            return {
                dialogFormVisible: false,
                form: {
                    address: '',
                    state: '',
                    county: '',
                    city: '',
                    zip: '',
                    street: '',
                    houseNumber: '',
                    latitude: 0,
                    longitude: 0,
                }
            }
        },
        methods: {
            querySearchAsync(text, cb) {
                axios.get('https://address-autocomplete-pl-stage.placematic.pl/1.0/suggest/address?query=' + text  + '&approximate=true&approximationRange=10&outputSchema=basic&sortBy=populationClass').then(function(response) {
                    let data = response.data.map((item) => {
                        item.value = `${item.streetPrefix} ${item.street} ${item.houseNumber}, ${item.zip} ${item.city} | ${item.county} | ${item.state}`;
                        return item;
                    });

                    cb(data);
                }.bind(this));
            },
            handleSelect(item) {
                this.form.address = `${item.streetPrefix} ${item.street} ${item.houseNumber}, ${item.zip} ${item.city}`;

                this.form.state = item.state;
                this.form.county = item.county;
                this.form.city = item.city;
                this.form.zip = item.zip;
                this.form.street = item.street;
                this.form.houseNumber = item.houseNumber;
                this.form.latitude = item.location.latitude;
                this.form.longitude = item.location.longitude;
            },
            onSubmit() {
                axios.post('api/address', 
                {
                    user_id: this.user_id,
                    state: this.form.state,
                    county: this.form.county,
                    city: this.form.city,
                    zip: this.form.zip,
                    street: this.form.street,
                    house_number: this.form.houseNumber,
                    latitude: this.form.latitude,
                    longitude: this.form.longitude,
                },
                {
                    headers: {
                        Authorization: `Bearer ${this.token}`
                    }
                }).then(function() {
                    ElNotification({
                        title: 'Dodano nowy adress',
                        type: 'success',
                    });
                    this.$emit('addressAdded');
                }.bind(this)).catch(function() {
                    ElNotification({
                        title: 'Nie można dodać adresu',
                        type: 'success',
                    });
                });                

                this.form.address = '';
                this.dialogFormVisible = false;
            },
        }
    }
</script>