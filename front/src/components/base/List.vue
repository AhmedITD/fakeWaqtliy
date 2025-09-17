<script setup>
import { useNeighborhoodsStore } from '@/stores/neighborhoodsStore'
import { useMapStateStore } from '@/stores/mapStateStore'
const store = useNeighborhoodsStore()
const mapStateStore = useMapStateStore()
</script>
<template>
    <ul class="mt-3 max-h-50 divide-y divide-neutral-100 overflow-y-auto rounded-xl border border-neutral-100 bg-white">
        <li
            v-for="neighborhood in store.filteredNeighborhoods"
            :key="neighborhood.name"
            @click="mapStateStore.flyToCoordinates(neighborhood.coordinates)"
            class="cursor-pointer px-4 py-3 hover:bg-neutral-50 text-neutral-800"
        >
            <div class="flex items-start justify-between">
                <span class="text-sm font-medium">{{ neighborhood.name }}</span>
                <span class="mt-1 size-1.5 rounded-full bg-blue-500 opacity-0 transition group-hover:opacity-100"></span>
            </div>
        </li>
        <li v-if="store.filteredNeighborhoods.length === 0 && store.query" class="px-4 py-4 text-center text-sm text-neutral-500">
            No neighborhoods found
        </li>
    </ul>
</template>