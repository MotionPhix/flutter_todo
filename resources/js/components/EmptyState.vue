<script setup lang="ts">
import { type Component } from 'vue';

defineProps<{
  title: string
  description: string
  icon?: Component
}>()
</script>

<template>
  <div class="flex flex-col items-center justify-center p-8 text-center">
    <component
      :is="icon"
      v-if="icon"
      class="w-12 h-12 text-muted-foreground/50 mb-4"
    />
    <h3 class="text-lg font-medium">{{ title }}</h3>
    <p class="text-sm text-muted-foreground mt-1">{{ description }}</p>
    <slot name="action" />
  </div>
</template>
