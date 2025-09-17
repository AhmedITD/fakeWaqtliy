<script setup lang="ts">
import FormMapBox from '@/components/shared/FormMapBox.vue'
import SerchList from '@/components/shared/SerchList.vue'
import ControlPanel from '@/components/shared/ControlPanel.vue'
import {handleMapClick, handleSearchButton, handel_IframeButton } from '../../composables/FormMapBox';
import mapboxgl from 'mapbox-gl';
import { onMounted } from 'vue';
import { useOrganizationsStore } from '@/stores/organizationsStore'
// const props = defineProps<{
//   id: number
// }>()
const orgsStore = useOrganizationsStore()
const FirstLocation = orgsStore.currentOrganization?.locations[0]
onMounted(() => {
    const lngLat = new mapboxgl.LngLat(FirstLocation?.latitude ?? 44.390267071425825, FirstLocation?.longitude ?? 33.28260880064724)
    handleMapClick({lngLat} as mapboxgl.MapMouseEvent)
  })
  console.log(orgsStore.currentOrganization)
</script>
<template>
  <div class="relative h-screen w-full overflow-hidden bg-neutral-50">
    <!-- MAP LAYER -->
    <div class="absolute inset-0">
      <FormMapBox
        :initial-center="[44.390267071425825, 33.28260880064724]"
        :initial-zoom="13"
        @map-click="handleMapClick"
        class="h-full w-full"
      />
    </div>
    <!-- OVERLAYS -->
    <div class="pointer-events-none absolute inset-0 z-10 flex flex-col gap-2 p-4">
      <SerchList
        @searchButton="handleSearchButton()"
      />
      <ControlPanel 
      @handelbutton="handel_IframeButton()"
      />
    </div>
  </div>
</template>
