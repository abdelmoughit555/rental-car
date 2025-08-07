<script setup>
import { ref, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import SelectInput from '@/Components/SelectInput.vue';
import Panel from '@/Components/Panel.vue';
import ValidationInfo from '@/Components/ValidationInfo.vue';

const props = defineProps({
    forModel: {
        type: String,
        default: 'release'
    },
    genre_priority: {
        type: Object,
        default: () => ({})
    },
    errors: {
        type: Object,
    },
    validationErrors: {
        type: Object
    },
    update: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['selectedGenres'])

const genres = usePage().props.genres;

const genre = ref(null)
const secondary = ref(null)
const niche = ref(null)

const defaultSelection = ref({
    main: null,
    secondary: null,
    niche: null,
})

const subGenres = computed(() => {
    if(!genre.value) return
    let selectedGenre = genres.filter(element => {
        return element.id == genre.value
    })

    if (defaultSelection.main == genre.value) {
        if (defaultSelection.secondary) {
            secondary.value = defaultSelection.secondary
        }
    } else {
        defaultSelection.secondary = null
    }
    if (selectedGenre[0] && selectedGenre[0].sub_genres.length != 0) {
        return selectedGenre[0].sub_genres
    } else {
        secondary.value = null
    }
})

const nicheGenres = computed(() => {
    if(!secondary.value) return

    let selectedGenre = subGenres.value.filter(element => {
        return element.id == secondary.value
    })

    if (defaultSelection.main == selectedGenre) {
        if (defaultSelection.secondary) {
            niche.value = defaultSelection.secondary
        }
    } else {
        defaultSelection.secondary = null
    }

    if (selectedGenre[0] && selectedGenre[0].sub_genres.length != 0) {
        return selectedGenre[0].sub_genres.data
    } else {
        niche.value = null
    }
})

watch(() => genre.value, () => {
    genreChanged()
})

watch(() => secondary.value, () => {
    genreChanged()
})

watch(() => niche.value, () => {
    genreChanged()
})

watch(() => props.priority_genres, () => {
    if(!props.genre_priority) return

    if (props.genre_priority.main) {
        let main = props.genre_priority.main.id
        defaultSelection.main = main
        genre.value = main
    }

    if (props.genre_priority.sub) {
        let secondaryGenre = props.genre_priority.sub.id
        defaultSelection.secondary = secondaryGenre
        secondary.value = secondaryGenre
    }

    if (props.genre_priority.niche) {
        let nicheGenre = props.genre_priority.niche.id
        defaultSelection.niche = nicheGenre
        niche.value = nicheGenre
    }
}, { deep: true, immediate: true })

const genreChanged = () => {
    emit('selectedGenres', {
        genre: genre.value,
        secondary: secondary.value,
        niche: niche.value
    })
}

const watchedFields = ['genres']
const sectionErrors = computed(() => {
  if (!props.validationErrors) return []
  return watchedFields.flatMap(field => props.validationErrors[field] || [])
})

const hasSectionError = computed(() => sectionErrors.value.length > 0)

</script>
<template>
    <Panel>
        <template #title>
            <span class="capitalize">{{ forModel }}</span> Genres
        </template>
        <template #description>
            Select the genres that best describe this {{ forModel }}. This will help us categorize your {{ forModel }} and make it easier for users to find it.
        </template>
        <template #aside>
            <ValidationInfo v-if="hasSectionError" :errors="sectionErrors" />
        </template>
        <template #content>
            <div class="space-y-4">
                <div>
                    <SelectInput class="w-full lg:w-auto" name="genre" id="genre" v-model="genre"
                        :items="[{
                            id: null,
                            name: 'Select a Genre for your Music',
                            disabled: true
                        }, ...genres]" placeholder="Genres" :errors="errors.genres" :hasError="errors.genres != null"
                        :disabled="!update"
                    >
                        <template #label>
                            <p>Genre(s)</p>
                        </template>
                    </SelectInput>
                </div>
                <div v-if="subGenres && subGenres.length != 0">
                    <SelectInput class="w-full lg:w-auto" name="sub_genre" id="sub_genre" v-model="secondary"
                        :items="[{
                            id: null,
                            name: `Select a Sub Genre for your ${ forModel }`,
                            disabled: true
                        }, ...subGenres]" placeholder="Sub Genre"
                        :disabled="!update"
                    >
                        <template #label>
                            <p>Sub Genre(s)</p>
                        </template>
                    </SelectInput>
                </div>
                <div v-if="nicheGenres && nicheGenres.length != 0">
                    <SelectInput class="w-full lg:w-auto" name="niche_genre" id="niche_genre" v-model="niche"
                        :items="[{
                            id: null,
                            name: `Select a Niche Genre for your ${ forModel }`,
                            disabled: true
                        }, ...nicheGenres]" placeholder="Niche Genre"
                        :disabled="!update"
                    >
                        <template #label>
                            <p>Niche Genre(s)</p>
                        </template>
                    </SelectInput>
                </div>
            </div>
        </template>
    </Panel>
</template>
