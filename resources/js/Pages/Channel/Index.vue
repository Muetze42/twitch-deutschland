<template>
    <h1>Channels</h1>
    <main class="scrollbar scrollbar-thumb-fuchsia-900 scrollbar-track-slate-600 hover:scrollbar-thumb-fuchsia-800">
        <div class="channels">
            <div v-for="channel in channels" class="channel" @click="show(channel.id, channel.latest, channel.name, channel.youtube_id)">
                {{ channel.name }}
                <div class="additional">
                    Letzte Video: {{ channel.published }}
                </div>
                <div class="additional">
                    {{ channel.count }} {{ channel.count === '1' ? 'Video' : 'Videos' }}
                </div>
                <div class="additional">
                    {{ channel.broadcasters }} Streams
                </div>
            </div>
        </div>
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
                                <a :href="'https://www.youtube.com/channel/'+channelId" rel="noopener" target="_blank">
                                    {{ name }} auf <i class="fab fa-youtube fa-fw"></i>YouTube
                                </a>
                            </span>
                        </div>
                        <div class="close">
                            <i class="fal fa-window-close" @click="close()"></i>
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
                                    {{ video.published }}
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
export default {
    props: {
        channels: Object
    },
    data() {
        return {
            isOpen: false,
            name: '',
            youTubeId: '',
            channelId: '',
            videos: {},
            nextLink: null,
            loadMoreProcess: false,
        }
    },
    methods: {
        show(channel, youTubeId, name, channelId) {
            document.body.classList.add('modal-open')
            this.name = name
            this.youTubeId = youTubeId
            this.channelId = channelId
            this.videos = {}
            axios.post('/api/channel/'+channel, {
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
                aerror.response && error.response.data.message ? alert('Error '+error.response.status+': '+error.response.data.message) : alert(error.response.status)
            })
        },
        switchVideo(youTubeId) {
            this.youTubeId = youTubeId
        },
        close() {
            document.body.classList.remove('modal-open')
            this.isOpen = false
        },
    }
}
</script>
