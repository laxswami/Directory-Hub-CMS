<template>
    <div>
        <page-header>
            <template slot="action">
                <router-link :to="{ name: 'topics-create' }" class="btn btn-sm btn-outline-success font-weight-bold my-auto">
                    {{ trans.app.new_topic }}
                </router-link>
            </template>
        </page-header>

        <main class="py-4">
            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">
                <div class="my-3">
                    <h1>{{ trans.app.topics }}</h1>
                    <p class="text-secondary">{{ trans.app.topics_are_great_for }}</p>
                </div>

                <div class="mt-5 card shadow border-0">
                    <div class="card-body p-0">
                        <div v-for="(topic, index) in topics" :key="index">
                            <router-link :to="{name: 'topics-edit', params: { id: topic.id }}" class="text-decoration-none">
                                <div
                                    v-hover="{class: `row-hover`}"
                                    class="p-3"
                                    :class="{'border-top': index !== 0, 'rounded-top': index === 0, 'rounded-bottom': index === topics.length - 1}">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-auto pl-2">
                                            <p class="mb-0 py-1">
                                            <span class="font-weight-bold text-lg lead">
                                                {{ topic.name }}
                                            </span>
                                            </p>
                                        </div>
                                        <div class="ml-auto d-none d-md-inline-block">
                                            <span class="text-muted mr-3">{{ topic.posts_count }} {{ trans.app.posts }}</span>
                                            <span class="mr-3">{{ trans.app.created }} {{ moment(topic.created_at).locale(Canvas.locale).fromNow() }}</span>
                                        </div>

                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" viewBox="0 0 24 24" class="icon-cheveron-right-circle">
                                            <circle cx="12" cy="12" r="10" style="fill:none"/>
                                            <path class="primary" d="M10.3 8.7a1 1 0 0 1 1.4-1.4l4 4a1 1 0 0 1 0 1.4l-4 4a1 1 0 0 1-1.4-1.4l3.29-3.3-3.3-3.3z"/>
                                        </svg>
                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <infinite-loading @infinite="fetchData" spinner="spiral">
                            <span slot="no-more"></span>
                            <div slot="no-results" class="text-left">
                                <div class="my-5">
                                    <p class="lead text-center text-muted mt-5">
                                        {{ trans.app.you_have_no_topics }}
                                    </p>
                                    <p class="lead text-center text-muted mt-1">
                                        {{ trans.app.write_on_the_go }}
                                    </p>
                                </div>
                            </div>
                        </infinite-loading>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    import NProgress from 'nprogress'
    import isEmpty from 'lodash/isEmpty'
    import Hover from "../../directives/Hover";
    import InfiniteLoading from 'vue-infinite-loading'
    import PageHeader from '../../components/PageHeader'

    export default {
        name: 'topics-index',

        components: {
            InfiniteLoading,
            PageHeader,
        },

        directives: {
            Hover
        },

        data() {
            return {
                page: 1,
                topics: [],
                trans: JSON.parse(Canvas.translations),
            }
        },

        methods: {
            fetchData($state) {
                this.request()
                    .get('/api/topics', {
                        params: {
                            page: this.page
                        },
                    })
                    .then(response => {
                        if (!isEmpty(response.data) && !isEmpty(response.data.data)) {
                            this.page += 1;
                            this.topics.push(...response.data.data)

                            $state.loaded();
                        } else {
                            $state.complete();
                        }

                        NProgress.done()
                    })
                    .catch(error => {
                        // Add any error debugging...

                        NProgress.done()
                    })
            },
        },
    }
</script>
