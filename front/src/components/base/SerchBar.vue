<script setup>
import MarkerIcon from '@/components/base/icons/marker.vue'
import SerchFiled from '@/components/base/SerchFiled.vue'
</script>
<template>
    <div class="mb-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="grid size-8 place-items-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow">
                <MarkerIcon />
            </span>
            <h2 class="text-base font-semibold text-neutral-800">Search inside Baghdad</h2>
        </div>
        <span class="flex items-center gap-1 text-xs text-neutral-500">
        <span class="size-2 animate-pulse rounded-full bg-emerald-500"></span>
        Live
        </span>
    </div>
        <SerchFiled />
</template>