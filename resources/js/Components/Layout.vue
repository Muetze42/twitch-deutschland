<template>
    <header>
        <nav>
            <Link href="/" :class="{ 'active': $page.url.split('?')[0] === '/' }">Videos</Link>
            <Link href="/streams" :class="{ 'active': $page.url.split('?')[0] === '/streams' }">Streams</Link>
        </nav>
        <div id="search"></div>
    </header>
    <slot/>
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

export default {
    props: {
        pageTitle: String,
        device: String,
    },
    components: {
        Link,
    },
    updated() {
        document.title = this.pageTitle
    },
}
</script>
