<template>
   <div class="card text-center flex flex-col justify-end">
         
         <img :src="`${product.image}`" alt="product thumb" class="thumb" />
         <p class="font-bold text-grey-500 m-4 truncate">{{product.title}}</p>
         <NuxtLink :to="`/products/${product.id}`"> <p class="btn my-4">view detail</p> </NuxtLink>

    </div>
</template>

<script setup>
    const { product } = defineProps(['product'])

</script>

<style lang="scss" scoped>

</style>