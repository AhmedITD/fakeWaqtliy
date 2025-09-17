<script setup lang="ts">
import FormMapBox from '@/components/shared/FormMapBox.vue'
import SerchList from '@/components/shared/SerchList.vue'
import ControlPanel from '@/components/shared/ControlPanel.vue'
import {handleMapClick, handleSearchButton, handel_IframeButton } from '../../composables/FormMapBox';
</script>
<template>
  <div class="relative h-screen w-full overflow-hidden bg-neutral-50">
    <!-- MAP LAYER -->
    <div class="absolute inset-0">
      <FormMapBox
        :initial-center="[44.37425079345715, 33.270713427515616]"
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
