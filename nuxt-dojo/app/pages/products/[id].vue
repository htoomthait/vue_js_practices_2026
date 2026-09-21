<template>
    <div>
        <p>Product Details for {{id}}</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit laborum natus maiores, odio sint cumque reprehenderit, voluptatibus voluptatum temporibus soluta tempore ut libero est nisi accusantium dolor voluptates id ipsa!</p>
    </div>
</template>

<script setup>
    const {id } = useRoute().params 
</script>

<style lang="scss" scoped>

</style>