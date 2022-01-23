<template>
    <header>
        <div id="mobile-nav">
            <a @click="toggle()">
                <i v-if="open" class="fas fa-times menu-switch fa-fw"></i>
                <i v-else class="fas fa-bars fa-fw"></i>
                Menu
            </a>
        </div>
        <nav :class="{ 'open': open}">
            <Link href="/" :class="{ 'active': $page.url.split('?')[0] === '/' }">Videos</Link>
            <Link href="/streams" :class="{ 'active': $page.url.split('?')[0] === '/streams' }">Streams</Link>
            <Link href="/channels" :class="{ 'active': $page.url.split('?')[0] === '/channels' }">Channels</Link>
            <div class="grow flex xs:hidden items-end mb-[2.6rem]">
                <a href="https://github.com/Muetze42/twitch-deutschland" target="_blank" class="flex-auto xs:hidden">
                    <i class="fab fa-github"></i>
                    Quellcode
                </a>
            </div>
        </nav>
        <div id="search-top"></div>
    </header>
    <div v-if="open" class="menu-bg" @click="toggle()" />
    <slot />
    <footer>
        <div class="author">
            <div>
                Site <i class="fal fa-copyright"></i> 2022 by
                <a href="https://huth.it" target="_blank">
                    Norman Huth
                </a>
            </div>
        </div>
        <div class="more">
            <a href="https://github.com/Muetze42/twitch-deutschland" target="_blank">
                <i class="fab fa-github"></i>
                Quellcode
            </a>
            <a href="https://www.netcup.de/?ref=177959" target="_blank" class="netcup">
                <img src="/assets/netcup/netcup-hlogo-2019-b110h50.png" alt="Norman Huth">
            </a>
        </div>
    </footer>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia';

export default {
    props: {
        pageTitle: String,
        device: String,
    },
    data() {
        return {
            open: false,
        }
    },
    components: {
        Link,
    },
    methods: {
        toggle() {
            this.open ? document.body.classList.remove('menu-open') : document.body.classList.add('menu-open')
            return this.open = !this.open;
        }
    },
    updated() {
        document.title = this.pageTitle
    },
    mounted() {
        Inertia.on("success", (event) => {
            if (this.open) {
                this.open = false
            }
        });
    }
}
</script>
