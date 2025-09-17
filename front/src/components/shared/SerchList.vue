<script setup>
import SerchBar from '@/components/base/SerchBar.vue'
import List from '@/components/base/List.vue'
const emit = defineEmits({
  'searchButton': event,
});
</script>
<template>
    <form
        @submit.prevent="emit('searchButton')"
        class="pointer-events-auto w-full max-w-1/4 rounded-2xl border border-black/5 bg-white/90 p-4 shadow-xl ring-1 ring-black/5 backdrop-blur"
    >
        <SerchBar/>
        <List/>
    </form>
</template>