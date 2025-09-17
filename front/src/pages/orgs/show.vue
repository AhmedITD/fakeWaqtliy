<script setup lang="ts">
import FormMapBox from '@/components/shared/FormMapBox.vue'
import { useMapStateStore } from '@/stores/mapStateStore'
import { useOrganizationsStore } from '@/stores/organizationsStore'
import { onMounted } from 'vue';
// const props = defineProps<{
//   name: string
// }>()
const orgsStore = useOrganizationsStore()
const FirstLocation = orgsStore.currentOrganization?.locations[0]
const mapStateStore = useMapStateStore()
mapStateStore.manuallyAddFlag = false
onMounted(() => {
  const coords: [number, number] = [FirstLocation?.latitude ?? 0, FirstLocation?.longitude ?? 0]
  mapStateStore.flyToCoordinates(coords)
  mapStateStore.addOrUpdateMarker(coords)
})
</script>
<template>
  <div class="relative h-screen w-full overflow-hidden bg-neutral-50">
        <FormMapBox
        :initial-center="[44.37425079345715, 33.270713427515616]"
        :initial-zoom="13"
        class="h-full w-full"
      />
  </div>
</template>
