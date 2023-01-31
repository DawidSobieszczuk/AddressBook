<template>
    <div class="addresses-list">
        <el-table :data="tableData" style="width: 100%">
            <el-table-column prop="state" label="Województwo"/>
            <el-table-column prop="county" label="Powiat"/>
            <el-table-column prop="city" label="Miasto" />
            <el-table-column prop="zip" label="Kod Pocztowy" />
            <el-table-column prop="street" label="Ulica" />
            <el-table-column prop="house_number" label="Numer Domu" />
    </el-table>
    </div>
</template>

<script>
    export default {
        name: 'AddressesList',
        props: [ 'tableData' ],
    }
</script>

<style>
    .addresses-list {
        padding: 1rem;
    }
</style>