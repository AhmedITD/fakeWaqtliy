<template>
  <div id="map"></div>
</template>
<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue';
import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import type { FormMapBoxProps } from '@/types/props';
import { DEFAULT_FORM_MAPBOX_PROPS } from '@/types/props';
import { useMapStateStore } from '@/stores/mapStateStore';
const mapStateStore = useMapStateStore();
//props
  const props = withDefaults(defineProps<FormMapBoxProps>(), DEFAULT_FORM_MAPBOX_PROPS);
//emits
  const emit = defineEmits<{
    (e: 'map-click', event: mapboxgl.MapMouseEvent): void;
    (e: 'map-loaded'): void;
    (e: 'style-load'): void;
  }>();
//lifecycles
  onMounted(() => {
    //initialize map
      const accessToken = import.meta.env.VITE_MAPBOX_ACCESS_TOKEN
      if(!accessToken){console.error('MapBox access token is required. Please set VITE_MAPBOX_ACCESS_TOKEN in your .env file.');return;}
      mapboxgl.accessToken = accessToken
      //check if container exists
      const container = document.getElementById('map')
      if(!container){console.error('Map container element not found!');return;}
      try {
        const map = new mapboxgl.Map({
          container: 'map',
          style: props.mapConfig?.style || 'mapbox://styles/mapbox/streets-v12',
          center: props.initialCenter || [44.4, 33.32],
          zoom: props.initialZoom || 11
        })
        mapStateStore.setMap(map)
      } catch (error) {
        console.error('Error creating map instance:', error)
        return
      }
    //add map controls
    if (mapStateStore.map) {
        //add navigation control (zoom in/out buttons)
        mapStateStore.map.addControl(new mapboxgl.NavigationControl() as any)
        //add fullscreen control
        mapStateStore.map.addControl(new mapboxgl.FullscreenControl() as any)
        //add geolocate control
        mapStateStore.map.addControl(
          new mapboxgl.GeolocateControl({
            positionOptions: {
              enableHighAccuracy: true
            },
            trackUserLocation: true,
            showUserHeading: true
          }) as any
        )
        //set the default cursor to the add pin icon
        mapStateStore.updateMapCursor()
        //map events
        mapStateStore.map.on('load',()=>{emit('map-loaded');})
        mapStateStore.map.on('error', (e: any) => {console.error('MapBox error:', e);})
        mapStateStore.map.on('style.load', () => {emit('style-load');})
        mapStateStore.map.on('click', (e: any) => {emit('map-click', e);})
      }
  })
  onUnmounted(() => {if(mapStateStore.map){mapStateStore.map.remove();}})
  //expose map instance for parent components
  defineExpose({getMap: () => mapStateStore.map})
</script>
<style scoped>
#map {
  width: 100%;
  height: 100vh;
  min-height: 100vh;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}
</style>
