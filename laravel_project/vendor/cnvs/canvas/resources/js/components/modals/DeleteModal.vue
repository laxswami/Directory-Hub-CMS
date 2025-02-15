<template>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body py-4">
                    <div class="d-flex justify-content-center mx-auto d-lg-none d-block">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" class="icon-important pb-2 mr-md-3"><path class="fill-warning-secondary" d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20z"/><path class="fill-warning-primary" d="M12 18a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm1-5.9c-.13 1.2-1.88 1.2-2 0l-.5-5a1 1 0 0 1 1-1.1h1a1 1 0 0 1 1 1.1l-.5 5z"/></svg>
                    </div>
                    <div class="media">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" class="icon-important d-none d-lg-block mr-md-3"><path class="fill-warning-secondary" d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20z"/><path class="fill-warning-primary" d="M12 18a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm1-5.9c-.13 1.2-1.88 1.2-2 0l-.5-5a1 1 0 0 1 1-1.1h1a1 1 0 0 1 1 1.1l-.5 5z"/></svg>
                        <div class="media-body">
                            <p class="lead mb-1 text-center text-lg-left">{{ header }}</p>
                            <p class="text-secondary text-center text-lg-left">{{ message }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row w-100">
                        <div class="col-lg order-lg-last px-0">
                            <a
                                href="#"
                                class="btn btn-danger btn-block font-weight-bold mt-0"
                                aria-label="Delete"
                                @click.prevent="confirmProceed()">
                                {{ trans.app.delete }}
                            </a>
                        </div>
                        <div class="col-lg order-lg-first px-0">
                            <button class="btn btn-link btn-block font-weight-bold text-muted text-decoration-none" data-dismiss="modal">
                                {{ trans.app.cancel }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'delete-modal',

        props: {
            header: {
                type: String,
                required: true,
            },
            message: {
                type: String,
                required: true,
            },
        },

        data() {
            return {
                trans: JSON.parse(Canvas.translations),
            }
        },

        methods: {
            confirmProceed() {
                this.$emit('delete')
            },
        },
    }
</script>
