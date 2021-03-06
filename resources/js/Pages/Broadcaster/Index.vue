<template>
    <teleport to="#search-top">
        <div id="search">
            <input v-model="search" type="search" placeholder="Suche...">
        </div>
    </teleport>
    <h1>Streams</h1>
    <main :class="{ 'e404': !broadcasters.data.length }" class="scrollbar scrollbar-thumb-fuchsia-900 scrollbar-track-slate-600 hover:scrollbar-thumb-fuchsia-800">
        <div class="content">
            <div v-for="broadcaster in broadcasters.data" class="card" v-if="broadcasters.data.length" @click="show(broadcaster.id, broadcaster.name, broadcaster.first)">
                <img :src="broadcaster.logo" :alt="broadcaster.name" :class="$page.props.device" width="300" height="300">
                {{ broadcaster.name }}
                <div class="additional">
                    {{ broadcaster.count }} Videos
                </div>
            </div>
            <NotFound v-else />
        </div>
        <Pagination :links="broadcasters.links" />
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
                                <a :href="'https://www.twitch.tv/'+name.toLowerCase()" target="_blank" rel="noopener">
                                    <font-awesome-icon :icon="['fab', 'twitch']" class="fa-fw" />
                                    {{ name }}
                                </a>
                            </span>
                        </div>
                        <div class="close">
                            <font-awesome-icon :icon="['fal', 'window-close']" class="fa-fw" @click="close()" />
                        </div>
                    </div>
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe :src="'https://www.youtube.com/embed/'+youTubeId" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div v-if="Object.keys(videos).length" class="body scrollbar-thin scrollbar-thumb-fuchsia-900 scrollbar-track-slate-600 hover:scrollbar-thumb-fuchsia-800 scrollbar-space">
                        <div v-for="video in videos" class="video">
                            <a class="img-link" @click="switchVideo(video.id)" v-if="video.id !== youTubeId">
                                <img :src="video.image" :alt="video.title">
                            </a>
                            <span class="img-link" v-else>
                                <img :src="video.image" :alt="video.title">
                            </span>
                            <div class="description">
                                <a @click="switchVideo(video.id)" class="desc-link" v-if="video.id !== youTubeId">
                                    {{ video.title }}
                                </a>
                                <span v-else class="desc-link">
                                    {{ video.title }}
                                </span>
                                <div class="additional">
                                    {{ video.channel }}, {{ video.published }}
                                </div>
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
        broadcasters: Object,
        filters: Object,
        device: String,
        search: String,
    },
    data() {
        return {
            isOpen: false,
            name: '',
            youTubeId: '',
            videos: {},
            nextLink: null,
            loadMoreProcess: false,
        }
    },
    components: {
        Pagination,
        NotFound,
    },
    methods: {
        show(broadcaster, name, youTubeId) {
            document.body.classList.add('modal-open')
            this.name = name
            this.youTubeId = youTubeId
            this.videos = {}
            axios.post('/api/broadcaster/'+broadcaster, {
                hash: this.$page.props.agent,
            }).then(response => {
                this.videos = response.data.data
                this.nextLink = response.data.next_page_url
            }).catch(error => {
                error.response && error.response.data.message ? alert('Error '+error.response.status+': '+error.response.data.message) : alert(error.response.status)
            })
            this.isOpen = true
        },
        loadMore() {
            this.loadMoreProcess = true
            axios.post(this.nextLink, {
                hash: this.$page.props.agent,
            }).then(response => {
                this.videos = this.videos.concat(response.data.data)
                this.nextLink = response.data.next_page_url
                this.loadMoreProcess = false
            }).catch(error => {
                error.response && error.response.data.message ? alert('Error '+error.response.status+': '+error.response.data.message) : alert(error.response.status)
            })
        },
        switchVideo(youTubeId) {
            this.youTubeId = youTubeId
        },
        close() {
            document.body.classList.remove('modal-open')
            this.isOpen = false
        },
    },
    watch: {
        search: debounce(function () {
            Inertia.get('/streams', {
                search: this.search,
            }, { preserveState: true, replace: true });
        }, 300)
    }
}
</script>
