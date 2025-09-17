<script setup>
import MagnifierIcon from '@/components/base/icons/magnifier.vue'
import { useNeighborhoodsStore } from '@/stores/neighborhoodsStore'
const store = useNeighborhoodsStore()
</script>
<template>
    <div class="relative">
        <input
            v-model="store.query"
            placeholder="Search by neighborhoods names"
            class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 pr-10 text-sm text-neutral-800 shadow-sm outline-none transition focus:border-blue-300 focus:bg-white"
            type="text"
            inputmode="search"
            aria-label="Search neighborhoods"
        />
        <button
            type="submit"
            class="cursor-pointer absolute right-1.5 top-1/2 -translate-y-1/2 rounded-lg p-2 text-neutral-500 hover:bg-neutral-100 hover:text-blue-700 focus:outline-none focus-visible:ring"
            aria-label="Search"
        >
            <MagnifierIcon />
        </button>
    </div>
</template>