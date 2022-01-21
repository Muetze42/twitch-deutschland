<template>
    <header>
        <nav>
            <Link href="/" :class="{ 'active': $page.url === '/' }">Videos</Link>
            <Link href="/streams" :class="{ 'active': $page.url === '/streams' }">Streams</Link>
        </nav>
        <div id="search">
            <slot name="search"></slot>
        </div>
    </header>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
    name: "Header",
    components: {
        Link,
    }
}
</script>
