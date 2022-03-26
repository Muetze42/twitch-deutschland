<template>
    <header>
        <div id="mobile-nav">
            <a @click="toggle()">
                <font-awesome-icon v-if="open" :icon="['fas', 'times']" class="menu-switch fa-fw" />
                <font-awesome-icon v-else :icon="['fas', 'bars']" class="fa-fw" />
                Menu
            </a>
        </div>
        <nav :class="{ 'open': open}">
            <Link href="/" :class="{ 'active': $page.url.split('?')[0] === '/' }">Videos</Link>
            <Link href="/streams" :class="{ 'active': $page.url.split('?')[0] === '/streams' }">Streams</Link>
            <Link href="/channels" :class="{ 'active': $page.url.split('?')[0] === '/channels' }">Channels</Link>
            <div class="mobile-bottom">
                <div>
                    <a href="https://github.com/Muetze42/twitch-deutschland" target="_blank">
                        <font-awesome-icon :icon="['fab', 'github']" class="fa-fw" />
                        Quellcode
                    </a>
                    <a href="https://www.netcup.de/?ref=177959" target="_blank" class="py-0">
                        <img :src="webp ? '/assets/netcup/netcup-hlogo-2019-b110h50.webp' : '/assets/netcup/netcup-hlogo-2019-b110h50.png'" alt="Norman Huth">
                    </a>
                </div>
            </div>
        </nav>
        <div id="search-top"></div>
    </header>
    <div v-if="open" class="menu-bg" @click="toggle()" />
    <slot />
    <footer>
        <div class="author">
            <div>
                Site <font-awesome-icon :icon="['fal', 'copyright']" /> 2022 by
                <a href="https://huth.it" target="_blank">
                    Norman Huth
                </a>
            </div>
        </div>
        <div class="more">
            <a href="https://huth.it/imprint" target="_blank">
                Impressum
            </a>
            <a href="https://github.com/Muetze42/twitch-deutschland" target="_blank">
                <font-awesome-icon :icon="['fab', 'github']" class="fa-fw" />
                Quellcode
            </a>
            <a href="https://www.netcup.de/?ref=177959" target="_blank" class="netcup">
                <img :src="webp ? '/assets/netcup/netcup-hlogo-2019-b110h50.webp' : '/assets/netcup/netcup-hlogo-2019-b110h50.png'" alt="Norman Huth">
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
            webp: self.createImageBitmap,
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
        if (this.pageTitle) {
            document.title = this.pageTitle
        }
    },
    mounted() {
        Inertia.on('success', (event) => {
            if (this.open) {
                document.body.classList.remove('menu-open')
                this.open = false
            }
        });
    }
}
</script>
