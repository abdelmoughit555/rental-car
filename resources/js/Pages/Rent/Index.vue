<script setup>
import { reactive, watch, ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/shadcn/ui/card/Card.vue'
import CardHeader from '@/Components/shadcn/ui/card/CardHeader.vue'
import CardTitle from '@/Components/shadcn/ui/card/CardTitle.vue'
import CardContent from '@/Components/shadcn/ui/card/CardContent.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Skeleton from '@/Components/Skeleton.vue'
import SelectInput from '@/Components/SelectInput.vue'
import DatePicker from '@/Components/DatePicker.vue'
import BlankSlate from '@/Components/BlankSlate.vue'
import { Car, Gauge, Calendar, Settings, Droplets, MapPin, Heart, Image as ImageIcon, ChevronRight } from 'lucide-vue-next'
import MakeModelPicker from '@/Components/MakeModelPicker.vue'

const props = defineProps({
    cars: Object,
    filters: Object,
})


const form = reactive({
    q: props.filters?.q ?? '',
    brand_id: props.filters?.brand_id ?? '',
    car_model_ids: props.filters?.car_model_ids ?? [],
    fuel_type_id: props.filters?.fuel_type_id ?? '',
    gearbox_id: props.filters?.gearbox_id ?? '',
    seats: props.filters?.seats ?? '',
    doors: props.filters?.doors ?? '',
    price_min: props.filters?.price_min ?? '',
    price_max: props.filters?.price_max ?? '',
    available_from: props.filters?.available_from ?? '',
    available_to: props.filters?.available_to ?? '',
    sort: props.filters?.sort ?? 'latest',
    per_page: props.filters?.per_page ?? 12,
})

const showMakeModel = ref(false)
const selectedMakeLabel = computed(() => {
    const id = form.brand_id
    if (!id) return null
    
    return usePage().props.makes.find(brand => brand.id == id)?.name || null
})

const selectedModelLabels = computed(() => {
    const ids = Array.isArray(form.car_model_ids) ? form.car_model_ids : []
    if (!ids.length) return []
    const models = usePage().props.carModels
    return ids.map(id => models.find(m => m.id == id)?.name).filter(Boolean)
})

const handleSelectMakeModel = (payload) => {
  const brand = payload.brand
  const models = payload.carModels

  form.brand_id = brand?.id || ''
  // Replace fully to avoid duplicates from stale array
  form.car_model_ids = Array.isArray(models) ? models.map(m => m.id) : []
}

const applyFilters = () => {
  router.get(route('rent'), { 
    ...Object.fromEntries(Object.entries(form).filter(([_, value]) => value !== '')),
  }, { 
    preserveform: true,
    preserveScroll: true 
  })
}

const clearFilters = () => {
  Object.keys(form).forEach(k => form[k] = '')
  Object.fromEntries(Object.entries(form).filter(([_, value]) => value !== ''))
  form.sort = 'latest'
  form.per_page = 12
}

let debounceTimer = null
watch(form, () => {
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    applyFilters()
  }, 400)
}, { deep: true })
</script>

<template>
  <AppLayout title="Rent a car">
    <div class="container mx-auto px-4 py-6">

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <aside class="lg:col-span-1">
        <Card>
          <CardHeader>
            <CardTitle>Filters</CardTitle>
          </CardHeader>
          <CardContent class="space-y-4">
            <div>
              <InputLabel value="Make and model" />
              <button class="w-full flex items-center justify-between border rounded-md px-3 py-2 text-left hover:bg-accent"
                @click="showMakeModel = true">
                <span class="text-sm">{{ selectedMakeLabel + ' ' + selectedModelLabels.join(', ') || 'Add a car' }}</span>
                <span class="text-primary">
                    <ChevronRight class="h-4 w-4" />
                </span>
              </button>
            </div>
            <div>
              <InputLabel value="Search" />
              <TextInput id="rent-search" name="q" v-model="form.q" placeholder="e.g. Golf, SUV..." />
            </div>
            <div class="space-y-3">
              <SelectInput
                id="rent-fuel"
                name="fuel_type_id"
                v-model="form.fuel_type_id"
                :items="[{ id: '', name: 'Any fuel', disabled: true }, ...$page.props.fuelTypes]"
              >
                <template #label>Fuel</template>
              </SelectInput>
              <SelectInput
                id="rent-gearbox"
                name="gearbox_id"
                v-model="form.gearbox_id"
                :items="[{ id: '', name: 'Any gearbox', disabled: true }, ...$page.props.gearboxes]"
              >
                <template #label>Gearbox</template>
              </SelectInput>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <InputLabel value="Seats" />
                <TextInput id="rent-seats" name="seats" v-model="form.seats" type="number" min="1" />
              </div>
              <div>
                <InputLabel value="Doors" />
                <TextInput id="rent-doors" name="doors" v-model="form.doors" type="number" min="1" />
              </div>
            </div>
            <div class="space-y-1">
                <InputLabel value="Price" />
                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <TextInput id="rent-price-min" name="price_min" v-model="form.price_min" type="number" min="0" placeholder="Price From" />
                  </div>
                  <div>
                    <TextInput id="rent-price-max" name="price_max" v-model="form.price_max" type="number" min="0" placeholder="Price To" />
                  </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <InputLabel value="Mileage From" />
                <SelectInput
                  id="rent-mileage-min"
                  name="mileage_min"
                  v-model="form.mileage_min"
                  :items="[{
                    id: '',
                    name: 'From', 
                    disabled: true 
                    }, 
                    ...Array.from({length: 41}, (_,i) => i * 5000).map(v => ({
                       id: v, name: Intl.NumberFormat('en-US').format(v) 
                    }))]"
                />
              </div>
              <div>
                <InputLabel value="Mileage To" />
                <SelectInput
                  id="rent-mileage-max"
                  name="mileage_max"
                  v-model="form.mileage_max"
                  :items="[{ id: '', name: 'To', disabled: true }, ...Array.from({length: 41}, (_,i) => i*5000).map(v => ({ id: v, name: Intl.NumberFormat('en-US').format(v) }))]"
                />
              </div>
            </div>
            <div class="space-y-1">
              <div>
                <InputLabel value="Availability" />
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <DatePicker placeholder="From" name="available_from" v-model="form.available_from" id="rent-from">
                  </DatePicker>
                </div>
                <div>
                  <DatePicker placeholder="To" name="available_to" v-model="form.available_to" id="rent-to" :minDate="form.available_from || undefined">
                  </DatePicker>
                </div>
              </div>
            </div>

            <div class="flex gap-2">
              <button class="text-sm underline" @click="clearFilters">Clear</button>
            </div>
          </CardContent>
        </Card>
      </aside>

      <main class="lg:col-span-3">
        <div class="flex items-center justify-between mb-4">
          <div class="text-sm text-muted-foreground">{{ cars?.meta?.total ?? 0 }} results</div>
          <div class="flex items-center gap-2">
            <div class="w-48">
              <SelectInput
                id="rent-sort"
                name="sort"
                v-model="form.sort"
                :items="[
                  { id: 'latest', name: 'Latest' },
                  { id: 'price_asc', name: 'Price: Low → High' },
                  { id: 'price_desc', name: 'Price: High → Low' },
                ]"
              >
                <template #label>Sort by</template>
              </SelectInput>
            </div>
            <div class="w-28">
              <SelectInput
                id="rent-per-page"
                name="per_page"
                v-model="form.per_page"
                :items="[
                  { id: 12, name: '12' },
                  { id: 24, name: '24' },
                  { id: 48, name: '48' },
                ]"
              >
                <template #label>Per page</template>
              </SelectInput>
            </div>
          </div>
        </div>

        <div v-if="!cars" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <Skeleton v-for="i in 6" :key="i" class="h-48" />
        </div>

        <div v-else-if="cars.data?.length" class="space-y-4">
          <Card v-for="car in cars.data" :key="car.id" class="overflow-hidden">
            <CardContent class="p-0">
              <div class="flex flex-col sm:flex-row gap-4 p-4 items-stretch sm:items-start">
                <div class="relative w-full h-48 sm:w-72 sm:h-48 lg:w-80 lg:h-56 rounded-md overflow-hidden bg-muted shrink-0 mb-2 sm:mb-0">
                  <img
                    v-if="car.front_view_image?.url"
                    :src="car.front_view_image.url"
                    :alt="`${car.title} - front view`"
                    class="w-full h-full object-cover"
                  />
                  <button class="absolute top-2 right-2 h-9 w-9 rounded-full bg-white/90 hover:bg-white shadow flex items-center justify-center">
                    <Heart class="h-5 w-5 text-primary" />
                  </button>
                  <div class="absolute bottom-2 left-2 bg-black/60 text-white text-xs px-2 py-1 rounded-md flex items-center gap-1">
                    <ImageIcon class="h-4 w-4" />
                    <span>{{ car.images_count ?? 1 }} images</span>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-xl font-bold leading-snug truncate">{{ car.title }}</div>
                  <div class="text-sm text-muted-foreground mb-2">{{ car.brand?.name }} • {{ car.car_model?.name }}</div>
                  <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-muted-foreground">
                    <span class="inline-flex items-center gap-2"><Gauge class="h-4 w-4" />{{ (car.mileage_km ?? 0).toLocaleString() }} km</span>
                    <span class="inline-flex items-center gap-2"><Calendar class="h-4 w-4" />{{ car.year }}</span>
                    <span class="inline-flex items-center gap-2"><Settings class="h-4 w-4" />{{ car.power_hp }} hp</span>
                    <span class="inline-flex items-center gap-2"><Settings class="h-4 w-4" />{{ car.gearbox?.name }}</span>
                    <span class="inline-flex items-center gap-2"><Droplets class="h-4 w-4" />{{ car.fuel_type?.name }}</span>
                  </div>

                  <div v-if="car.features?.length" class="mt-3 flex flex-wrap gap-2">
                    <span v-for="(feat, idx) in car.features.slice(0,5)" :key="feat.id" class="text-xs bg-primary/10 text-primary px-2 py-1 rounded-full">
                      {{ feat.name }}
                    </span>
                    <span v-if="car.features.length > 5" class="text-xs text-muted-foreground">+ {{ car.features.length - 5 }} more</span>
                  </div>

                  <div class="mt-4 flex flex-col sm:flex-row sm:items-end justify-between gap-2">
                    <div class="text-sm text-muted-foreground inline-flex items-center gap-2">
                      <MapPin class="h-4 w-4" />
                      <span>Casablanca, delivery available</span>
                    </div>
                    <div class="border-t border-muted my-2 sm:hidden"></div>
                    <div class="text-right">
                      <div class="text-2xl font-extrabold">{{ new Intl.NumberFormat('en-US').format(Number(car.price_per_day)) }} MAD/day</div>
                    </div>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <div v-else class="py-16">
          <BlankSlate>
            <template #icon>
              <Car class="mx-auto h-10 w-10 text-muted-foreground" />
            </template>
            <template #title>No cars match your filters</template>
            <template #description>Try adjusting your dates or filters to see more cars.</template>
            <template #actions>
              <PrimaryButton @click="clearFilters">Reset filters</PrimaryButton>
            </template>
          </BlankSlate>
        </div>

        <div class="mt-6 flex justify-center" v-if="cars?.links">
          <nav class="inline-flex items-center gap-2">
          </nav>
        </div>
      </main>
    </div>
    </div>

    <MakeModelPicker
      :show="showMakeModel"
      @close="showMakeModel = false"
      @select="handleSelectMakeModel"
    />
  </AppLayout>
</template>


