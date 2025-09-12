<script setup>
import { ref, computed } from 'vue'
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Checkbox from '@/Components/Checkbox.vue'
import { Search, X, ChevronLeft } from 'lucide-vue-next'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    show: { 
        type: Boolean, 
        default: false 
    }
})

const makes = usePage().props.makes
const models = usePage().props.carModels

const emit = defineEmits(['close', 'select'])

const step = ref('brands')
const query = ref('')
const selectedMake = ref(null)
const selectedModelIds = ref([])

const mostSearched = computed(() => {
    const featured = makes.filter(m => m.featured)
    const base = featured.length ? featured : makes
    return base.slice(0, 12)
})
const filteredMakes = computed(() => {
    const q = query.value.trim().toLowerCase()
    return q ? makes.filter(m => m.name.toLowerCase().includes(q)) : makes
})
const filteredModels = computed(() => {
    if (!selectedMake.value) return []
    const list = models.filter(m => m.make_id === selectedMake.value.id)
    const q = query.value.trim().toLowerCase()
    return q ? list.filter(m => m.name.toLowerCase().includes(q)) : list
})

const openModels = (make) => {
    selectedMake.value = make
    query.value = ''
    step.value = 'models'
}

const backToMakes = () => {
    step.value = 'brands'
    query.value = ''
}

const toggleModel = (model, isChecked) => {
    const id = model.id
    if (isChecked) {
        if (!selectedModelIds.value.includes(id)) {
            selectedModelIds.value = [...selectedModelIds.value, id]
        }
        return
    }
    selectedModelIds.value = selectedModelIds.value.filter(x => x !== id)
}

const confirmSelection = () => {
    const selected = models.filter(m => selectedModelIds.value.includes(m.id))
    emit('select', { brand: selectedMake.value, carModels: selected })
    emit('close')
}

const close = () => {
  emit('close')
}
</script>

<template>
  <Modal :show="show" max-width="lg" @close="close">
    <div class="p-4 sm:p-6">
      <div class="relative py-1">
        <button v-if="step==='models'" @click="backToMakes" class="absolute left-2 top-1/2 -translate-y-1/2 p-2">
            <ChevronLeft class="h-5 w-5" />
        </button>
        <h2 class="text-2xl text-center font-bold">
            <span v-if="step==='brands'">Select make</span>
            <span v-else>{{ selectedMake?.name }}</span>
        </h2>
        <button class="absolute right-2 top-1/2 -translate-y-1/2 p-2" @click="close"><X class="h-5 w-5"/></button>
      </div>

      <div class="mt-4 relative">
        <TextInput id="make-model-picker-search" name="query" v-model="query" placeholder="Make or model" class="w-full pl-10" />
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-muted-foreground" />
      </div>

      <div v-if="step==='brands'" class="mt-6 space-y-6">
        <div>
          <h3 class="text-sm font-semibold tracking-wide text-muted-foreground">MOST SEARCHED TAGS</h3>
          <div class="mt-3 grid grid-cols-3 sm:grid-cols-6 gap-3">
            <button v-for="m in mostSearched" :key="m.id" id="make-model-picker-most-searched" name="most-searched" class="h-16 rounded-lg border flex items-center justify-center hover:bg-accent"
                @click="openModels(m)">
                <span class="text-sm font-medium">{{ m.name }}</span>
            </button>
          </div>
        </div>

        <div>
          <h3 class="text-xs font-semibold tracking-wide text-muted-foreground">ALL BRANDS</h3>
          <ul class="mt-3 divide-y max-h-[50vh] overflow-auto pr-1">
            <li v-for="m in filteredMakes" :key="m.id">
              <button class="w-full text-left py-3 px-2 hover:bg-accent rounded-md" @click="openModels(m)">
                {{ m.name }}
              </button>
            </li>
          </ul>
        </div>
      </div>

      <div v-else class="mt-4">
        <div class="mt-2 space-y-2 max-h-[60vh] overflow-auto pr-1">
          <div v-for="mm in filteredModels" :key="mm.id" class="w-full py-3 px-2 rounded-md hover:bg-accent">
            <label class="flex items-center gap-3">
              <Checkbox :checked="selectedModelIds.includes(mm.id)" @update:checked="val => toggleModel(mm, val)" />
              <span>{{ mm.name }}</span>
            </label>
          </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
          <PrimaryButton :disabled="!selectedModelIds.length" @click="confirmSelection">Select</PrimaryButton>
        </div>
      </div>
    </div>
  </Modal>
</template>


