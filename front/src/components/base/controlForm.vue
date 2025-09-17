<script setup>
import { onMounted, onUnmounted } from 'vue'
import SparkIcon from './icons/spark.vue'
import PinIcon from './icons/pin.vue'
import AddPinIcon from './icons/addPin.vue'
import { useMapStateStore } from '@/stores/mapStateStore'
const mapState = useMapStateStore()
let unsubscribe = null
onMounted(() => {
  unsubscribe = mapState.$subscribe((mutation, state) => {
    // Update cursor when manuallyAddFlag changes
    if (mutation.storeId === 'mapState' && mutation.events) {
      const event = Array.isArray(mutation.events) ? mutation.events['manuallyAddFlag'] : mutation.events
      if (event) {
        mapState.updateMapCursor()
      }
    }
  }, { detached: true })
})
onUnmounted(() => {
  if (unsubscribe) {
    unsubscribe()
  }
})
const emit = defineEmits(['handelbutton'])
</script>
<template>
    <!-- Manual Add side header -->
    <div class="mb-3 flex items-center justify-between rounded-xl border border-neutral-200 bg-neutral-50 px-3 py-2">
        <span class="flex items-center gap-2 text-sm text-neutral-700">
            <SparkIcon />
            Manual Add
        </span>
        <label class="relative inline-flex h-6 w-11 cursor-pointer items-center">
        <input type="checkbox" v-model="mapState.manuallyAddFlag" class="peer sr-only"/>
            <span class="absolute left-0.5 size-5 translate-x-0 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span>
            <span class="h-full w-full rounded-full bg-neutral-200 transition peer-checked:bg-emerald-500"></span>
        </label>
    </div>
    <!-- Coordinate Input -->
    <div v-if="!mapState.manuallyAddFlag" class="mb-3">
        <label class="mb-1 flex items-center gap-2 text-xs font-medium text-neutral-700">
            <PinIcon />
            Coordinates
        </label>
        <input
            v-model="mapState.xy"
            placeholder="44.37, 33.27"
            class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm outline-none transition focus:border-purple-300"
        />
    </div>
    <!-- Add Location Button -->
    <button
        @click="$emit('handelbutton')"
        class="mb-3 w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:brightness-105 focus:outline-none focus-visible:ring"
    >
        <span class="inline-flex items-center gap-2">
            <AddPinIcon />
            Add Location
        </span>
    </button>
</template>