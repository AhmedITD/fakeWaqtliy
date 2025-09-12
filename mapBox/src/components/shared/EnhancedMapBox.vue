<template>
  <div class="mapbox-container">
    <!-- Map Controls -->
    <div class="map-controls">
      <div class="control-group">
        <label for="style-select">Map Style:</label>
        <select id="style-select" v-model="selectedStyle" @change="changeMapStyle">
          <option value="mapbox://styles/mapbox/streets-v12">Streets</option>
          <option value="mapbox://styles/mapbox/outdoors-v12">Outdoors</option>
          <option value="mapbox://styles/mapbox/light-v11">Light</option>
          <option value="mapbox://styles/mapbox/dark-v11">Dark</option>
          <option value="mapbox://styles/mapbox/satellite-v9">Satellite</option>
          <option value="mapbox://styles/mapbox/satellite-streets-v12">Satellite Streets</option>
        </select>
      </div>
      
      <div class="control-group">
        <button @click="loadMarkers" :disabled="loading" class="btn btn-primary">
          {{ loading ? 'Loading...' : 'Load Markers' }}
        </button>
        <button @click="toggleAddMode" :class="['btn', addMode ? 'btn-danger' : 'btn-success']">
          {{ addMode ? 'Cancel Add' : 'Add Marker' }}
        </button>
      </div>
    </div>

    <!-- Add Marker Form -->
    <div v-if="addMode" class="add-marker-form">
      <h3>Add New Marker</h3>
      <p>Click on the map to set the location</p>
      <div class="form-group">
        <input 
          v-model="newMarker.name" 
          type="text" 
          placeholder="Marker name" 
          class="form-input"
          required
        />
      </div>
      <div class="form-group">
        <textarea 
          v-model="newMarker.description" 
          placeholder="Description (optional)" 
          class="form-textarea"
        ></textarea>
      </div>
      <div class="form-group">
        <select v-model="newMarker.type" class="form-select">
          <option value="custom">Custom</option>
          <option value="city">City</option>
          <option value="landmark">Landmark</option>
          <option value="park">Park</option>
          <option value="monument">Monument</option>
        </select>
      </div>
      <div class="form-actions">
        <button @click="saveMarker" :disabled="!canSaveMarker" class="btn btn-primary">
          Save Marker
        </button>
        <button @click="cancelAddMode" class="btn btn-secondary">
          Cancel
        </button>
      </div>
    </div>

    <!-- Map Container -->
    <div id="enhanced-map" class="map-container"></div>

    <!-- Status Messages -->
    <div v-if="message" :class="['message', messageType]">
      {{ message }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, ref, computed } from 'vue'
import mapboxgl from 'mapbox-gl'
import 'mapbox-gl/dist/mapbox-gl.css'

// Types
interface Marker {
  id: number | string
  name: string
  coordinates: [number, number]
  description?: string
  type?: string
}

// Reactive state
const map = ref<mapboxgl.Map | null>(null)
const markers = ref<Marker[]>([])
const loading = ref(false)
const addMode = ref(false)
const selectedStyle = ref('mapbox://styles/mapbox/streets-v12')
const message = ref('')
const messageType = ref<'success' | 'error' | 'info'>('info')

const newMarker = ref({
  name: '',
  description: '',
  type: 'custom',
  coordinates: null as [number, number] | null
})

// Computed
const canSaveMarker = computed(() => 
  newMarker.value.name.trim() !== '' && newMarker.value.coordinates !== null
)

// API Base URL
const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api'

// Methods
const showMessage = (msg: string, type: 'success' | 'error' | 'info' = 'info') => {
  message.value = msg
  messageType.value = type
  setTimeout(() => {
    message.value = ''
  }, 5000)
}

const loadMarkers = async () => {
  loading.value = true
  try {
    const response = await fetch(`${API_BASE_URL}/map/markers`)
    const data = await response.json()
    
    if (data.success) {
      markers.value = data.data
      addMarkersToMap()
      showMessage(`Loaded ${markers.value.length} markers`, 'success')
    } else {
      showMessage('Failed to load markers', 'error')
    }
  } catch (error) {
    console.error('Error loading markers:', error)
    showMessage('Error loading markers', 'error')
  } finally {
    loading.value = false
  }
}

const addMarkersToMap = () => {
  if (!map.value) return

  // Clear existing markers
  const existingMarkers = document.querySelectorAll('.mapboxgl-marker')
  existingMarkers.forEach(marker => marker.remove())

  // Add new markers
  markers.value.forEach(marker => {
    const el = document.createElement('div')
    el.className = 'custom-marker'
    el.style.backgroundColor = getMarkerColor(marker.type || 'custom')
    el.style.width = '20px'
    el.style.height = '20px'
    el.style.borderRadius = '50%'
    el.style.border = '2px solid white'
    el.style.cursor = 'pointer'

    new mapboxgl.Marker(el)
      .setLngLat(marker.coordinates)
      .setPopup(
        new mapboxgl.Popup({ offset: 25 })
          .setHTML(`
            <div class="marker-popup">
              <h3>${marker.name}</h3>
              ${marker.description ? `<p>${marker.description}</p>` : ''}
              <small>Type: ${marker.type || 'custom'}</small>
            </div>
          `)
      )
      .addTo(map.value!)
  })
}

const getMarkerColor = (type: string): string => {
  const colors = {
    city: '#FF6B6B',
    landmark: '#4ECDC4',
    park: '#45B7D1',
    monument: '#96CEB4',
    custom: '#FFEAA7'
  }
  return colors[type as keyof typeof colors] || colors.custom
}

const changeMapStyle = () => {
  if (map.value) {
    map.value.setStyle(selectedStyle.value)
  }
}

const toggleAddMode = () => {
  addMode.value = !addMode.value
  if (!addMode.value) {
    cancelAddMode()
  }
}

const cancelAddMode = () => {
  addMode.value = false
  newMarker.value = {
    name: '',
    description: '',
    type: 'custom',
    coordinates: null
  }
}

const saveMarker = async () => {
  if (!canSaveMarker.value) return

  try {
    const response = await fetch(`${API_BASE_URL}/map/markers`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: newMarker.value.name,
        description: newMarker.value.description,
        type: newMarker.value.type,
        coordinates: newMarker.value.coordinates
      })
    })

    const data = await response.json()
    
    if (data.success) {
      markers.value.push(data.data)
      addMarkersToMap()
      showMessage('Marker added successfully!', 'success')
      cancelAddMode()
    } else {
      showMessage('Failed to add marker', 'error')
    }
  } catch (error) {
    console.error('Error saving marker:', error)
    showMessage('Error saving marker', 'error')
  }
}

// Lifecycle
onMounted(() => {
  const accessToken = import.meta.env.VITE_MAPBOX_ACCESS_TOKEN
  
  if (!accessToken) {
    showMessage('MapBox access token is required. Please set VITE_MAPBOX_ACCESS_TOKEN in your .env file.', 'error')
    return
  }
  
  mapboxgl.accessToken = accessToken

  map.value = new mapboxgl.Map({
    container: 'enhanced-map',
    style: selectedStyle.value,
    center: [-74.5, 40],
    zoom: 9
  })

  // Add controls
  map.value.addControl(new mapboxgl.NavigationControl())
  map.value.addControl(new mapboxgl.FullscreenControl())
  map.value.addControl(
    new mapboxgl.GeolocateControl({
      positionOptions: { enableHighAccuracy: true },
      trackUserLocation: true,
      showUserHeading: true
    })
  )

  // Add click handler for adding markers
  map.value.on('click', (e) => {
    if (addMode.value) {
      newMarker.value.coordinates = [e.lngLat.lng, e.lngLat.lat]
      showMessage('Location selected! Fill in the details and save.', 'info')
    }
  })

  // Load initial markers
  loadMarkers()
})

onUnmounted(() => {
  if (map.value) {
    map.value.remove()
  }
})
</script>

<style scoped>
.mapbox-container {
  width: 100%;
  position: relative;
}

.map-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding: 15px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  flex-wrap: wrap;
  gap: 15px;
}

.control-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

.control-group label {
  font-weight: 600;
  color: #333;
}

.add-marker-form {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.add-marker-form h3 {
  margin: 0 0 15px 0;
  color: #333;
}

.form-group {
  margin-bottom: 15px;
}

.form-input,
.form-textarea,
.form-select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.form-actions {
  display: flex;
  gap: 10px;
}

.map-container {
  width: 100%;
  height: 600px;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.btn {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: background-color 0.2s;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #0056b3;
}

.btn-success {
  background-color: #28a745;
  color: white;
}

.btn-success:hover:not(:disabled) {
  background-color: #1e7e34;
}

.btn-danger {
  background-color: #dc3545;
  color: white;
}

.btn-danger:hover:not(:disabled) {
  background-color: #c82333;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

.btn-secondary:hover:not(:disabled) {
  background-color: #545b62;
}

select {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background-color: white;
}

.message {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 12px 20px;
  border-radius: 4px;
  color: white;
  font-weight: 500;
  z-index: 1000;
  max-width: 300px;
}

.message.success {
  background-color: #28a745;
}

.message.error {
  background-color: #dc3545;
}

.message.info {
  background-color: #17a2b8;
}

/* Global marker popup styles */
:global(.marker-popup h3) {
  margin: 0 0 8px 0;
  color: #333;
  font-size: 16px;
}

:global(.marker-popup p) {
  margin: 0 0 8px 0;
  color: #666;
  font-size: 14px;
}

:global(.marker-popup small) {
  color: #999;
  font-size: 12px;
}
</style>
