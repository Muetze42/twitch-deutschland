<template>
    <teleport to="#search-top">
        <div id="search">
            <input v-model="search" type="search" placeholder="Suche...">
        </div>
    </teleport>
    <h1>Videos</h1>
    <main :class="{ 'e404': !videos.data.length }" class="scrollbar scrollbar-thumb-fuchsia-900 scrollbar-track-slate-600 hover:scrollbar-thumb-fuchsia-800">
        <div class="content">
            <div v-for="video in videos.data" class="card" v-if="videos.data.length" @click="show(video.id, video.title, video.youtube_id)">
                <div class="yt-thumb">
                    <img :src="video.image" :alt="video.title" :class="$page.props.device" width="300" height="170">
                </div>
                {{ video.title }}
                <div class="additional">
                    {{ video.channel }}, {{ video.published }}
                </div>
            </div>
            <NotFound v-else />
        </div>
        <Pagination :links="videos.links" />
    </main>
    <div class="modal-container" v-if="isOpen">
        <div class="modal">
            <div class="modal-bg">
                <div @click="close()" class="cursor-zoom-out"></div>
            </div>
            <span class="align"></span>
            <div class="modal-body" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg">
                    <div class="head">
                        <div class="title">
                            <span>
                                {{ title }}
                            </span>
                        </div>
                        <div class="close">
                            <font-awesome-icon :icon="['fal', 'window-close']" class="fa-fw" @click="close()" />
                        </div>
                    </div>
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe :src="'https://www.youtube.com/embed/'+youtube_id" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div v-if="Object.keys(broadcasters).length" class="body justify-center scrollbar-thin scrollbar-thumb-fuchsia-900 scrollbar-track-slate-600 hover:scrollbar-thumb-fuchsia-800 scrollbar-space">
                        <div class="broadcaster" v-for="broadcaster in broadcasters">
                            <div>
                                <img :src="broadcaster.logo" :alt="broadcaster.name" width="50" height="50">
                                <a :href="'https://www.twitch.tv/'+broadcaster.name.toLowerCase()" target="_blank" rel="noopener">
                                    <font-awesome-icon :icon="['fab', 'twitch']" class="fa-fw" />
                                    {{ broadcaster.name }}
                                </a>
                            </div>
                        </div>
                        <div v-if="nextLink" class="next">
                            <div>
                                <span class="btn disabled" v-if="loadMoreProcess">
                                    Mehr anzeigen
                                </span>
                                <button v-else type="button" class="btn" @click="loadMore()">
                                    Mehr anzeigen
                                </button>
                                <span v-if="loadMoreProcess" class="more-process">
                                    <span></span>
                                    <span></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="spinner-container" role="status">
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import Pagination from './../../Components/Pagination';
import NotFound from './../../Components/NotFound';
import debounce from "lodash/debounce";

export default {
    props: {
        videos: Object,
        filters: Object,
        device: String,
        search: String,
        id: Number,
    },
    data() {
        return {
            isOpen: false,
            title: '',
            youtube_id: '',
            broadcasters: {},
            nextLink: null,
            loadMoreProcess: false,
            seed: null
        }
    },
    components: {
        Pagination,
        NotFound,
    },
    methods: {
        show(video, title, youtube_id) {
            document.body.classList.add('modal-open')
            this.broadcasters = {}
            this.youtube_id = youtube_id
            this.title = title
            this.isOpen = true
            axios.post('/api/video/'+video, {
                hash: this.$page.props.agent,
            }).then(response => {
                this.broadcasters = response.data.broadcasters.data
                this.nextLink = response.data.broadcasters.next_page_url
                this.seed = response.data.seed
            }).catch(error => {
                error.response && error.response.data.message ? alert('Error '+error.response.status+': '+error.response.data.message) : alert(error.response.status)
            })
        },
        loadMore() {
            this.loadMoreProcess = true
            axios.post(this.nextLink, {
                hash: this.$page.props.agent,
                seed: this.seed
            }).then(response => {
                this.broadcasters = this.broadcasters.concat(response.data.broadcasters.data)
                this.nextLink = response.data.broadcasters.next_page_url
                this.loadMoreProcess = false
            }).catch(error => {
                error.response && error.response.data.message ? alert('Error '+error.response.status+': '+error.response.data.message) : alert(error.response.status)
            })
        },
        close() {
            document.body.classList.remove('modal-open')
            this.isOpen = false
        }
    },
    watch: {
        search: debounce(function () {
            Inertia.get('/', {
                search: this.search,
            }, {
                preserveState: true,
                replace: true,
                preserveScroll: true
            });
        }, 300)
    }
}
</script>
