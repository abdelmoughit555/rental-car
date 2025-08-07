<template>
    <div class="flex items-center justify-between border-t border px-4 py-3 sm:px-6 bg-accent rounded-b-lg"

        v-if="meta"
    >
        <div class="flex flex-1 justify-between sm:hidden">
            <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
            <a href="#" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-accent-foreground">
                    Page
                    <span class="font-medium">{{ meta.current_page }}</span>
                    of
                    <span class="font-medium">{{ meta.total_pages ?? meta.last_page }}</span>
                    with
                    <span class="font-medium">{{ meta.total }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex space-x-2" aria-label="Pagination" v-if="useResourceLinks">
                     <Link v-if="links && links.prev" :href="links.prev + currentHash" preserve-scroll>
                        <Button :disabled="!links && !links.prev" @clicked="prev" type="button">Prev</Button>
                    </Link>

                    <Link v-if="links && links.next" :href="links.next + currentHash" preserve-scroll>
                        <Button @clicked="next" type="button">Next</Button>
                    </Link>
                </nav>

                <nav class="isolate inline-flex space-x-2" aria-label="Pagination" v-else>
                    <Button :disabled="!canGoPrev" @click="prev" type="button">Prev</Button>
                    <Button :disabled="!canGoNext" @click="next" type="button">Next</Button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
    import { Link } from '@inertiajs/vue3'
    import Button from './PrimaryButton.vue';

    export default {
        components: {
            Link,
            Button
        },

        emits: ['next', 'prev'],
        name: 'TablePagination',

        props: {
            meta: {
                type: Object
            },

            links: {
                type: Object,
                required: false
            },

            useInertiaLinks: {
                type: Boolean,
                default: true
            },

            useResourceLinks: {
                type: Boolean,
                default: false
            },

            whiteBackground: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            canGoNext: function() {
                if(this.meta == null) return;
                return this.meta.current_page != (this.meta.total_pages ?? this.meta.last_page)
            },

            canGoPrev: function() {
                if(this.meta == null) return;
                return this.meta.current_page != 1
            },

            currentHash: function() {
                return window.location.hash || '';
            }
        },

        methods: {
            next() {
                if(this.canGoNext) {
                    this.$emit("next")
                }
            },

            prev() {
                if(this.canGoPrev) {
                    this.$emit('prev')
                }
            },
        }
    }
</script>
