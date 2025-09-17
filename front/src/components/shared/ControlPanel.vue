<script setup>
import CheckIcon from '../base/icons/check.vue'
import SettingsIcon from '../base/icons/settings.vue'
import controlForm from '../base/controlForm.vue'
import { useMapStateStore } from '@/stores/mapStateStore'
const emit = defineEmits(['handelbutton'])
const mapState = useMapStateStore()
</script>
<template>
    <div class="pointer-events-auto max-w-1/4 rounded-2xl border border-black/5 bg-white/90 p-4 shadow-xl ring-1 ring-black/5 backdrop-blur">
        <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-neutral-800">
            <span class="grid size-7 place-items-center rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow">
                <SettingsIcon />
            </span>
            Location Controls
        </h3>
        <controlForm @handelbutton="emit('handelbutton')"/>
        <div class="rounded-xl border border-neutral-200 bg-neutral-50 p-3">
            <div class="mb-1 flex items-center gap-2 text-xs font-semibold text-neutral-700">
                <span class="grid size-4 place-items-center rounded-full bg-gradient-to-r from-blue-500 to-purple-500 text-white">
                    <CheckIcon />
                </span>
                Selected Location
            </div>
            <code class="block truncate rounded-lg bg-white/70 p-2 text-xs text-neutral-800">{{ mapState.xy || 'None selected' }}</code>
        </div>
    </div>
</template>