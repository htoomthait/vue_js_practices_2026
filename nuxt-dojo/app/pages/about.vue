<template>
    <div> 
        <h2>About</h2>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Odio debitis, ipsa, quia soluta, sed quo praesentium earum cumque nostrum similique laborum illum sint! Possimus, voluptas. Laboriosam quae neque doloribus esse.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odit odio neque pariatur amet? Sit, tempore perspiciatis aperiam tempora quisquam ut veniam est minima ex aliquid corrupti quis facilis ad veritatis?</p>
        <div>{{data}}</div>
        <p>
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Assumenda, reprehenderit maxime cumque at quaerat velit eligendi corporis porro illo dolor, aspernatur minima voluptatum ut soluta. Voluptatibus nesciunt at dignissimos cum!
        </p>
    </div>
</template>

<script setup>
    // const {data} = await useFetch('/api/ninjas', {
    //     method: 'post'
    // })

    const {data} = await useFetch('/api/currency/GBP')
</script>

<style lang="scss" scoped>
    h2{
        margin-bottom: 20px;
        font-size:36px;
    }
    p{
        margin: 20px 0;
    }
</style>