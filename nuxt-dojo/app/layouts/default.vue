<template>
    <div>
        <header>
            <nav>
                <NuxtLink to="/"> Nuxt Dojo </NuxtLink>
                <ul>
                    <li><NuxtLink to="/">Home </NuxtLink></li>
                    <li><NuxtLink to="/about">About </NuxtLink></li>
                    <li><NuxtLink to="/products">Products </NuxtLink></li>
                </ul>
            </nav>
        </header>

        <!-- output  the page content-->
         <div>
            <slot />
         </div>
    </div>
</template>
<script setup>

</script>

<style> 
    .router-link-exact-active{
        color: #12b488;
    }
</style>